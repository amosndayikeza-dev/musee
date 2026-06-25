<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\OeuvreModel;
use App\Models\AuteurModel;
use App\Models\CategorieModel;
use App\Middlewares\AuthMiddleware;
use App\Services\AuditService;
use App\Services\ExcelExportService;
use App\Services\PdfService;
use App\Middlewares\SessionMiddleware;

class OeuvreController extends Controller {
    
    private $oeuvreModel;
    private $auteurModel;
    private $categorieModel;

    public function __construct() {
        SessionMiddleware::check();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        AuthMiddleware::requireAdminOrConservateur();
        
        $this->oeuvreModel = new OeuvreModel();
        $this->auteurModel = new AuteurModel();
        $this->categorieModel = new CategorieModel();
    }

    /**
     * Liste des œuvres avec recherche et filtres
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $auteur_id = $_GET['auteur_id'] ?? '';
        $categorie_id = $_GET['categorie_id'] ?? '';
        $statut = $_GET['statut'] ?? '';
        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';
        
        $oeuvres = $this->oeuvreModel->getWithFilters([
            'keyword' => $keyword,
            'auteur_id' => $auteur_id,
            'categorie_id' => $categorie_id,
            'statut' => $statut,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ]);
        
        $auteurs = $this->auteurModel->getAll();
        $categories = $this->categorieModel->getAll();
        $statuts = ['exposé', 'en réserve', 'en restauration', 'en prêt'];
        
        $this->render('index', [
            'oeuvres' => $oeuvres,
            'auteurs' => $auteurs,
            'categories' => $categories,
            'statuts' => $statuts,
            'keyword' => $keyword,
            'auteur_id' => $auteur_id,
            'categorie_id' => $categorie_id,
            'statut' => $statut,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'pageTitle' => 'Gestion des Œuvres'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $auteurs = $this->auteurModel->getAll();
        $categories = $this->categorieModel->getAll();
        $this->render('create', [
            'auteurs' => $auteurs,
            'categories' => $categories,
            'pageTitle' => 'Ajouter une œuvre'
        ]);
    }

    /**
     * Enregistrer une nouvelle œuvre
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/oeuvre/create');
            return;
        }

        $data = [
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date_creation' => $_POST['date_creation'] ?? null,
            'technique' => trim($_POST['technique'] ?? ''),
            'dimensions' => trim($_POST['dimensions'] ?? ''),
            'auteur_id' => $_POST['auteur_id'] ?? null,
            'categorie_id' => $_POST['categorie_id'] ?? null,
            'statut' => $_POST['statut'] ?? 'en réserve'
        ];

        if (empty($data['titre'])) {
            $this->render('create', [
                'error' => 'Le titre est obligatoire',
                'auteurs' => $this->auteurModel->getAll(),
                'categories' => $this->categorieModel->getAll(),
                'old' => $data,
                'pageTitle' => 'Ajouter une œuvre'
            ]);
            return;
        }

        // Gestion de l'upload de photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_DIR . 'oeuvres/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $data['photo'] = 'uploads/oeuvres/' . $filename;
            }
        }

        // 1. Insérer
        $id = $this->oeuvreModel->insert($data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('INSERT', 'oeuvre', $id, null, $data);

        // 3. Redirection
        $_SESSION['success'] = 'Œuvre ajoutée avec succès !';
        $this->redirect('admin/oeuvre/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $oeuvre = $this->oeuvreModel->getByIdWithDetails($id);
        if (!$oeuvre) {
            $this->redirect('admin/oeuvre/index');
            return;
        }
        $auteurs = $this->auteurModel->getAll();
        $categories = $this->categorieModel->getAll();
        $this->render('edit', [
            'oeuvre' => $oeuvre,
            'auteurs' => $auteurs,
            'categories' => $categories,
            'pageTitle' => 'Modifier une œuvre'
        ]);
    }

    /**
     * Mettre à jour une œuvre
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/oeuvre/edit/' . $id);
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->oeuvreModel->getById($id);
        if (!$old) {
            $this->redirect('admin/oeuvre/index');
            return;
        }

        $data = [
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date_creation' => $_POST['date_creation'] ?? null,
            'technique' => trim($_POST['technique'] ?? ''),
            'dimensions' => trim($_POST['dimensions'] ?? ''),
            'auteur_id' => $_POST['auteur_id'] ?? null,
            'categorie_id' => $_POST['categorie_id'] ?? null,
            'statut' => $_POST['statut'] ?? 'en réserve'
        ];

        if (empty($data['titre'])) {
            $_SESSION['error'] = 'Le titre est obligatoire';
            $this->redirect('admin/oeuvre/edit/' . $id);
            return;
        }

        // Gestion de la photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_DIR . 'oeuvres/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $data['photo'] = 'uploads/oeuvres/' . $filename;
            }
        }

        // 1. Mise à jour
        $this->oeuvreModel->update($id, $data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'oeuvre', $id, (array)$old, $data);

        // 3. Redirection
        $_SESSION['success'] = 'Œuvre modifiée avec succès !';
        $this->redirect('admin/oeuvre/index');
    }

    /**
     * Supprimer une œuvre (soft delete)
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/oeuvre/index');
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->oeuvreModel->getById($id);
        if (!$old) {
            $this->redirect('admin/oeuvre/index');
            return;
        }

        // 1. Soft delete
        $this->oeuvreModel->delete($id);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('DELETE', 'oeuvre', $id, (array)$old, null);

        // 3. Redirection
        $_SESSION['success'] = 'Œuvre supprimée avec succès !';
        $this->redirect('admin/oeuvre/index');
    }

    /**
     * Voir le détail d'une œuvre
     */
    public function showAction($id) {
        $oeuvre = $this->oeuvreModel->getByIdWithDetails($id);
        if (!$oeuvre) {
            $this->redirect('admin/oeuvre/index');
            return;
        }
        $this->render('show', [
            'oeuvre' => $oeuvre,
            'pageTitle' => 'Détail de l\'œuvre'
        ]);
    }

    /**
     * Recherche AJAX
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $filters = [
            'keyword' => $keyword,
            'auteur_id' => $_GET['auteur_id'] ?? '',
            'categorie_id' => $_GET['categorie_id'] ?? '',
            'statut' => $_GET['statut'] ?? '',
            'date_debut' => $_GET['date_debut'] ?? '',
            'date_fin' => $_GET['date_fin'] ?? '',
        ];

        $oeuvres = $this->oeuvreModel->getWithFilters($filters);
        header('Content-Type: application/json');
        echo json_encode($oeuvres);
        exit;
    }

    /**
     * Export PDF
     */
    public function exportPdfAction() {
    // Récupérer les filtres depuis l'URL
    $filters = [
        'keyword' => $_GET['keyword'] ?? '',
        'auteur_id' => $_GET['auteur_id'] ?? '',
        'categorie_id' => $_GET['categorie_id'] ?? '',
        'statut' => $_GET['statut'] ?? '',
        'date_debut' => $_GET['date_debut'] ?? '',
        'date_fin' => $_GET['date_fin'] ?? ''
    ];

    // Récupérer les données filtrées
    $oeuvres = $this->oeuvreModel->getWithFilters($filters);

    // Générer le HTML pour le PDF
    $html = '<h1 style="text-align:center;">Liste des œuvres (recherche)</h1>';
    $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
    if (!empty($filters['keyword'])) {
        $html .= '<p style="text-align:center;">Recherche : "' . htmlspecialchars($filters['keyword']) . '"</p>';
    }
    $html .= '<hr>';
    $html .= '<table border="1" cellpadding="5" style="width:100%; border-collapse:collapse;">';
    $html .= '<thead><tr><th>ID</th><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Statut</th></tr></thead>';
    $html .= '<tbody>';
    foreach ($oeuvres as $oeuvre) {
        $html .= '<tr>';
        $html .= '<td>' . $oeuvre->id . '</td>';
        $html .= '<td>' . htmlspecialchars($oeuvre->titre) . '</td>';
        $html .= '<td>' . htmlspecialchars($oeuvre->auteur_nom ?? 'Non défini') . '</td>';
        $html .= '<td>' . htmlspecialchars($oeuvre->categorie_nom ?? 'Non défini') . '</td>';
        $html .= '<td>' . $oeuvre->statut . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';

    $pdfService = new PdfService();
    $pdfService->download($html, 'oeuvres_' . date('Y-m-d') . '.pdf');
}

    /**
     * Export Excel
     */
    public function exportExcelAction() {
    $filters = [
        'keyword' => $_GET['keyword'] ?? '',
        'auteur_id' => $_GET['auteur_id'] ?? '',
        'categorie_id' => $_GET['categorie_id'] ?? '',
        'statut' => $_GET['statut'] ?? '',
        'date_debut' => $_GET['date_debut'] ?? '',
        'date_fin' => $_GET['date_fin'] ?? ''
    ];

    $oeuvres = $this->oeuvreModel->getWithFilters($filters);

    $headers = ['ID', 'Titre', 'Auteur', 'Catégorie', 'Statut', 'Date création'];
    $data = [];
    foreach ($oeuvres as $oeuvre) {
        $data[] = [
            $oeuvre->id,
            $oeuvre->titre,
            $oeuvre->auteur_nom ?? 'Non défini',
            $oeuvre->categorie_nom ?? 'Non défini',
            $oeuvre->statut,
            $oeuvre->date_creation ?? ''
        ];
    }

    $excel = new ExcelExportService();
    $excel->export($data, $headers, 'oeuvres_' . date('Y-m-d'), 'Liste des œuvres');
}

    /**
     * Archiver une œuvre
     */
    public function archiveAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/oeuvre/index');
            return;
        }
        
        $old = $this->oeuvreModel->getById($id);
        if (!$old) {
            $this->redirect('admin/oeuvre/index');
            return;
        }
        
        $this->oeuvreModel->archive($id);
        
        $audit = new AuditService();
        $audit->log('ARCHIVE', 'oeuvre', $id, (array)$old, ['archive' => 1]);
        
        $_SESSION['success'] = 'Œuvre archivée avec succès !';
        $this->redirect('admin/oeuvre/index');
    }

    /**
     * Restaurer une œuvre archivée
     */
    public function unarchiveAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/oeuvre/index');
            return;
        }
        
        $old = $this->oeuvreModel->getById($id);
        if (!$old) {
            $this->redirect('admin/oeuvre/index');
            return;
        }
        
        $this->oeuvreModel->unarchive($id);
        
        $audit = new AuditService();
        $audit->log('UNARCHIVE', 'oeuvre', $id, (array)$old, ['archive' => 0]);
        
        $_SESSION['success'] = 'Œuvre restaurée avec succès !';
        $this->redirect('admin/oeuvre/index');
    }
}
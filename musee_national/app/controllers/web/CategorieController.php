<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\CategorieModel;
use App\Services\PdfExportService;
use App\Middlewares\AuthMiddleware;
use App\Services\ExcelExportService;
use App\Services\AuditService;
use App\Middlewares\SessionMiddleware;

class CategorieController extends Controller {
    private $categorieModel;

    public function __construct() {
        SessionMiddleware::check();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        AuthMiddleware::requireAdminOrConservateur();
        $this->categorieModel = new CategorieModel();
    }

    /**
     * Liste des catégories avec recherche
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        
        if (!empty($keyword)) {
            $categories = $this->categorieModel->search($keyword);
        } else {
            $categories = $this->categorieModel->getOeuvresCount();
        }
        
        $this->render('index', [
            'categories' => $categories,
            'keyword' => $keyword,
            'pageTitle' => 'Gestion des Catégories'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $this->render('create', [
            'pageTitle' => 'Ajouter une catégorie'
        ]);
    }

    /**
     * Enregistrer une catégorie
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/categorie/create');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];

        if (empty($data['nom'])) {
            $this->render('create', [
                'error' => 'Le nom de la catégorie est obligatoire',
                'old' => $data,
                'pageTitle' => 'Ajouter une catégorie'
            ]);
            return;
        }

        $existing = $this->categorieModel->findByName($data['nom']);
        if ($existing) {
            $this->render('create', [
                'error' => 'Une catégorie avec ce nom existe déjà',
                'old' => $data,
                'pageTitle' => 'Ajouter une catégorie'
            ]);
            return;
        }

        // 1. Insertion
        $id = $this->categorieModel->insert($data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('INSERT', 'categorie', $id, null, $data);

        // 3. Redirection
        $_SESSION['success'] = "La catégorie a été ajoutée avec succès !";
        $this->redirect('admin/categorie/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $categorie = $this->categorieModel->getById($id);
        if (!$categorie) {
            $this->redirect('admin/categorie/index');
            return;
        }
        $this->render('edit', [
            'categorie' => $categorie,
            'pageTitle' => 'Modifier une catégorie'
        ]);
    }

    /**
     * Mettre à jour une catégorie
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/categorie/edit/' . $id);
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->categorieModel->getById($id);
        if (!$old) {
            $this->redirect('admin/categorie/index');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];

        if (empty($data['nom'])) {
            $_SESSION['error'] = 'Le nom de la catégorie est obligatoire';
            $this->redirect('admin/categorie/edit/' . $id);
            return;
        }

        // Vérifier si le nom existe déjà pour un autre ID
        $existing = $this->categorieModel->findByName($data['nom']);
        if ($existing && $existing->id != $id) {
            $_SESSION['error'] = 'Une catégorie avec ce nom existe déjà';
            $this->redirect('admin/categorie/edit/' . $id);
            return;
        }

        // 1. Mise à jour
        $this->categorieModel->update($id, $data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'categorie', $id, (array)$old, $data);

        // 3. Redirection
        $_SESSION['success'] = "La catégorie a été modifiée avec succès !";
        $this->redirect('admin/categorie/index');
    }

    /**
     * Supprimer une catégorie (soft delete)
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/categorie/index');
            return;
        }

        // Vérifier si la catégorie a des œuvres associées
        $oeuvres = $this->categorieModel->getOeuvres($id);
        if (count($oeuvres) > 0) {
            $_SESSION['error'] = "Impossible de supprimer cette catégorie car elle a " . count($oeuvres) . " œuvre(s) associée(s).";
            $this->redirect('admin/categorie/index');
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->categorieModel->getById($id);
        if (!$old) {
            $this->redirect('admin/categorie/index');
            return;
        }

        // 1. Suppression (soft delete)
        $this->categorieModel->delete($id);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('DELETE', 'categorie', $id, (array)$old, null);

        // 3. Redirection
        $_SESSION['success'] = "La catégorie a été supprimée avec succès !";
        $this->redirect('admin/categorie/index');
    }

    /**
     * Voir le détail d'une catégorie
     */
    public function showAction($id) {
        $categorie = $this->categorieModel->getById($id);
        if (!$categorie) {
            $this->redirect('admin/categorie/index');
            return;
        }
        $oeuvres = $this->categorieModel->getOeuvres($id);
        $this->render('show', [
            'categorie' => $categorie,
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Détail de la catégorie'
        ]);
    }

    /**
     * Recherche AJAX pour les catégories
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $resultats = [];
        if (!empty($keyword)) {
            $resultats = $this->categorieModel->search($keyword);
        }
        header('Content-Type: application/json');
        echo json_encode($resultats);
        exit;
    }

    /**
     * Export PDF de la liste des catégories
     */
    public function exportPdfAction() {
        $categories = $this->categorieModel->getOeuvresCount();
        
        $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Catégories</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px;">';
        $html .= '<thead>
                    <tr style="background:#1a2a3a; color:white;">
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Nombre d\'œuvres</th>
                    </tr>
                  </thead><tbody>';
        foreach ($categories as $categorie) {
            $html .= '<tr>
                        <td>' . $categorie->id . '</td>
                        <td>' . htmlspecialchars($categorie->nom) . '</td>
                        <td>' . htmlspecialchars($categorie->description ?? '') . '</td>
                        <td>' . ($categorie->nb_oeuvres ?? 0) . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        
        $pdfService = new PdfExportService();
        $pdfService->generateFromHtml($html, 'liste_categories', 'portrait');
    }

    /**
     * Export Excel de la liste des catégories
     */
    public function exportExcelAction() {
        $categories = $this->categorieModel->getOeuvresCount();
        
        $headers = ['ID', 'Nom', 'Description', 'Nombre d\'œuvres'];
        $data = [];
        foreach ($categories as $categorie) {
            $data[] = [
                $categorie->id,
                $categorie->nom,
                $categorie->description ?? '',
                $categorie->nb_oeuvres ?? 0
            ];
        }
        
        $excel = new ExcelExportService();
        $excel->export($data, $headers, 'categories_' . date('Y-m-d'), 'Liste des catégories');
    }
}
<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\AuteurModel;
use App\Services\PdfExportService;
use App\Middlewares\AuthMiddleware;
use App\Services\AuditService;
use App\Services\ExcelExportService;

class AuteurController extends Controller {
    private $auteurModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        // Admin ou conservateur peuvent gérer les auteurs
        AuthMiddleware::requireAdminOrConservateur();
        $this->auteurModel = new AuteurModel();
    }

    /**
     * Liste des auteurs
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $nationalite = $_GET['nationalite'] ?? '';

        if (!empty($keyword)) {
            $auteurs = $this->auteurModel->search($keyword);
        } elseif (!empty($nationalite)) {
            $auteurs = $this->auteurModel->getByNationalite($nationalite);
        } else {
            $auteurs = $this->auteurModel->getAll();
        }

        $nationalites = $this->auteurModel->getAllNationalites();

        $this->render('index', [
            'auteurs' => $auteurs,
            'keyword' => $keyword,
            'nationalite' => $nationalite,
            'nationalites' => $nationalites,
            'pageTitle' => 'Gestion des Auteurs'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $this->render('create', ['pageTitle' => 'Ajouter un Auteur']);
    }

    /**
     * Enregistrer un nouvel auteur
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/auteur/create');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'biographie' => trim($_POST['biographie'] ?? ''),
            'date_naissance' => !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null,
            'date_deces' => !empty($_POST['date_deces']) ? $_POST['date_deces'] : null, // CORRECTION
            'nationalite' => trim($_POST['nationalite'] ?? '')
        ];

        if (empty($data['nom'])) {
            $this->render('create', [
                'error' => 'Le nom est obligatoire',
                'old' => $data,
                'pageTitle' => 'Ajouter un Auteur'
            ]);
            return;
        }

        $data['matricule'] = $this->auteurModel->generateMatricule();

        // 1. Insertion
        $id = $this->auteurModel->insert($data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('INSERT', 'auteur', $id, null, $data);

        // 3. Redirection
        $_SESSION['success'] = 'Auteur ajouté avec succès !';
        $this->redirect('admin/auteur/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $auteur = $this->auteurModel->getById($id);
        if (!$auteur) {
            $this->redirect('admin/auteur/index');
            return;
        }
        $this->render('edit', [
            'auteur' => $auteur,
            'pageTitle' => 'Modifier un Auteur'
        ]);
    }

    /**
     * Mettre à jour un auteur
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/auteur/edit/' . $id);
            return;
        }

        $old = $this->auteurModel->getById($id);
        if (!$old) {
            $this->redirect('admin/auteur/index');
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'biographie' => trim($_POST['biographie'] ?? ''),
            'date_naissance' => !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null,
            'date_deces' => !empty($_POST['date_deces']) ? $_POST['date_deces'] : null,
            'nationalite' => trim($_POST['nationalite'] ?? '')
        ];

        if (empty($data['nom'])) {
            $_SESSION['error'] = 'Le nom est obligatoire';
            $this->redirect('admin/auteur/edit/' . $id);
            return;
        }

        // 1. Mise à jour
        $this->auteurModel->update($id, $data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'auteur', $id, (array)$old, $data);

        // 3. Redirection
        $_SESSION['success'] = 'Auteur modifié avec succès !';
        $this->redirect('admin/auteur/index');
    }

    /**
     * Supprimer un auteur (Admin uniquement)
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/auteur/index');
            return;
        }

        $old = $this->auteurModel->getById($id);
        if (!$old) {
            $this->redirect('admin/auteur/index');
            return;
        }

        // Vérifier si l'auteur a des œuvres associées
        $oeuvresAssociees = $this->auteurModel->getOeuvres($id);
        if (!empty($oeuvresAssociees)) {
            $_SESSION['error'] = 'Impossible de supprimer cet auteur car il est associé à ' . count($oeuvresAssociees) . ' œuvre(s).';
            $this->redirect('admin/auteur/index');
            return;
        }

        // 1. Suppression
        $this->auteurModel->delete($id); // CORRECTION : un seul appel

        // 2. Audit
        $audit = new AuditService();
        $audit->log('DELETE', 'auteur', $id, (array)$old, null);

        // 3. Redirection
        $_SESSION['success'] = 'Auteur supprimé avec succès !';
        $this->redirect('admin/auteur/index');
    }

    /**
     * Détail d'un auteur
     */
    public function showAction($id) {
        $auteur = $this->auteurModel->getById($id);
        if (!$auteur) {
            $this->redirect('admin/auteur/index');
            return;
        }
        $oeuvres = $this->auteurModel->getOeuvres($id);
        $this->render('show', [
            'auteur' => $auteur,
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Détails de l\'Auteur'
        ]);
    }

    /**
     * Recherche AJAX
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $results = [];
        if (!empty($keyword)) {
            $results = $this->auteurModel->search($keyword);
        }
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    /**
     * Export PDF
     */
    public function exportPdfAction() {
        $auteurs = $this->auteurModel->getAll();

        $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Auteurs</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px;">';
        $html .= '<thead><tr style="background:#1a2a3a; color:white;">
                    <th>Matricule</th><th>Nom</th><th>Prénom</th>
                    <th>Nationalité</th><th>Date naissance</th><th>Date décès</th>
                  </tr></thead><tbody>';
        foreach ($auteurs as $auteur) {
            $html .= '<tr>
                        <td>' . $auteur->matricule . '</td>
                        <td>' . htmlspecialchars($auteur->nom) . '</td>
                        <td>' . htmlspecialchars($auteur->prenom ?? '') . '</td>
                        <td>' . htmlspecialchars($auteur->nationalite ?? '') . '</td>
                        <td>' . ($auteur->date_naissance ?? '') . '</td>
                        <td>' . ($auteur->date_deces ?? '') . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';

        $pdfService = new PdfExportService();
        $pdfService->generateFromHtml($html, 'liste_auteurs', 'landscape');
    }

    public function exportExcelAction() {
    $auteurs = $this->auteurModel->getAll();
    
    $headers = ['Matricule', 'Nom', 'Prénom', 'Nationalité', 'Date naissance', 'Date décès'];
    $data = [];
    foreach ($auteurs as $auteur) {
        $data[] = [
            $auteur->matricule,
            $auteur->nom,
            $auteur->prenom ?? '',
            $auteur->nationalite ?? '',
            $auteur->date_naissance ?? '',
            $auteur->date_deces ?? ''
        ];
    }
    
    $excel = new ExcelExportService();
    $excel->export($data, $headers, 'auteurs_' . date('Y-m-d'), 'Liste des auteurs');
}
}
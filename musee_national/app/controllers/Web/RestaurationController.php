<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\RestaurationModel;
use App\Models\OeuvreModel;
use App\Services\PdfExportService;
use App\Middlewares\AuthMiddleware;
use App\Services\ExcelExportService;
use App\Services\AuditService;
use App\Middlewares\SessionMiddleware;

class RestaurationController extends Controller {
    private $restaurationModel;
    private $oeuvreModel;

    public function __construct() {
        SessionMiddleware::check();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        AuthMiddleware::requireAdminOrConservateur();
        $this->restaurationModel = new RestaurationModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * Liste des restaurations avec recherche et filtres
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $oeuvre_id = $_GET['oeuvre_id'] ?? '';
        $statut = $_GET['statut'] ?? '';
        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';
        
        $restaurations = $this->restaurationModel->getWithFilters([
            'keyword' => $keyword,
            'oeuvre_id' => $oeuvre_id,
            'statut' => $statut,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ]);
        
        $oeuvres = $this->oeuvreModel->getAll();
        $statuts = ['en cours', 'terminée'];
        
        $this->render('index', [
            'restaurations' => $restaurations,
            'oeuvres' => $oeuvres,
            'statuts' => $statuts,
            'keyword' => $keyword,
            'oeuvre_id' => $oeuvre_id,
            'statut' => $statut,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'pageTitle' => 'Gestion des Restaurations'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $oeuvres = $this->oeuvreModel->getAll();
        $this->render('create', [
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Ajouter une restauration'
        ]);
    }

    /**
     * Enregistrer une restauration
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/restauration/create');
            return;
        }

        $data = [
            'oeuvre_id' => $_POST['oeuvre_id'] ?? null,
            'date_debut' => !empty($_POST['date_debut']) ? $_POST['date_debut'] : null,
            'date_fin' => !empty($_POST['date_fin']) ? $_POST['date_fin'] : null,
            'responsable' => trim($_POST['responsable'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'cout' => !empty($_POST['cout']) ? str_replace(',', '.', $_POST['cout']) : null
        ];

        if (empty($data['oeuvre_id']) || empty($data['date_debut'])) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'L\'œuvre et la date de début sont obligatoires',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter une restauration'
            ]);
            return;
        }

        if ($data['date_fin'] && $data['date_fin'] < $data['date_debut']) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'La date de fin doit être postérieure à la date de début',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter une restauration'
            ]);
            return;
        }

        $restaurationExistante = $this->restaurationModel->findActiveRestaurationByOeuvre($data['oeuvre_id']);
        if ($restaurationExistante) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'Cette œuvre est déjà en restauration (ID: ' . $restaurationExistante->id . ')',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter une restauration'
            ]);
            return;
        }

        // 1. Insertion
        $id = $this->restaurationModel->insert($data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('INSERT', 'restauration', $id, null, $data);

        // 3. Redirection
        $_SESSION['success'] = "La restauration a été ajoutée avec succès !";
        $this->redirect('admin/restauration/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $restauration = $this->restaurationModel->getById($id);
        if (!$restauration) {
            $this->redirect('admin/restauration/index');
            return;
        }
        $oeuvres = $this->oeuvreModel->getAll();
        $this->render('edit', [
            'restauration' => $restauration,
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Modifier une restauration'
        ]);
    }

    /**
     * Mettre à jour une restauration
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/restauration/edit/' . $id);
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->restaurationModel->getById($id);
        if (!$old) {
            $this->redirect('admin/restauration/index');
            return;
        }

        $data = [
            'oeuvre_id' => $_POST['oeuvre_id'] ?? null,
            'date_debut' => !empty($_POST['date_debut']) ? $_POST['date_debut'] : null,
            'date_fin' => !empty($_POST['date_fin']) ? $_POST['date_fin'] : null,
            'responsable' => trim($_POST['responsable'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'cout' => !empty($_POST['cout']) ? str_replace(',', '.', $_POST['cout']) : null
        ];

        if (empty($data['oeuvre_id']) || empty($data['date_debut'])) {
            $_SESSION['error'] = 'L\'œuvre et la date de début sont obligatoires';
            $this->redirect('admin/restauration/edit/' . $id);
            return;
        }

        if ($data['date_fin'] && $data['date_fin'] < $data['date_debut']) {
            $_SESSION['error'] = 'La date de fin doit être postérieure à la date de début';
            $this->redirect('admin/restauration/edit/' . $id);
            return;
        }

        $restaurationExistante = $this->restaurationModel->findActiveRestaurationByOeuvre($data['oeuvre_id']);
        if ($restaurationExistante && $restaurationExistante->id != $id) {
            $_SESSION['error'] = 'Cette œuvre est déjà en restauration (ID: ' . $restaurationExistante->id . ')';
            $this->redirect('admin/restauration/edit/' . $id);
            return;
        }

        // 1. Mise à jour
        $this->restaurationModel->update($id, $data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'restauration', $id, (array)$old, $data);

        // 3. Redirection
        $_SESSION['success'] = "La restauration a été modifiée avec succès !";
        $this->redirect('admin/restauration/index');
    }

    /**
     * Supprimer une restauration (soft delete)
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/restauration/index');
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->restaurationModel->getById($id);
        if (!$old) {
            $this->redirect('admin/restauration/index');
            return;
        }

        // 1. Soft delete
        $this->restaurationModel->delete($id);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('DELETE', 'restauration', $id, (array)$old, null);

        // 3. Redirection
        $_SESSION['success'] = "La restauration a été supprimée avec succès !";
        $this->redirect('admin/restauration/index');
    }

    /**
     * Voir le détail d'une restauration
     */
    public function showAction($id) {
        $restauration = $this->restaurationModel->getWithOeuvre($id);
        if (!$restauration) {
            $this->redirect('admin/restauration/index');
            return;
        }
        $this->render('show', [
            'restauration' => $restauration,
            'pageTitle' => 'Détail de la restauration'
        ]);
    }

    /**
     * Terminer une restauration (marquer comme terminée)
     */
    public function completeAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/restauration/index');
            return;
        }

        // Récupérer les anciennes données pour l'audit
        $old = $this->restaurationModel->getById($id);
        if (!$old) {
            $this->redirect('admin/restauration/index');
            return;
        }

        // 1. Marquer comme terminée
        $this->restaurationModel->terminerRestauration($id);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'restauration', $id, (array)$old, ['date_fin' => date('Y-m-d')]);

        // 3. Redirection
        $_SESSION['success'] = "La restauration a été marquée comme terminée !";
        $this->redirect('admin/restauration/index');
    }

    /**
     * Recherche AJAX pour les restaurations
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $resultats = [];
        if (!empty($keyword)) {
            $resultats = $this->restaurationModel->search($keyword);
        }
        header('Content-Type: application/json');
        echo json_encode($resultats);
        exit;
    }

    /**
     * Export PDF
     */
    public function exportPdfAction() {
        $restaurations = $this->restaurationModel->getAll();
        
        $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Restaurations</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px;">';
        $html .= '<thead>
                    <tr style="background:#1a2a3a; color:white;">
                        <th>ID</th>
                        <th>Œuvre</th>
                        <th>Responsable</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Coût (€)</th>
                    </tr>
                  </thead><tbody>';
        foreach ($restaurations as $restauration) {
            $oeuvre = $this->oeuvreModel->getById($restauration->oeuvre_id);
            $html .= '<tr>
                        <td>' . $restauration->id . '</td>
                        <td>' . htmlspecialchars($oeuvre->titre ?? 'Non trouvée') . '</td>
                        <td>' . htmlspecialchars($restauration->responsable ?? '') . '</td>
                        <td>' . date('d/m/Y', strtotime($restauration->date_debut)) . '</td>
                        <td>' . ($restauration->date_fin ? date('d/m/Y', strtotime($restauration->date_fin)) : 'En cours') . '</td>
                        <td>' . number_format($restauration->cout ?? 0, 2) . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        
        $pdfService = new PdfExportService();
        $pdfService->generateFromHtml($html, 'liste_restaurations', 'landscape');
    }

    /**
     * Export Excel
     */
    public function exportExcelAction() {
        $restaurations = $this->restaurationModel->getAll();
        
        $headers = ['ID', 'Œuvre', 'Responsable', 'Date début', 'Date fin', 'Coût (€)', 'Description'];
        $data = [];
        foreach ($restaurations as $restauration) {
            $oeuvre = $this->oeuvreModel->getById($restauration->oeuvre_id);
            $data[] = [
                $restauration->id,
                $oeuvre->titre ?? 'Non trouvée',
                $restauration->responsable ?? '',
                date('d/m/Y', strtotime($restauration->date_debut)),
                $restauration->date_fin ? date('d/m/Y', strtotime($restauration->date_fin)) : 'En cours',
                number_format($restauration->cout ?? 0, 2),
                $restauration->description ?? ''
            ];
        }
        
        $excel = new ExcelExportService();
        $excel->export($data, $headers, 'restaurations_' . date('Y-m-d'), 'Liste des restaurations');
    }
}
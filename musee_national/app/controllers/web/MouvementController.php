<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\MouvementModel;
use App\Models\OeuvreModel;
use App\Services\PdfExportService;
use App\Middlewares\AuthMiddleware;
use App\Services\ExcelExportService;
use App\Services\AuditService;
use App\Middlewares\SessionMiddleware;

class MouvementController extends Controller {
    private $mouvementModel;
    private $oeuvreModel;

    public function __construct() {
        SessionMiddleware::check();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        AuthMiddleware::requireAdminOrConservateur();
        $this->mouvementModel = new MouvementModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * Liste des mouvements avec recherche et filtres
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $oeuvre_id = $_GET['oeuvre_id'] ?? '';
        $type = $_GET['type'] ?? '';
        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';
        
        $mouvements = $this->mouvementModel->getWithFilters([
            'keyword' => $keyword,
            'oeuvre_id' => $oeuvre_id,
            'type' => $type,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ]);
        
        $oeuvres = $this->oeuvreModel->getAll();
        $types = ['entrée', 'sortie'];
        
        $this->render('index', [
            'mouvements' => $mouvements,
            'oeuvres' => $oeuvres,
            'types' => $types,
            'keyword' => $keyword,
            'oeuvre_id' => $oeuvre_id,
            'type' => $type,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'pageTitle' => 'Gestion des Mouvements'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $oeuvres = $this->oeuvreModel->getAll();
        $this->render('create', [
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Ajouter un mouvement'
        ]);
    }

    /**
     * Enregistrer un mouvement
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/mouvement/create');
            return;
        }

        $data = [
            'oeuvre_id' => $_POST['oeuvre_id'] ?? null,
            'type' => $_POST['type'] ?? 'entrée',
            'date' => !empty($_POST['date']) ? $_POST['date'] : null,
            'provenance' => trim($_POST['provenance'] ?? ''),
            'destination' => trim($_POST['destination'] ?? ''),
            'responsable' => trim($_POST['responsable'] ?? '')
        ];

        if (empty($data['oeuvre_id']) || empty($data['date'])) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'L\'œuvre et la date sont obligatoires',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter un mouvement'
            ]);
            return;
        }

        if ($data['type'] === 'sortie' && empty($data['destination'])) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'La destination est obligatoire pour une sortie',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter un mouvement'
            ]);
            return;
        }

        if ($data['type'] === 'entrée' && empty($data['provenance'])) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'La provenance est obligatoire pour une entrée',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter un mouvement'
            ]);
            return;
        }

        // 1. Insertion
        $id = $this->mouvementModel->insert($data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('INSERT', 'mouvement', $id, null, $data);

        // 3. Redirection
        $_SESSION['success'] = "Le mouvement a été ajouté avec succès !";
        $this->redirect('admin/mouvement/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $mouvement = $this->mouvementModel->getById($id);
        if (!$mouvement) {
            $this->redirect('admin/mouvement/index');
            return;
        }
        $oeuvres = $this->oeuvreModel->getAll();
        $this->render('edit', [
            'mouvement' => $mouvement,
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Modifier un mouvement'
        ]);
    }

    /**
     * Mettre à jour un mouvement
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/mouvement/edit/' . $id);
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->mouvementModel->getById($id);
        if (!$old) {
            $this->redirect('admin/mouvement/index');
            return;
        }

        $data = [
            'oeuvre_id' => $_POST['oeuvre_id'] ?? null,
            'type' => $_POST['type'] ?? 'entrée',
            'date' => !empty($_POST['date']) ? $_POST['date'] : null,
            'provenance' => trim($_POST['provenance'] ?? ''),
            'destination' => trim($_POST['destination'] ?? ''),
            'responsable' => trim($_POST['responsable'] ?? '')
        ];

        if (empty($data['oeuvre_id']) || empty($data['date'])) {
            $_SESSION['error'] = 'L\'œuvre et la date sont obligatoires';
            $this->redirect('admin/mouvement/edit/' . $id);
            return;
        }

        if ($data['type'] === 'sortie' && empty($data['destination'])) {
            $_SESSION['error'] = 'La destination est obligatoire pour une sortie';
            $this->redirect('admin/mouvement/edit/' . $id);
            return;
        }

        if ($data['type'] === 'entrée' && empty($data['provenance'])) {
            $_SESSION['error'] = 'La provenance est obligatoire pour une entrée';
            $this->redirect('admin/mouvement/edit/' . $id);
            return;
        }

        // 1. Mise à jour
        $this->mouvementModel->update($id, $data);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('UPDATE', 'mouvement', $id, (array)$old, $data);

        // 3. Redirection
        $_SESSION['success'] = "Le mouvement a été modifié avec succès !";
        $this->redirect('admin/mouvement/index');
    }

    /**
     * Supprimer un mouvement (soft delete)
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/mouvement/index');
            return;
        }

        // Récupérer les anciennes valeurs pour l'audit
        $old = $this->mouvementModel->getById($id);
        if (!$old) {
            $this->redirect('admin/mouvement/index');
            return;
        }

        // 1. Soft delete
        $this->mouvementModel->delete($id);

        // 2. Audit
        $audit = new AuditService();
        $audit->log('DELETE', 'mouvement', $id, (array)$old, null);

        // 3. Redirection
        $_SESSION['success'] = "Le mouvement a été supprimé avec succès !";
        $this->redirect('admin/mouvement/index');
    }

    /**
     * Voir le détail d'un mouvement
     */
    public function showAction($id) {
        $mouvement = $this->mouvementModel->getWithOeuvre($id);
        if (!$mouvement) {
            $this->redirect('admin/mouvement/index');
            return;
        }
        $this->render('show', [
            'mouvement' => $mouvement,
            'pageTitle' => 'Détail du mouvement'
        ]);
    }

    /**
     * Recherche AJAX pour les mouvements
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $resultats = [];
        if (!empty($keyword)) {
            $resultats = $this->mouvementModel->search($keyword);
        }
        header('Content-Type: application/json');
        echo json_encode($resultats);
        exit;
    }

    /**
     * Export PDF de la liste des mouvements
     */
    public function exportPdfAction() {
        $mouvements = $this->mouvementModel->getAll();
        
        $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Mouvements</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px;">';
        $html .= '<thead>
                    <tr style="background:#1a2a3a; color:white;">
                        <th>ID</th>
                        <th>Œuvre</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Provenance</th>
                        <th>Destination</th>
                        <th>Responsable</th>
                    </tr>
                  </thead><tbody>';
        foreach ($mouvements as $mouvement) {
            $oeuvre = $this->oeuvreModel->getById($mouvement->oeuvre_id);
            $html .= '<tr>
                        <td>' . $mouvement->id . '</td>
                        <td>' . htmlspecialchars($oeuvre->titre ?? 'Non trouvée') . '</td>
                        <td>' . ucfirst($mouvement->type) . '</td>
                        <td>' . date('d/m/Y', strtotime($mouvement->date)) . '</td>
                        <td>' . htmlspecialchars($mouvement->provenance ?? '') . '</td>
                        <td>' . htmlspecialchars($mouvement->destination ?? '') . '</td>
                        <td>' . htmlspecialchars($mouvement->responsable ?? '') . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        
        $pdfService = new PdfExportService();
        $pdfService->generateFromHtml($html, 'liste_mouvements', 'landscape');
    }

    /**
     * Export Excel de la liste des mouvements
     */
    public function exportExcelAction() {
        $mouvements = $this->mouvementModel->getAll();
        
        $headers = ['ID', 'Œuvre', 'Type', 'Date', 'Provenance', 'Destination', 'Responsable'];
        $data = [];
        foreach ($mouvements as $mouvement) {
            $oeuvre = $this->oeuvreModel->getById($mouvement->oeuvre_id);
            $data[] = [
                $mouvement->id,
                $oeuvre->titre ?? 'Non trouvée',
                $mouvement->type,
                date('d/m/Y', strtotime($mouvement->date)),
                $mouvement->provenance ?? '',
                $mouvement->destination ?? '',
                $mouvement->responsable ?? ''
            ];
        }
        
        $excel = new ExcelExportService();
        $excel->export($data, $headers, 'mouvements_' . date('Y-m-d'), 'Liste des mouvements');
    }
}
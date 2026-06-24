<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\ExpositionModel;
use App\Models\OeuvreModel;
use App\Services\PdfExportService;
use App\Services\AuditService;
use App\Middlewares\AuthMiddleware;
use App\Services\ExcelExportService;
use App\Middlewares\SessionMiddleware;

class ExpositionController extends Controller {
    private $expositionModel;
    private $oeuvreModel;

    public function __construct() {
        SessionMiddleware::check();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        AuthMiddleware::requireAdminOrConservateur();
        $this->expositionModel = new ExpositionModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * Liste des expositions avec recherche et filtres
     */
    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $statut = $_GET['statut'] ?? '';
        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';
        
        $expositions = $this->expositionModel->getWithFilters([
            'keyword' => $keyword,
            'statut' => $statut,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ]);
        
        $statuts = ['prévue', 'en cours', 'terminée'];
        
        $this->render('index', [
            'expositions' => $expositions,
            'statuts' => $statuts,
            'keyword' => $keyword,
            'statut' => $statut,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'pageTitle' => 'Gestion des Expositions'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $oeuvres = $this->oeuvreModel->getAll();
        $this->render('create', [
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Ajouter une exposition'
        ]);
    }

    /**
     * Enregistrer une exposition
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/exposition/create');
            return;
        }

        $data = [
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date_debut' => !empty($_POST['date_debut']) ? $_POST['date_debut'] : null,
            'date_fin' => !empty($_POST['date_fin']) ? $_POST['date_fin'] : null,
            'lieu' => trim($_POST['lieu'] ?? ''),
            'statut' => $_POST['statut'] ?? 'prévue'
        ];

        if (empty($data['titre']) || empty($data['date_debut']) || empty($data['date_fin'])) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'Le titre, la date de début et la date de fin sont obligatoires',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter une exposition'
            ]);
            return;
        }

        if ($data['date_fin'] < $data['date_debut']) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'La date de fin doit être postérieure à la date de début',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter une exposition'
            ]);
            return;
        }

        // Gestion de l'upload de photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_DIR . 'expositions/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $data['photo'] = 'uploads/expositions/' . $filename;
            }
        }

        $id = $this->expositionModel->insert($data);
        
        if (isset($_POST['oeuvres']) && is_array($_POST['oeuvres'])) {
            foreach ($_POST['oeuvres'] as $oeuvreId) {
                $this->expositionModel->addOeuvre($id, $oeuvreId, date('Y-m-d'));
            }
        }

        $audit = new AuditService();
        $audit->log('INSERT', 'exposition', $id, null, $data);

        $_SESSION['success'] = "L'exposition a été ajoutée avec succès !";
        $this->redirect('admin/exposition/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->redirect('admin/exposition/index');
            return;
        }
        
        $oeuvresAssociees = $this->expositionModel->getOeuvres($id);
        $toutesOeuvres = $this->oeuvreModel->getAll();
        $oeuvresIds = array_map(function($o) { return $o->id; }, $oeuvresAssociees);
        
        $this->render('edit', [
            'exposition' => $exposition,
            'oeuvresAssociees' => $oeuvresAssociees,
            'toutesOeuvres' => $toutesOeuvres,
            'oeuvresIds' => $oeuvresIds,
            'pageTitle' => 'Modifier une exposition'
        ]);
    }

    /**
     * Mettre à jour une exposition
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/exposition/edit/' . $id);
            return;
        }

        $old = $this->expositionModel->getById($id);
        if (!$old) {
            $this->redirect('admin/exposition/index');
            return;
        }

        $data = [
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date_debut' => !empty($_POST['date_debut']) ? $_POST['date_debut'] : null,
            'date_fin' => !empty($_POST['date_fin']) ? $_POST['date_fin'] : null,
            'lieu' => trim($_POST['lieu'] ?? ''),
            'statut' => $_POST['statut'] ?? 'prévue'
        ];

        if (empty($data['titre']) || empty($data['date_debut']) || empty($data['date_fin'])) {
            $_SESSION['error'] = 'Le titre, la date de début et la date de fin sont obligatoires';
            $this->redirect('admin/exposition/edit/' . $id);
            return;
        }

        if ($data['date_fin'] < $data['date_debut']) {
            $_SESSION['error'] = 'La date de fin doit être postérieure à la date de début';
            $this->redirect('admin/exposition/edit/' . $id);
            return;
        }

        // Gestion de l'upload de photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_DIR . 'expositions/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $data['photo'] = 'uploads/expositions/' . $filename;
            }
        }

        $this->expositionModel->update($id, $data);
        
        $this->expositionModel->removeAllOeuvres($id);
        if (isset($_POST['oeuvres']) && is_array($_POST['oeuvres'])) {
            foreach ($_POST['oeuvres'] as $oeuvreId) {
                $this->expositionModel->addOeuvre($id, $oeuvreId, date('Y-m-d'));
            }
        }

        $audit = new AuditService();
        $audit->log('UPDATE', 'exposition', $id, (array)$old, $data);

        $_SESSION['success'] = "L'exposition a été modifiée avec succès !";
        $this->redirect('admin/exposition/index');
    }

    /**
     * Supprimer une exposition (soft delete)
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/exposition/index');
            return;
        }

        $old = $this->expositionModel->getById($id);
        if (!$old) {
            $this->redirect('admin/exposition/index');
            return;
        }

        // Soft delete
        $this->expositionModel->delete($id);

        $audit = new AuditService();
        $audit->log('DELETE', 'exposition', $id, (array)$old, null);

        $_SESSION['success'] = "L'exposition a été supprimée avec succès !";
        $this->redirect('admin/exposition/index');
    }

    /**
     * Voir le détail d'une exposition
     */
    public function showAction($id) {
        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->redirect('admin/exposition/index');
            return;
        }
        $oeuvres = $this->expositionModel->getOeuvres($id);
        $this->render('show', [
            'exposition' => $exposition,
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Détail de l\'exposition'
        ]);
    }

    /**
     * Recherche AJAX
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $resultats = [];
        if (!empty($keyword)) {
            $resultats = $this->expositionModel->search($keyword);
        }
        header('Content-Type: application/json');
        echo json_encode($resultats);
        exit;
    }

    /**
     * Export PDF
     */
    public function exportPdfAction() {
        $expositions = $this->expositionModel->getAll();
        
        $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Expositions</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px;">';
        $html .= '<thead>
                    <tr style="background:#1a2a3a; color:white;">
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Lieu</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                    </tr>
                  </thead><tbody>';
        foreach ($expositions as $exposition) {
            $html .= '<tr>
                        <td>' . $exposition->id . '</td>
                        <td>' . htmlspecialchars($exposition->titre) . '</td>
                        <td>' . htmlspecialchars($exposition->lieu ?? '') . '</td>
                        <td>' . date('d/m/Y', strtotime($exposition->date_debut)) . '</td>
                        <td>' . date('d/m/Y', strtotime($exposition->date_fin)) . '</td>
                        <td>' . $exposition->statut . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        
        $pdfService = new PdfExportService();
        $pdfService->generateFromHtml($html, 'liste_expositions', 'landscape');
    }

    /**
     * Export Excel
     */
    public function exportExcelAction() {
        $expositions = $this->expositionModel->getAll();
        
        $headers = ['ID', 'Titre', 'Lieu', 'Date début', 'Date fin', 'Statut'];
        $data = [];
        foreach ($expositions as $exposition) {
            $data[] = [
                $exposition->id,
                $exposition->titre,
                $exposition->lieu ?? '',
                date('d/m/Y', strtotime($exposition->date_debut)),
                date('d/m/Y', strtotime($exposition->date_fin)),
                $exposition->statut
            ];
        }
        
        $excel = new ExcelExportService();
        $excel->export($data, $headers, 'expositions_' . date('Y-m-d'), 'Liste des expositions');
    }
}
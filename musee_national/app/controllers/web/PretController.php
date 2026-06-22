<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\PretModel;
use App\Models\OeuvreModel;
use App\Services\PdfExportService;
use App\Middlewares\AuthMiddleware;
use App\Services\EmailService;
use App\Services\ExcelExportService;

class PretController extends Controller {
    private $pretModel;
    private $oeuvreModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
        AuthMiddleware::requireAdminOrConservateur();
        $this->pretModel = new PretModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * Liste des prêts avec recherche et filtres
     */
    public function indexAction() {
        // Récupérer les filtres
        $keyword = $_GET['keyword'] ?? '';
        $statut = $_GET['statut'] ?? '';
        $oeuvre_id = $_GET['oeuvre_id'] ?? '';
        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';
        
        // Construire la requête avec filtres
        $prets = $this->pretModel->getWithFilters([
            'keyword' => $keyword,
            'statut' => $statut,
            'oeuvre_id' => $oeuvre_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ]);
        
        // Récupérer les données pour les filtres
        $oeuvres = $this->oeuvreModel->getAll();
        $statuts = ['en cours', 'retourné'];
        
        // Vérifier les prêts en retard et envoyer des notifications
        $pretsRetard = $this->pretModel->getPretsRetard();
        if (!empty($pretsRetard)) {
            $emailService = new EmailService();
            $adminEmail = $_SESSION['user_email'] ?? 'admin@musee.com';
            
            foreach ($pretsRetard as $pret) {
                $oeuvre = $this->oeuvreModel->getById($pret->oeuvre_id);
                $emailService->notifyPretRetard($pret, $oeuvre, $adminEmail);
            }
        }

        $this->render('index', [
            'prets' => $prets,
            'oeuvres' => $oeuvres,
            'statuts' => $statuts,
            'keyword' => $keyword,
            'statut' => $statut,
            'oeuvre_id' => $oeuvre_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'pageTitle' => 'Gestion des Prêts'
        ]);
    }

    /**
     * Formulaire d'ajout
     */
    public function createAction() {
        $oeuvres = $this->oeuvreModel->getAll();
        $this->render('create', [
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Ajouter un prêt'
        ]);
    }

    /**
     * Enregistrer un prêt
     */
    public function storeAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pret/create');
        }

        $data = [
            'oeuvre_id' => $_POST['oeuvre_id'] ?? null,
            'emprunteur' => trim($_POST['emprunteur'] ?? ''),
            'date_debut' => !empty($_POST['date_debut']) ? $_POST['date_debut'] : null,
            'date_fin' => !empty($_POST['date_fin']) ? $_POST['date_fin'] : null,
            'statut' => $_POST['statut'] ?? 'en cours',
            'observations' => trim($_POST['observations'] ?? '')
        ];

        // Validation
        if (empty($data['oeuvre_id']) || empty($data['emprunteur']) || empty($data['date_debut']) || empty($data['date_fin'])) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'Tous les champs sont obligatoires',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter un prêt'
            ]);
            return;
        }

        // Vérifier que la date de fin est après la date de début
        if ($data['date_fin'] < $data['date_debut']) {
            $oeuvres = $this->oeuvreModel->getAll();
            $this->render('create', [
                'error' => 'La date de fin doit être postérieure à la date de début',
                'old' => $data,
                'oeuvres' => $oeuvres,
                'pageTitle' => 'Ajouter un prêt'
            ]);
            return;
        }

        // Vérifier si l'œuvre est déjà en prêt
        if ($data['statut'] === 'en cours') {
            $pretExistant = $this->pretModel->findActivePretByOeuvre($data['oeuvre_id']);
            if ($pretExistant) {
                $oeuvres = $this->oeuvreModel->getAll();
                $this->render('create', [
                    'error' => 'Cette œuvre est déjà en prêt (ID: ' . $pretExistant->id . ')',
                    'old' => $data,
                    'oeuvres' => $oeuvres,
                    'pageTitle' => 'Ajouter un prêt'
                ]);
                return;
            }
        }

        $this->pretModel->insert($data);
        $_SESSION['success'] = "Le prêt a été ajouté avec succès !";
        $this->redirect('pret/index');
    }

    /**
     * Formulaire d'édition
     */
    public function editAction($id) {
        $pret = $this->pretModel->getById($id);
        if (!$pret) {
            $this->redirect('pret/index');
        }
        $oeuvres = $this->oeuvreModel->getAll();
        $this->render('edit', [
            'pret' => $pret,
            'oeuvres' => $oeuvres,
            'pageTitle' => 'Modifier un prêt'
        ]);
    }

    /**
     * Mettre à jour un prêt
     */
    public function updateAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pret/edit/' . $id);
        }

        $data = [
            'oeuvre_id' => $_POST['oeuvre_id'] ?? null,
            'emprunteur' => trim($_POST['emprunteur'] ?? ''),
            'date_debut' => !empty($_POST['date_debut']) ? $_POST['date_debut'] : null,
            'date_fin' => !empty($_POST['date_fin']) ? $_POST['date_fin'] : null,
            'statut' => $_POST['statut'] ?? 'en cours',
            'observations' => trim($_POST['observations'] ?? '')
        ];

        if (empty($data['oeuvre_id']) || empty($data['emprunteur']) || empty($data['date_debut']) || empty($data['date_fin'])) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            $this->redirect('pret/edit/' . $id);
            return;
        }

        if ($data['date_fin'] < $data['date_debut']) {
            $_SESSION['error'] = 'La date de fin doit être postérieure à la date de début';
            $this->redirect('pret/edit/' . $id);
            return;
        }

        // Vérifier si l'œuvre est déjà en prêt (sauf pour ce prêt)
        if ($data['statut'] === 'en cours') {
            $pretExistant = $this->pretModel->findActivePretByOeuvre($data['oeuvre_id']);
            if ($pretExistant && $pretExistant->id != $id) {
                $_SESSION['error'] = 'Cette œuvre est déjà en prêt (ID: ' . $pretExistant->id . ')';
                $this->redirect('pret/edit/' . $id);
                return;
            }
        }

        $this->pretModel->update($id, $data);
        $_SESSION['success'] = "Le prêt a été modifié avec succès !";
        $this->redirect('pret/index');
    }

    /**
     * Supprimer un prêt
     */
    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->pretModel->delete($id);
            $_SESSION['success'] = "Le prêt a été supprimé avec succès !";
        }
        $this->redirect('pret/index');
    }

    /**
     * Voir le détail d'un prêt
     */
    public function showAction($id) {
        $pret = $this->pretModel->getWithOeuvre($id);
        if (!$pret) {
            $this->redirect('pret/index');
        }
        $this->render('show', [
            'pret' => $pret,
            'pageTitle' => 'Détail du prêt'
        ]);
    }

    /**
     * Terminer un prêt (le marquer comme retourné)
     */
    public function returnAction($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->pretModel->terminerPret($id);
            $_SESSION['success'] = "Le prêt a été marqué comme retourné !";
        }
        $this->redirect('pret/index');
    }

    /**
     * Recherche AJAX pour les prêts
     */
    public function searchAction() {
        $keyword = $_GET['keyword'] ?? '';
        $resultats = [];
        if (!empty($keyword)) {
            $resultats = $this->pretModel->search($keyword);
        }
        header('Content-Type: application/json');
        echo json_encode($resultats);
        exit;
    }

    /**
     * Export PDF de la liste des prêts
     */
    public function exportPdfAction() {
        $prets = $this->pretModel->getAll();
        
        // Générer le HTML pour le PDF
        $html = '<h1 style="text-align:center; color:#1a2a3a;">Liste des Prêts</h1>';
        $html .= '<p style="text-align:center; color:#888;">Généré le ' . date('d/m/Y H:i') . '</p>';
        $html .= '<hr>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:12px;">';
        $html .= '<thead>
                    <tr style="background:#1a2a3a; color:white;">
                        <th>ID</th>
                        <th>Œuvre</th>
                        <th>Emprunteur</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                    </tr>
                  </thead><tbody>';
        foreach ($prets as $pret) {
            // Récupérer le titre de l'œuvre
            $oeuvre = $this->oeuvreModel->getById($pret->oeuvre_id);
            $html .= '<tr>
                        <td>' . $pret->id . '</td>
                        <td>' . htmlspecialchars($oeuvre->titre ?? 'Non trouvée') . '</td>
                        <td>' . htmlspecialchars($pret->emprunteur) . '</td>
                        <td>' . date('d/m/Y', strtotime($pret->date_debut)) . '</td>
                        <td>' . date('d/m/Y', strtotime($pret->date_fin)) . '</td>
                        <td>' . $pret->statut . '</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        
        $pdfService = new PdfExportService();
        $pdfService->generateFromHtml($html, 'liste_prets', 'landscape');
    }

 

public function exportExcelAction() {
    $prets = $this->pretModel->getAll();
    
    $headers = ['ID', 'Œuvre', 'Emprunteur', 'Date début', 'Date fin', 'Statut', 'Observations'];
    $data = [];
    foreach ($prets as $pret) {
        $oeuvre = $this->oeuvreModel->getById($pret->oeuvre_id);
        $data[] = [
            $pret->id,
            $oeuvre->titre ?? 'Non trouvée',
            $pret->emprunteur,
            date('d/m/Y', strtotime($pret->date_debut)),
            date('d/m/Y', strtotime($pret->date_fin)),
            $pret->statut,
            $pret->observations ?? ''
        ];
    }
    
    $excel = new ExcelExportService();
    $excel->export($data, $headers, 'prets_' . date('Y-m-d'), 'Liste des prêts');
}
}
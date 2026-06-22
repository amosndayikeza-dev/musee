<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\PretModel;
use App\Models\OeuvreModel;
use App\Middlewares\ApiAuthMiddleware;

class PretApiController extends Controller {
    
    private $pretModel;
    private $oeuvreModel;

    public function __construct() {
        $this->pretModel = new PretModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * GET /api/pret
     * Liste des prêts avec pagination et filtres
     */
    public function indexAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $keyword = $_GET['keyword'] ?? '';
        $statut = $_GET['statut'] ?? '';
        $oeuvre_id = $_GET['oeuvre_id'] ?? '';

        $filters = [
            'keyword' => $keyword,
            'statut' => $statut,
            'oeuvre_id' => $oeuvre_id
        ];

        $prets = $this->pretModel->getWithFiltersPaginated($filters, $limit, $offset);
        $total = $this->pretModel->countWithFilters($filters);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $prets,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'pages' => ceil($total / $limit)
            ]
        ], 200);
    }

    /**
     * GET /api/pret/retard
     * Prêts en retard
     */
    public function retardAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();
        
        $prets = $this->pretModel->getPretsRetard();
        
        $this->jsonResponse([
            'status' => 'success',
            'data' => $prets,
            'count' => count($prets)
        ], 200);
    }

    /**
     * GET /api/pret/{id}
     * Détail d'un prêt
     */
    public function showAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $pret = $this->pretModel->getWithOeuvre($id);
        if (!$pret) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Prêt non trouvé'], 404);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => $pret
        ], 200);
    }

    /**
     * POST /api/pret
     * Créer un prêt (Admin/Conservateur)
     */
    public function createAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['oeuvre_id']) || empty($input['emprunteur']) || empty($input['date_debut']) || empty($input['date_fin'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'L\'œuvre, l\'emprunteur, la date de début et la date de fin sont obligatoires'
            ], 400);
            return;
        }

        if ($input['date_fin'] < $input['date_debut']) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'La date de fin doit être postérieure à la date de début'
            ], 400);
            return;
        }

        // Vérifier que l'œuvre existe
        $oeuvre = $this->oeuvreModel->getById($input['oeuvre_id']);
        if (!$oeuvre) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Œuvre non trouvée'], 404);
            return;
        }

        // Vérifier si l'œuvre est déjà en prêt
        $pretExistant = $this->pretModel->findActivePretByOeuvre($input['oeuvre_id']);
        if ($pretExistant) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Cette œuvre est déjà en prêt (ID: ' . $pretExistant->id . ')'
            ], 409);
            return;
        }

        $data = [
            'oeuvre_id' => $input['oeuvre_id'],
            'emprunteur' => trim($input['emprunteur']),
            'date_debut' => $input['date_debut'],
            'date_fin' => $input['date_fin'],
            'statut' => $input['statut'] ?? 'en cours',
            'observations' => trim($input['observations'] ?? '')
        ];

        $id = $this->pretModel->insert($data);
        $pret = $this->pretModel->getWithOeuvre($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Prêt créé avec succès',
            'data' => $pret
        ], 201);
    }

    /**
     * PUT /api/pret/{id}
     * Modifier un prêt (Admin/Conservateur)
     */
    public function updateAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $pret = $this->pretModel->getById($id);
        if (!$pret) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Prêt non trouvé'], 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [];
        if (isset($input['oeuvre_id'])) $data['oeuvre_id'] = $input['oeuvre_id'];
        if (isset($input['emprunteur'])) $data['emprunteur'] = trim($input['emprunteur']);
        if (isset($input['date_debut'])) $data['date_debut'] = $input['date_debut'];
        if (isset($input['date_fin'])) $data['date_fin'] = $input['date_fin'];
        if (isset($input['statut'])) $data['statut'] = $input['statut'];
        if (isset($input['observations'])) $data['observations'] = trim($input['observations']);

        if (isset($data['date_debut']) && isset($data['date_fin']) && $data['date_fin'] < $data['date_debut']) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'La date de fin doit être postérieure à la date de début'
            ], 400);
            return;
        }

        $this->pretModel->update($id, $data);
        $pret = $this->pretModel->getWithOeuvre($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Prêt mis à jour avec succès',
            'data' => $pret
        ], 200);
    }

    /**
     * DELETE /api/pret/{id}
     * Supprimer un prêt (Admin uniquement)
     */
    public function deleteAction($id) {
        ApiAuthMiddleware::requireAdmin();

        $pret = $this->pretModel->getById($id);
        if (!$pret) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Prêt non trouvé'], 404);
            return;
        }

        $this->pretModel->delete($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Prêt supprimé avec succès'
        ], 200);
    }

    /**
     * POST /api/pret/{id}/return
     * Marquer un prêt comme retourné (Admin/Conservateur)
     */
    public function returnAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $pret = $this->pretModel->getById($id);
        if (!$pret) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Prêt non trouvé'], 404);
            return;
        }

        if ($pret->statut === 'retourné') {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Ce prêt est déjà retourné'
            ], 400);
            return;
        }

        $this->pretModel->terminerPret($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Prêt marqué comme retourné avec succès'
        ], 200);
    }
}
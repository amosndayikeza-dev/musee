<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\RestaurationModel;
use App\Models\OeuvreModel;
use App\Middlewares\ApiAuthMiddleware;

class RestaurationApiController extends Controller {
    
    private $restaurationModel;
    private $oeuvreModel;

    public function __construct() {
        $this->restaurationModel = new RestaurationModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * GET /api/restauration
     * Liste des restaurations avec pagination et filtres
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

        $restaurations = $this->restaurationModel->getWithFiltersPaginated($filters, $limit, $offset);
        $total = $this->restaurationModel->countWithFilters($filters);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $restaurations,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'pages' => ceil($total / $limit)
            ]
        ], 200);
    }

    /**
     * GET /api/restauration/{id}
     * Détail d'une restauration
     */
    public function showAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $restauration = $this->restaurationModel->getWithOeuvre($id);
        if (!$restauration) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restauration non trouvée'], 404);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => $restauration
        ], 200);
    }

    /**
     * POST /api/restauration
     * Créer une restauration (Admin/Conservateur)
     */
    public function createAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['oeuvre_id']) || empty($input['date_debut'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'L\'œuvre et la date de début sont obligatoires'
            ], 400);
            return;
        }

        // Vérifier que l'œuvre existe
        $oeuvre = $this->oeuvreModel->getById($input['oeuvre_id']);
        if (!$oeuvre) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Œuvre non trouvée'], 404);
            return;
        }

        // Vérifier si l'œuvre est déjà en restauration
        $restaurationExistante = $this->restaurationModel->findActiveRestaurationByOeuvre($input['oeuvre_id']);
        if ($restaurationExistante) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Cette œuvre est déjà en restauration (ID: ' . $restaurationExistante->id . ')'
            ], 409);
            return;
        }

        $data = [
            'oeuvre_id' => $input['oeuvre_id'],
            'date_debut' => $input['date_debut'],
            'date_fin' => $input['date_fin'] ?? null,
            'responsable' => trim($input['responsable'] ?? ''),
            'description' => trim($input['description'] ?? ''),
            'cout' => $input['cout'] ?? null
        ];

        $id = $this->restaurationModel->insert($data);
        $restauration = $this->restaurationModel->getWithOeuvre($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restauration créée avec succès',
            'data' => $restauration
        ], 201);
    }

    /**
     * PUT /api/restauration/{id}
     * Modifier une restauration (Admin/Conservateur)
     */
    public function updateAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $restauration = $this->restaurationModel->getById($id);
        if (!$restauration) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restauration non trouvée'], 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [];
        if (isset($input['oeuvre_id'])) $data['oeuvre_id'] = $input['oeuvre_id'];
        if (isset($input['date_debut'])) $data['date_debut'] = $input['date_debut'];
        if (isset($input['date_fin'])) $data['date_fin'] = $input['date_fin'];
        if (isset($input['responsable'])) $data['responsable'] = trim($input['responsable']);
        if (isset($input['description'])) $data['description'] = trim($input['description']);
        if (isset($input['cout'])) $data['cout'] = $input['cout'];

        $this->restaurationModel->update($id, $data);
        $restauration = $this->restaurationModel->getWithOeuvre($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restauration mise à jour avec succès',
            'data' => $restauration
        ], 200);
    }

    /**
     * DELETE /api/restauration/{id}
     * Supprimer une restauration (Admin uniquement)
     */
    public function deleteAction($id) {
        ApiAuthMiddleware::requireAdmin();

        $restauration = $this->restaurationModel->getById($id);
        if (!$restauration) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restauration non trouvée'], 404);
            return;
        }

        $this->restaurationModel->delete($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restauration supprimée avec succès'
        ], 200);
    }

    /**
     * POST /api/restauration/{id}/complete
     * Marquer une restauration comme terminée (Admin/Conservateur)
     */
    public function completeAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $restauration = $this->restaurationModel->getById($id);
        if (!$restauration) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Restauration non trouvée'], 404);
            return;
        }

        if ($restauration->date_fin && strtotime($restauration->date_fin) <= time()) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Cette restauration est déjà terminée'
            ], 400);
            return;
        }

        $this->restaurationModel->terminerRestauration($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Restauration marquée comme terminée avec succès'
        ], 200);
    }
}
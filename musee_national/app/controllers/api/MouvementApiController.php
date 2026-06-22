<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\MouvementModel;
use App\Models\OeuvreModel;
use App\Middlewares\ApiAuthMiddleware;

class MouvementApiController extends Controller {
    
    private $mouvementModel;
    private $oeuvreModel;

    public function __construct() {
        $this->mouvementModel = new MouvementModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * GET /api/mouvement
     * Liste des mouvements avec pagination et filtres
     */
    public function indexAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $keyword = $_GET['keyword'] ?? '';
        $type = $_GET['type'] ?? '';
        $oeuvre_id = $_GET['oeuvre_id'] ?? '';

        $filters = [
            'keyword' => $keyword,
            'type' => $type,
            'oeuvre_id' => $oeuvre_id
        ];

        $mouvements = $this->mouvementModel->getWithFiltersPaginated($filters, $limit, $offset);
        $total = $this->mouvementModel->countWithFilters($filters);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $mouvements,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'pages' => ceil($total / $limit)
            ]
        ], 200);
    }

    /**
     * GET /api/mouvement/{id}
     * Détail d'un mouvement
     */
    public function showAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $mouvement = $this->mouvementModel->getWithOeuvre($id);
        if (!$mouvement) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Mouvement non trouvé'], 404);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => $mouvement
        ], 200);
    }

    /**
     * POST /api/mouvement
     * Créer un mouvement (Admin/Conservateur)
     */
    public function createAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['oeuvre_id']) || empty($input['date'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'L\'œuvre et la date sont obligatoires'
            ], 400);
            return;
        }

        // Vérifier que l'œuvre existe
        $oeuvre = $this->oeuvreModel->getById($input['oeuvre_id']);
        if (!$oeuvre) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Œuvre non trouvée'], 404);
            return;
        }

        // Validation spécifique : pour une sortie, la destination est obligatoire
        if ($input['type'] === 'sortie' && empty($input['destination'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'La destination est obligatoire pour une sortie'
            ], 400);
            return;
        }

        // Validation spécifique : pour une entrée, la provenance est obligatoire
        if ($input['type'] === 'entrée' && empty($input['provenance'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'La provenance est obligatoire pour une entrée'
            ], 400);
            return;
        }

        $data = [
            'oeuvre_id' => $input['oeuvre_id'],
            'type' => $input['type'] ?? 'entrée',
            'date' => $input['date'],
            'provenance' => trim($input['provenance'] ?? ''),
            'destination' => trim($input['destination'] ?? ''),
            'responsable' => trim($input['responsable'] ?? '')
        ];

        $id = $this->mouvementModel->insert($data);
        $mouvement = $this->mouvementModel->getWithOeuvre($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Mouvement créé avec succès',
            'data' => $mouvement
        ], 201);
    }

    /**
     * PUT /api/mouvement/{id}
     * Modifier un mouvement (Admin/Conservateur)
     */
    public function updateAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $mouvement = $this->mouvementModel->getById($id);
        if (!$mouvement) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Mouvement non trouvé'], 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [];
        if (isset($input['oeuvre_id'])) $data['oeuvre_id'] = $input['oeuvre_id'];
        if (isset($input['type'])) $data['type'] = $input['type'];
        if (isset($input['date'])) $data['date'] = $input['date'];
        if (isset($input['provenance'])) $data['provenance'] = trim($input['provenance']);
        if (isset($input['destination'])) $data['destination'] = trim($input['destination']);
        if (isset($input['responsable'])) $data['responsable'] = trim($input['responsable']);

        // Validation si le type change
        if (isset($data['type']) && $data['type'] === 'sortie' && empty($data['destination'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'La destination est obligatoire pour une sortie'
            ], 400);
            return;
        }
        if (isset($data['type']) && $data['type'] === 'entrée' && empty($data['provenance'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'La provenance est obligatoire pour une entrée'
            ], 400);
            return;
        }

        $this->mouvementModel->update($id, $data);
        $mouvement = $this->mouvementModel->getWithOeuvre($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Mouvement mis à jour avec succès',
            'data' => $mouvement
        ], 200);
    }

    /**
     * DELETE /api/mouvement/{id}
     * Supprimer un mouvement (Admin uniquement)
     */
    public function deleteAction($id) {
        ApiAuthMiddleware::requireAdmin();

        $mouvement = $this->mouvementModel->getById($id);
        if (!$mouvement) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Mouvement non trouvé'], 404);
            return;
        }

        $this->mouvementModel->delete($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Mouvement supprimé avec succès'
        ], 200);
    }
}
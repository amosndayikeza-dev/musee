<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\ExpositionModel;
use App\Models\OeuvreModel;
use App\Middlewares\ApiAuthMiddleware;

class ExpositionApiController extends Controller {
    
    private $expositionModel;
    private $oeuvreModel;

    public function __construct() {
        $this->expositionModel = new ExpositionModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    /**
     * GET /api/exposition
     * Liste des expositions avec pagination et filtres
     */
    public function indexAction() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $keyword = $_GET['keyword'] ?? '';
        $statut = $_GET['statut'] ?? '';
        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';

        $filters = [
            'keyword' => $keyword,
            'statut' => $statut,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ];

        $expositions = $this->expositionModel->getWithFiltersPaginated($filters, $limit, $offset);
        $total = $this->expositionModel->countWithFilters($filters);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $expositions,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'pages' => ceil($total / $limit)
            ]
        ], 200);
    }

    /**
     * GET /api/exposition/{id}
     * Détail d'une exposition avec ses œuvres
     */
    public function showAction($id) {
        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Exposition non trouvée'], 404);
            return;
        }

        $oeuvres = $this->expositionModel->getOeuvres($id);

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'exposition' => $exposition,
                'oeuvres' => $oeuvres
            ]
        ], 200);
    }

    /**
     * GET /api/exposition/current
     * Expositions en cours
     */
    public function currentAction() {
        $expositions = $this->expositionModel->getExpositionsEnCours();
        
        $this->jsonResponse([
            'status' => 'success',
            'data' => $expositions,
            'count' => count($expositions)
        ], 200);
    }

    /**
     * GET /api/exposition/upcoming
     * Expositions à venir
     */
    public function upcomingAction() {
        $expositions = $this->expositionModel->getExpositionsAvenir();
        
        $this->jsonResponse([
            'status' => 'success',
            'data' => $expositions,
            'count' => count($expositions)
        ], 200);
    }

    /**
     * POST /api/exposition
     * Créer une exposition (Admin/Conservateur)
     */
    public function createAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['titre']) || empty($input['date_debut']) || empty($input['date_fin'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Le titre, la date de début et la date de fin sont obligatoires'
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

        $data = [
            'titre' => trim($input['titre']),
            'description' => trim($input['description'] ?? ''),
            'date_debut' => $input['date_debut'],
            'date_fin' => $input['date_fin'],
            'lieu' => trim($input['lieu'] ?? ''),
            'statut' => $input['statut'] ?? 'prévue'
        ];

        $id = $this->expositionModel->insert($data);
        
        // Ajouter les œuvres si spécifiées
        if (isset($input['oeuvres']) && is_array($input['oeuvres'])) {
            foreach ($input['oeuvres'] as $oeuvreId) {
                $this->expositionModel->addOeuvre($id, $oeuvreId, date('Y-m-d'));
            }
        }

        $exposition = $this->expositionModel->getById($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Exposition créée avec succès',
            'data' => $exposition
        ], 201);
    }

    /**
     * PUT /api/exposition/{id}
     * Modifier une exposition (Admin/Conservateur)
     */
    public function updateAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Exposition non trouvée'], 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [];
        if (isset($input['titre'])) $data['titre'] = trim($input['titre']);
        if (isset($input['description'])) $data['description'] = trim($input['description']);
        if (isset($input['date_debut'])) $data['date_debut'] = $input['date_debut'];
        if (isset($input['date_fin'])) $data['date_fin'] = $input['date_fin'];
        if (isset($input['lieu'])) $data['lieu'] = trim($input['lieu']);
        if (isset($input['statut'])) $data['statut'] = $input['statut'];

        if (isset($data['date_debut']) && isset($data['date_fin']) && $data['date_fin'] < $data['date_debut']) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'La date de fin doit être postérieure à la date de début'
            ], 400);
            return;
        }

        $this->expositionModel->update($id, $data);
        
        // Mettre à jour les œuvres si spécifiées
        if (isset($input['oeuvres']) && is_array($input['oeuvres'])) {
            // Supprimer toutes les associations existantes
            $this->expositionModel->removeAllOeuvres($id);
            // Ajouter les nouvelles
            foreach ($input['oeuvres'] as $oeuvreId) {
                $this->expositionModel->addOeuvre($id, $oeuvreId, date('Y-m-d'));
            }
        }

        $exposition = $this->expositionModel->getById($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Exposition mise à jour avec succès',
            'data' => $exposition
        ], 200);
    }

    /**
     * DELETE /api/exposition/{id}
     * Supprimer une exposition (Admin uniquement)
     */
    public function deleteAction($id) {
        ApiAuthMiddleware::requireAdmin();

        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Exposition non trouvée'], 404);
            return;
        }

        $this->expositionModel->delete($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Exposition supprimée avec succès'
        ], 200);
    }

    /**
     * POST /api/exposition/{id}/oeuvre
     * Ajouter une œuvre à une exposition (Admin/Conservateur)
     */
    public function addOeuvreAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Exposition non trouvée'], 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['oeuvre_id'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'ID de l\'œuvre requis'
            ], 400);
            return;
        }

        // Vérifier que l'œuvre existe
        $oeuvre = $this->oeuvreModel->getById($input['oeuvre_id']);
        if (!$oeuvre) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Œuvre non trouvée'], 404);
            return;
        }

        $this->expositionModel->addOeuvre(
            $id,
            $input['oeuvre_id'],
            $input['date_arrivee'] ?? date('Y-m-d'),
            $input['date_depart'] ?? null
        );

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Œuvre ajoutée à l\'exposition avec succès'
        ], 200);
    }

    /**
     * DELETE /api/exposition/{id}/oeuvre/{oeuvreId}
     * Retirer une œuvre d'une exposition (Admin/Conservateur)
     */
    public function removeOeuvreAction($id, $oeuvreId) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Exposition non trouvée'], 404);
            return;
        }

        $this->expositionModel->removeOeuvre($id, $oeuvreId);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Œuvre retirée de l\'exposition avec succès'
        ], 200);
    }
}
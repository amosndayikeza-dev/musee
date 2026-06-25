<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\OeuvreModel;
use App\Models\AuteurModel;
use App\Models\CategorieModel;
use App\Middlewares\ApiAuthMiddleware;

class OeuvreApiController extends Controller {
    
    private $oeuvreModel;
    private $auteurModel;
    private $categorieModel;

    public function __construct() {
        $this->oeuvreModel = new OeuvreModel();
        $this->auteurModel = new AuteurModel();
        $this->categorieModel = new CategorieModel();
    }

    /**
     * GET /api/oeuvre
     * Liste des œuvres avec pagination et filtres
     */
    /**
 * GET /api/oeuvre
 * Liste des œuvres avec pagination et filtres
 */
public function indexAction() {
    // Récupérer les paramètres
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $keyword = $_GET['keyword'] ?? '';
    $statut = $_GET['statut'] ?? '';
    $auteur_id = $_GET['auteur_id'] ?? '';
    $categorie_id = $_GET['categorie_id'] ?? '';

    $filters = [
        'keyword' => $keyword,
        'statut' => $statut,
        'auteur_id' => $auteur_id,
        'categorie_id' => $categorie_id
    ];

    // Utiliser la méthode avec pagination
    $oeuvres = $this->oeuvreModel->getWithFiltersPaginated($filters, $limit, $offset);
    $total = $this->oeuvreModel->countWithFilters($filters);

    $this->jsonResponse([
        'status' => 'success',
        'data' => $oeuvres,
        'pagination' => [
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'pages' => ceil($total / $limit)
        ]
    ], 200);
}


    /**
     * GET /api/oeuvre/{id}
     * Détail d'une œuvre
     */
    public function showAction($id) {
        $oeuvre = $this->oeuvreModel->getByIdWithDetails($id);
        
        if (!$oeuvre) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Œuvre non trouvée'
            ], 404);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => $oeuvre
        ], 200);
    }

    /**
     * GET /api/oeuvre/search
     * Recherche d'œuvres
     */
    public function searchAction() {
        $keyword = $_GET['q'] ?? '';
        
        if (empty($keyword)) {
            $this->jsonResponse([
                'status' => 'success',
                'data' => [],
                'message' => 'Aucun mot-clé fourni'
            ], 200);
            return;
        }

        $results = $this->oeuvreModel->search($keyword);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ], 200);
    }

    /**
     * POST /api/oeuvre
     * Créer une œuvre (Admin/Conservateur)
     */
    public function createAction() {
        // Vérifier l'authentification et les droits
        $payload = ApiAuthMiddleware::requireAdminOrConservateur();

        // Récupérer les données
        $input = json_decode(file_get_contents('php://input'), true);

        // Validation
        if (empty($input['titre'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Le titre est obligatoire'
            ], 400);
            return;
        }

        // Préparer les données
        $data = [
            'titre' => trim($input['titre']),
            'description' => trim($input['description'] ?? ''),
            'date_creation' => $input['date_creation'] ?? null,
            'technique' => trim($input['technique'] ?? ''),
            'dimensions' => trim($input['dimensions'] ?? ''),
            'auteur_id' => $input['auteur_id'] ?? null,
            'categorie_id' => $input['categorie_id'] ?? null,
            'statut' => $input['statut'] ?? 'en réserve'
        ];

        // Vérifier si l'auteur existe
        if (!empty($data['auteur_id'])) {
            $auteur = $this->auteurModel->getById($data['auteur_id']);
            if (!$auteur) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Auteur non trouvé'
                ], 404);
                return;
            }
        }

        // Vérifier si la catégorie existe
        if (!empty($data['categorie_id'])) {
            $categorie = $this->categorieModel->getById($data['categorie_id']);
            if (!$categorie) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Catégorie non trouvée'
                ], 404);
                return;
            }
        }

        // Insérer l'œuvre
        $id = $this->oeuvreModel->insert($data);

        // Récupérer l'œuvre créée
        $oeuvre = $this->oeuvreModel->getByIdWithDetails($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Œuvre créée avec succès',
            'data' => $oeuvre
        ], 201);
    }

    /**
     * PUT /api/oeuvre/{id}
     * Modifier une œuvre (Admin/Conservateur)
     */
    public function updateAction($id) {
        // Vérifier l'authentification et les droits
        $payload = ApiAuthMiddleware::requireAdminOrConservateur();

        // Vérifier si l'œuvre existe
        $oeuvre = $this->oeuvreModel->getById($id);
        if (!$oeuvre) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Œuvre non trouvée'
            ], 404);
            return;
        }

        // Récupérer les données
        $input = json_decode(file_get_contents('php://input'), true);

        // Préparer les données (seulement les champs fournis)
        $data = [];
        if (isset($input['titre'])) $data['titre'] = trim($input['titre']);
        if (isset($input['description'])) $data['description'] = trim($input['description']);
        if (isset($input['date_creation'])) $data['date_creation'] = $input['date_creation'];
        if (isset($input['technique'])) $data['technique'] = trim($input['technique']);
        if (isset($input['dimensions'])) $data['dimensions'] = trim($input['dimensions']);
        if (isset($input['auteur_id'])) $data['auteur_id'] = $input['auteur_id'];
        if (isset($input['categorie_id'])) $data['categorie_id'] = $input['categorie_id'];
        if (isset($input['statut'])) $data['statut'] = $input['statut'];

        // Validation du titre si fourni
        if (isset($data['titre']) && empty($data['titre'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Le titre ne peut pas être vide'
            ], 400);
            return;
        }

        // Vérifier si l'auteur existe
        if (isset($data['auteur_id']) && !empty($data['auteur_id'])) {
            $auteur = $this->auteurModel->getById($data['auteur_id']);
            if (!$auteur) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Auteur non trouvé'
                ], 404);
                return;
            }
        }

        // Vérifier si la catégorie existe
        if (isset($data['categorie_id']) && !empty($data['categorie_id'])) {
            $categorie = $this->categorieModel->getById($data['categorie_id']);
            if (!$categorie) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Catégorie non trouvée'
                ], 404);
                return;
            }
        }

        // Mettre à jour l'œuvre
        $this->oeuvreModel->update($id, $data);

        // Récupérer l'œuvre mise à jour
        $oeuvre = $this->oeuvreModel->getByIdWithDetails($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Œuvre mise à jour avec succès',
            'data' => $oeuvre
        ], 200);
    }

    /**
     * DELETE /api/oeuvre/{id}
     * Supprimer une œuvre (Admin uniquement)
     */
    public function deleteAction($id) {
        // Vérifier l'authentification et les droits (Admin uniquement)
        $payload = ApiAuthMiddleware::requireAdmin();

        // Vérifier si l'œuvre existe
        $oeuvre = $this->oeuvreModel->getById($id);
        if (!$oeuvre) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Œuvre non trouvée'
            ], 404);
            return;
        }

        // Supprimer l'œuvre
        $this->oeuvreModel->delete($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Œuvre supprimée avec succès'
        ], 200);
    }

    /**
     * GET /api/oeuvre/statuts
     * Liste des statuts disponibles
     */
    public function statutsAction() {
        $statuts = ['exposé', 'en réserve', 'en restauration', 'en prêt'];
        
        $this->jsonResponse([
            'status' => 'success',
            'data' => $statuts
        ], 200);
    }
}
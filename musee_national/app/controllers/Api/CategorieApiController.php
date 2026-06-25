<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\CategorieModel;
use App\Middlewares\ApiAuthMiddleware;

class CategorieApiController extends Controller {
    
    private $categorieModel;

    public function __construct() {
        $this->categorieModel = new CategorieModel();
    }

    public function indexAction() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $keyword = $_GET['keyword'] ?? '';

        $filters = ['keyword' => $keyword];
        $categories = $this->categorieModel->getWithFiltersPaginated($filters, $limit, $offset);
        $total = $this->categorieModel->countWithFilters($filters);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $categories,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'pages' => ceil($total / $limit)
            ]
        ], 200);
    }

    public function showAction($id) {
        $categorie = $this->categorieModel->getById($id);
        if (!$categorie) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Catégorie non trouvée'], 404);
            return;
        }
        $this->jsonResponse(['status' => 'success', 'data' => $categorie], 200);
    }

    public function createAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['nom'])) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Le nom est obligatoire'], 400);
            return;
        }

        // Vérifier si la catégorie existe déjà
        $existing = $this->categorieModel->findByName($input['nom']);
        if ($existing) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Cette catégorie existe déjà'], 409);
            return;
        }

        $data = [
            'nom' => trim($input['nom']),
            'description' => trim($input['description'] ?? '')
        ];

        $id = $this->categorieModel->insert($data);
        $categorie = $this->categorieModel->getById($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Catégorie créée avec succès',
            'data' => $categorie
        ], 201);
    }

    public function updateAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();
        $categorie = $this->categorieModel->getById($id);
        if (!$categorie) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Catégorie non trouvée'], 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $data = [];
        if (isset($input['nom'])) $data['nom'] = trim($input['nom']);
        if (isset($input['description'])) $data['description'] = trim($input['description']);

        if (isset($data['nom']) && empty($data['nom'])) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Le nom ne peut pas être vide'], 400);
            return;
        }

        $this->categorieModel->update($id, $data);
        $categorie = $this->categorieModel->getById($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Catégorie mise à jour avec succès',
            'data' => $categorie
        ], 200);
    }

    public function deleteAction($id) {
        ApiAuthMiddleware::requireAdmin();
        $categorie = $this->categorieModel->getById($id);
        if (!$categorie) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Catégorie non trouvée'], 404);
            return;
        }

        $this->categorieModel->delete($id);
        $this->jsonResponse(['status' => 'success', 'message' => 'Catégorie supprimée avec succès'], 200);
    }
}
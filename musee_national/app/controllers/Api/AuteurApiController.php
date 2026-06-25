<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\AuteurModel;
use App\Middlewares\ApiAuthMiddleware;

class AuteurApiController extends Controller {
    
    private $auteurModel;

    public function __construct() {
        $this->auteurModel = new AuteurModel();
    }

    /**
     * GET /api/auteur
     * Liste des auteurs avec pagination et filtres
     */
    public function indexAction() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $keyword = $_GET['keyword'] ?? '';
        $nationalite = $_GET['nationalite'] ?? '';

        $filters = [
            'keyword' => $keyword,
            'nationalite' => $nationalite
        ];

        $auteurs = $this->auteurModel->getWithFiltersPaginated($filters, $limit, $offset);
        $total = $this->auteurModel->countWithFilters($filters);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $auteurs,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'pages' => ceil($total / $limit)
            ]
        ], 200);
    }

    /**
     * GET /api/auteur/{id}
     * Détail d'un auteur avec ses œuvres
     */
    public function showAction($id) {
        $auteur = $this->auteurModel->getById($id);
        
        if (!$auteur) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Auteur non trouvé'
            ], 404);
            return;
        }

        // Récupérer les œuvres de l'auteur
        $oeuvres = $this->auteurModel->getOeuvres($id);

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'auteur' => $auteur,
                'oeuvres' => $oeuvres
            ]
        ], 200);
    }

    /**
     * GET /api/auteur/search
     * Recherche d'auteurs
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

        $results = $this->auteurModel->search($keyword);

        $this->jsonResponse([
            'status' => 'success',
            'data' => $results,
            'count' => count($results)
        ], 200);
    }

    /**
     * GET /api/auteur/nationalites
     * Liste des nationalités distinctes
     */
    public function nationalitesAction() {
        $nationalites = $this->auteurModel->getAllNationalites();
        
        $this->jsonResponse([
            'status' => 'success',
            'data' => $nationalites
        ], 200);
    }

    /**
     * POST /api/auteur
     * Créer un auteur (Admin/Conservateur)
     */
    public function createAction() {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['nom'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Le nom est obligatoire'
            ], 400);
            return;
        }

        $data = [
            'nom' => trim($input['nom']),
            'prenom' => trim($input['prenom'] ?? ''),
            'biographie' => trim($input['biographie'] ?? ''),
            'date_naissance' => $input['date_naissance'] ?? null,
            'date_deces' => $input['date_deces'] ?? null,
            'nationalite' => trim($input['nationalite'] ?? '')
        ];

        // Générer le matricule automatiquement
        $data['matricule'] = $this->auteurModel->generateMatricule();

        $id = $this->auteurModel->insert($data);
        $auteur = $this->auteurModel->getById($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Auteur créé avec succès',
            'data' => $auteur
        ], 201);
    }

    /**
     * PUT /api/auteur/{id}
     * Modifier un auteur (Admin/Conservateur)
     */
    public function updateAction($id) {
        ApiAuthMiddleware::requireAdminOrConservateur();

        $auteur = $this->auteurModel->getById($id);
        if (!$auteur) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Auteur non trouvé'
            ], 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [];
        if (isset($input['nom'])) $data['nom'] = trim($input['nom']);
        if (isset($input['prenom'])) $data['prenom'] = trim($input['prenom']);
        if (isset($input['biographie'])) $data['biographie'] = trim($input['biographie']);
        if (isset($input['date_naissance'])) $data['date_naissance'] = $input['date_naissance'];
        if (isset($input['date_deces'])) $data['date_deces'] = $input['date_deces'];
        if (isset($input['nationalite'])) $data['nationalite'] = trim($input['nationalite']);

        if (isset($data['nom']) && empty($data['nom'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Le nom ne peut pas être vide'
            ], 400);
            return;
        }

        $this->auteurModel->update($id, $data);
        $auteur = $this->auteurModel->getById($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Auteur mis à jour avec succès',
            'data' => $auteur
        ], 200);
    }

    /**
     * DELETE /api/auteur/{id}
     * Supprimer un auteur (Admin uniquement)
     */
    public function deleteAction($id) {
        ApiAuthMiddleware::requireAdmin();

        $auteur = $this->auteurModel->getById($id);
        if (!$auteur) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Auteur non trouvé'
            ], 404);
            return;
        }

        // Vérifier si l'auteur a des œuvres associées
        $oeuvres = $this->auteurModel->getOeuvres($id);
        if (count($oeuvres) > 0) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Impossible de supprimer cet auteur car il a ' . count($oeuvres) . ' œuvre(s) associée(s)'
            ], 409);
            return;
        }

        $this->auteurModel->delete($id);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Auteur supprimé avec succès'
        ], 200);
    }
}
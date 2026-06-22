<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Services\StatistiqueService;
use App\Middlewares\ApiAuthMiddleware;

class StatApiController extends Controller {
    
    private $statService;

    public function __construct() {
        $this->statService = new StatistiqueService();
    }

    /**
     * GET /api/stat/dashboard
     * Retourne toutes les statistiques pour le dashboard
     */
    public function dashboardAction() {
        // Vérifier le token JWT (optionnel, mais on peut l'exiger pour les stats)
        // Si vous voulez sécuriser, décommentez la ligne suivante :
        // ApiAuthMiddleware::check();
        
        $stats = $this->statService->getDashboardStats();
        $this->jsonResponse($stats);
    }

    /**
     * GET /api/stat/oeuvres-par-categorie
     */
    public function oeuvresParCategorieAction() {
        $data = $this->statService->getOeuvresParCategorie();
        $this->jsonResponse($data);
    }

    /**
     * GET /api/stat/oeuvres-par-statut
     */
    public function oeuvresParStatutAction() {
        $data = $this->statService->getOeuvresParStatut();
        $this->jsonResponse($data);
    }

    /**
     * GET /api/stat/expositions-par-statut
     */
    public function expositionsParStatutAction() {
        $data = $this->statService->getExpositionsParStatut();
        $this->jsonResponse($data);
    }

    /**
     * GET /api/stat/prochaines-expositions
     */
    public function prochainesExpositionsAction() {
        $data = $this->statService->getProchainesExpositions();
        $this->jsonResponse($data);
    }
}
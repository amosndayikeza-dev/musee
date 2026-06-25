<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\OeuvreModel;
use App\Models\AuteurModel;
use App\Models\CategorieModel;
use App\Models\ExpositionModel;
use App\Models\PretModel;
use App\Models\RestaurationModel;
use App\Models\MouvementModel;
use App\Middlewares\SessionMiddleware;

class AdminController extends Controller {
    
   public function __construct() {
        // Vérifier la session (timeout)
        SessionMiddleware::check();
        
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        
        // Vérifier que l'utilisateur a les droits admin ou conservateur
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'conservateur') {
            $this->redirect('home/index');
            exit;
        }
   }
    /**
     * Récupère toutes les alertes pour le dashboard
     */
    private function getAlerts() {
        $alerts = [];
        
        // 1. Prêts en retard
        $pretModel = new PretModel();
        $pretsRetard = $pretModel->countRetard();
        if ($pretsRetard > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fa-exclamation-triangle',
                'message' => "⚠️ $pretsRetard prêt(s) en retard ! Veuillez vérifier."
            ];
        }
        
        // 2. Restaurations en cours
        $restaurationModel = new RestaurationModel();
        $restaurationsEnCours = $restaurationModel->countEnCours();
        if ($restaurationsEnCours > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fa-tools',
                'message' => "🔧 $restaurationsEnCours restauration(s) en cours."
            ];
        }
        
        // 3. Expositions à venir
        $expositionModel = new ExpositionModel();
        $expoAvenir = $expositionModel->countAvenir();
        if ($expoAvenir > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fa-calendar-alt',
                'message' => "📅 $expoAvenir exposition(s) à venir prochainement."
            ];
        }
        
        // 4. Œuvres sans auteur
        $oeuvreModel = new OeuvreModel();
        $oeuvresSansAuteur = $oeuvreModel->countSansAuteur();
        if ($oeuvresSansAuteur > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fa-user-slash',
                'message' => "👤 $oeuvresSansAuteur œuvre(s) sans auteur assigné."
            ];
        }
        
        // 5. Œuvres sans catégorie
        $oeuvresSansCategorie = $oeuvreModel->countSansCategorie();
        if ($oeuvresSansCategorie > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fa-tag',
                'message' => "🏷️ $oeuvresSansCategorie œuvre(s) sans catégorie."
            ];
        }
        
        return $alerts;
    }
    
    public function dashboardAction() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            return;
        }
        
        // Vérifier si l'utilisateur a les droits
        if (!in_array($_SESSION['role'], ['admin', 'conservateur'])) {
            $this->redirect('home/index');
            return;
        }

        // Instancier les modèles
        $oeuvreModel = new OeuvreModel();
        $expositionModel = new ExpositionModel();
        $pretModel = new PretModel();
        $restaurationModel = new RestaurationModel();
        $mouvementModel = new MouvementModel();

        // Récupérer toutes les statistiques
        $stats = [
            'total_oeuvres' => $oeuvreModel->countTotal(),
            'total_auteurs' => (new AuteurModel())->countTotal() ?? 0,
            'total_categories' => (new CategorieModel())->countTotal() ?? 0,
            'total_expositions' => $expositionModel->countTotal(),
            'total_prets' => $pretModel->countTotal(),
            'total_restaurations' => $restaurationModel->countTotal(),
            'total_mouvements' => $mouvementModel->countTotal(),
            
            'expositions_en_cours' => $expositionModel->countEnCours(),
            'expositions_avenir' => $expositionModel->countAvenir(),
            'prets_en_cours' => $pretModel->countEnCours(),
            'prets_retard' => $pretModel->countRetard(),
            'restaurations_en_cours' => $restaurationModel->countEnCours(),
            'cout_restaurations' => $restaurationModel->getCoutTotal(),
            'mouvements_entrees' => $mouvementModel->countEntrees(),
            'mouvements_sorties' => $mouvementModel->countSorties(),
            
            'stats_statut' => $oeuvreModel->getStatsByStatut(),
            'stats_categorie' => $oeuvreModel->getStatsByCategorie(),
            'stats_auteurs_top' => $oeuvreModel->getStatsByAuteur(5),
        ];

        // Récupérer les alertes
        $alerts = $this->getAlerts();

        $this->render('dashboard', [
            'nom' => $_SESSION['nom'] ?? 'Utilisateur',
            'stats' => $stats,
            'alerts' => $alerts,
            'pageTitle' => 'Tableau de bord'
        ]);
    }
}
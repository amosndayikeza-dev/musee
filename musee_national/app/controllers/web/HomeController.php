<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\OeuvreModel;
use App\Models\ExpositionModel;

class HomeController extends Controller {
    
    public function indexAction() {
        $oeuvreModel = new OeuvreModel();
        $expositionModel = new ExpositionModel();
        
        // Récupérer les œuvres phares (exposées)
        $oeuvres = $oeuvreModel->getWithFilters(['statut' => 'exposé']);
        $oeuvres = array_slice($oeuvres, 0, 6); // 6 dernières
        
        // Expositions en cours
        $expositions = $expositionModel->getExpositionsEnCours();
        $expositions = array_slice($expositions, 0, 3);
        
        $this->render('index', [
            'oeuvres' => $oeuvres,
            'expositions' => $expositions,
            'pageTitle' => 'Accueil'
        ], 'public');
    }
}
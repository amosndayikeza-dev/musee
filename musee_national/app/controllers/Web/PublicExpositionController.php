<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\ExpositionModel;

class PublicExpositionController extends Controller {
    
    private $expositionModel;

    public function __construct() {
        $this->expositionModel = new ExpositionModel();
    }

    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $statut = $_GET['statut'] ?? '';
        
        $filters = [
            'keyword' => $keyword,
            'statut' => $statut
        ];

        $expositions = $this->expositionModel->getWithFilters($filters);
        $statuts = ['prévue', 'en cours', 'terminée'];

        $this->render('index', [
            'expositions' => $expositions,
            'statuts' => $statuts,
            'keyword' => $keyword,
            'statut' => $statut,
            'pageTitle' => 'Expositions'
        ], 'public');
    }

    public function showAction($id) {
        $exposition = $this->expositionModel->getById($id);
        if (!$exposition) {
            $this->redirect('public/exposition');
        }
        $oeuvres = $this->expositionModel->getOeuvres($id);
        $this->render('show', [
            'exposition' => $exposition,
            'oeuvres' => $oeuvres,
            'pageTitle' => $exposition->titre
        ], 'public');
    }
}
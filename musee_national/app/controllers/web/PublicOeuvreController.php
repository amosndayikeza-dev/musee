<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\OeuvreModel;
use App\Models\AuteurModel;
use App\Models\CategorieModel;

class PublicOeuvreController extends Controller {
    
    private $oeuvreModel;
    private $auteurModel;
    private $categorieModel;

    public function __construct() {
        $this->oeuvreModel = new OeuvreModel();
        $this->auteurModel = new AuteurModel();
        $this->categorieModel = new CategorieModel();
    }

    public function indexAction() {
   
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

        $oeuvres = $this->oeuvreModel->getWithFilters($filters);
        $auteurs = $this->auteurModel->getAll();
        $categories = $this->categorieModel->getAll();
        $statuts = ['exposé', 'en réserve', 'en restauration', 'en prêt'];

        $this->render('index', [
            'oeuvres' => $oeuvres,
            'auteurs' => $auteurs,
            'categories' => $categories,
            'statuts' => $statuts,
            'keyword' => $keyword,
            'statut' => $statut,
            'auteur_id' => $auteur_id,
            'categorie_id' => $categorie_id,
            'pageTitle' => 'Catalogue des œuvres'
        ], 'public');
    }

    public function showAction($id) {
        $oeuvre = $this->oeuvreModel->getByIdWithDetails($id);
        if (!$oeuvre) {
            $this->redirect('public/oeuvre');
        }
        $this->render('show', [
            'oeuvre' => $oeuvre,
            'pageTitle' => $oeuvre->titre
        ], 'public');
    }
}
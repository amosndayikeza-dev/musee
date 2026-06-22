<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\AuteurModel;

class PublicAuteurController extends Controller {
    
    private $auteurModel;

    public function __construct() {
        $this->auteurModel = new AuteurModel();
    }

    public function indexAction() {
        $keyword = $_GET['keyword'] ?? '';
        $nationalite = $_GET['nationalite'] ?? '';
        
        if (!empty($keyword)) {
            $auteurs = $this->auteurModel->search($keyword);
        } elseif (!empty($nationalite)) {
            $auteurs = $this->auteurModel->getByNationalite($nationalite);
        } else {
            $auteurs = $this->auteurModel->getAll();
        }
        
        $nationalites = $this->auteurModel->getAllNationalites();

        $this->render('index', [
            'auteurs' => $auteurs,
            'nationalites' => $nationalites,
            'keyword' => $keyword,
            'nationalite' => $nationalite,
            'pageTitle' => 'Auteurs'
        ], 'public');
    }

    public function showAction($id) {
        $auteur = $this->auteurModel->getById($id);
        if (!$auteur) {
            $this->redirect('public/auteur');
        }
        $oeuvres = $this->auteurModel->getOeuvres($id);
        $this->render('show', [
            'auteur' => $auteur,
            'oeuvres' => $oeuvres,
            'pageTitle' => $auteur->nom
        ], 'public');
    }
}
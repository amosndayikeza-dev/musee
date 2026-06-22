<?php
namespace App\Services;

use App\Models\OeuvreModel;
use App\Models\AuteurModel;
use App\Models\ExpositionModel;
use App\Models\PretModel;
use App\Models\CategorieModel;

class SearchService {
    
    private $oeuvreModel;
    private $auteurModel;
    private $expositionModel;
    private $pretModel;
    private $categorieModel;

    public function __construct() {
        $this->oeuvreModel = new OeuvreModel();
        $this->auteurModel = new AuteurModel();
        $this->expositionModel = new ExpositionModel();
        $this->pretModel = new PretModel();
        $this->categorieModel = new CategorieModel();
    }

    /**
     * Recherche globale dans toutes les tables
     * @param string $keyword
     * @return array ['oeuvres' => [], 'auteurs' => [], 'expositions' => [], 'prets' => [], 'categories' => []]
     */
    public function globalSearch($keyword) {
        $results = [];

        // Recherche dans les œuvres (titre, description)
        $results['oeuvres'] = $this->oeuvreModel->search($keyword);

        // Recherche dans les auteurs (nom, prénom)
        $results['auteurs'] = $this->auteurModel->search($keyword);

        // Recherche dans les expositions (titre, description, lieu)
        $results['expositions'] = $this->expositionModel->search($keyword);

        // Recherche dans les prêts (emprunteur)
        $results['prets'] = $this->pretModel->search($keyword);

        // Recherche dans les catégories (nom, description)
        $results['categories'] = $this->categorieModel->search($keyword);

        return $results;
    }

    /**
     * Recherche filtrée par type
     * @param string $type (oeuvre, auteur, exposition, pret, categorie)
     * @param string $keyword
     * @return array
     */
    public function searchByType($type, $keyword) {
        switch ($type) {
            case 'oeuvre':
                return $this->oeuvreModel->search($keyword);
            case 'auteur':
                return $this->auteurModel->search($keyword);
            case 'exposition':
                return $this->expositionModel->search($keyword);
            case 'pret':
                return $this->pretModel->search($keyword);
            case 'categorie':
                return $this->categorieModel->search($keyword);
            default:
                return [];
        }
    }
}
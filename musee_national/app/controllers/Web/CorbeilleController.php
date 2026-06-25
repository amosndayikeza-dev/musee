<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\OeuvreModel;
use App\Models\AuteurModel;
use App\Models\CategorieModel;
use App\Models\ExpositionModel;
use App\Models\PretModel;
use App\Models\RestaurationModel;
use App\Models\MouvementModel;
use App\Middlewares\SessionMiddleware;

class CorbeilleController extends Controller {
    
    public function __construct() {
        SessionMiddleware::check();
        AuthMiddleware::requireAdminOrConservateur();
    }

    /**
     * Affiche tous les éléments supprimés (toutes tables)
     */
    public function indexAction() {
        $corbeille = $this->getAllTrashed();

        usort($corbeille, function($a, $b) {
            return strtotime($b['deleted_at']) - strtotime($a['deleted_at']);
        });

        $this->render('index', [
            'corbeille' => $corbeille,
            'pageTitle' => 'Corbeille'
        ]);
    }

    /**
     * Restaure un élément depuis la corbeille
     */
    public function restoreAction($table, $id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/corbeille');
            return;
        }

        $model = $this->getModel($table);
        if ($model) {
            $model->restore($id);
            $_SESSION['success'] = 'Élément restauré avec succès !';
        } else {
            $_SESSION['error'] = 'Table non reconnue.';
        }
        $this->redirect('admin/corbeille');
    }

    /**
     * Supprime définitivement un élément
     */
    public function forceDeleteAction($table, $id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/corbeille');
            return;
        }

        $model = $this->getModel($table);
        if ($model) {
            $model->forceDelete($id);
            $_SESSION['success'] = 'Élément supprimé définitivement !';
        } else {
            $_SESSION['error'] = 'Table non reconnue.';
        }
        $this->redirect('admin/corbeille');
    }

    /**
     * Vide toutes les corbeilles (supprime définitivement tout)
     */
    public function emptyAllAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/corbeille');
            return;
        }

        $models = [
            new OeuvreModel(),
            new AuteurModel(),
            new CategorieModel(),
            new ExpositionModel(),
            new PretModel(),
            new RestaurationModel(),
            new MouvementModel()
        ];

        foreach ($models as $model) {
            $model->forceDeleteAllTrashed();
        }

        $_SESSION['success'] = 'Toutes les corbeilles ont été vidées !';
        $this->redirect('admin/corbeille');
    }

    /**
     * Récupère tous les éléments supprimés de toutes les tables
     */
    private function getAllTrashed() {
        $corbeille = [];

        $oeuvreModel = new OeuvreModel();
        $auteurModel = new AuteurModel();
        $categorieModel = new CategorieModel();
        $expositionModel = new ExpositionModel();
        $pretModel = new PretModel();
        $restaurationModel = new RestaurationModel();
        $mouvementModel = new MouvementModel();

        // Œuvres
        foreach ($oeuvreModel->getTrashed() as $item) {
            $corbeille[] = [
                'id' => $item->id,
                'type' => 'Œuvre',
                'nom' => $item->titre,
                'table' => 'oeuvre',
                'deleted_at' => $item->deleted_at,
            ];
        }

        // Auteurs
        foreach ($auteurModel->getTrashed() as $item) {
            $corbeille[] = [
                'id' => $item->id,
                'type' => 'Auteur',
                'nom' => $item->nom . ' ' . ($item->prenom ?? ''),
                'table' => 'auteur',
                'deleted_at' => $item->deleted_at,
            ];
        }

        // Catégories
        foreach ($categorieModel->getTrashed() as $item) {
            $corbeille[] = [
                'id' => $item->id,
                'type' => 'Catégorie',
                'nom' => $item->nom,
                'table' => 'categorie',
                'deleted_at' => $item->deleted_at,
            ];
        }

        // Expositions
        foreach ($expositionModel->getTrashed() as $item) {
            $corbeille[] = [
                'id' => $item->id,
                'type' => 'Exposition',
                'nom' => $item->titre,
                'table' => 'exposition',
                'deleted_at' => $item->deleted_at,
            ];
        }

        // Prêts
        foreach ($pretModel->getTrashed() as $item) {
            $corbeille[] = [
                'id' => $item->id,
                'type' => 'Prêt',
                'nom' => 'Prêt #' . $item->id,
                'table' => 'pret',
                'deleted_at' => $item->deleted_at,
            ];
        }

        // Restaurations
        foreach ($restaurationModel->getTrashed() as $item) {
            $corbeille[] = [
                'id' => $item->id,
                'type' => 'Restauration',
                'nom' => 'Restauration #' . $item->id,
                'table' => 'restauration',
                'deleted_at' => $item->deleted_at,
            ];
        }

        // Mouvements
        foreach ($mouvementModel->getTrashed() as $item) {
            $corbeille[] = [
                'id' => $item->id,
                'type' => 'Mouvement',
                'nom' => 'Mouvement #' . $item->id,
                'table' => 'mouvement',
                'deleted_at' => $item->deleted_at,
            ];
        }

        return $corbeille;
    }

    /**
     * Retourne le modèle correspondant à la table
     */
    private function getModel($table) {
        switch ($table) {
            case 'oeuvre': return new OeuvreModel();
            case 'auteur': return new AuteurModel();
            case 'categorie': return new CategorieModel();
            case 'exposition': return new ExpositionModel();
            case 'pret': return new PretModel();
            case 'restauration': return new RestaurationModel();
            case 'mouvement': return new MouvementModel();
            default: return null;
        }
    }
}
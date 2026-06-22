<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\ParametresModel;

class ParametresController extends Controller {
    
    private $parametresModel;

    public function __construct() {
        AuthMiddleware::requireAdmin();
        $this->parametresModel = new ParametresModel();
    }

    public function indexAction() {
        $parametres = $this->parametresModel->getAll();
        
        $this->render('index', [
            'parametres' => $parametres,
            'pageTitle' => 'Paramètres du système'
        ]);
    }

    public function updateAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('parametres/index');
            return;
        }
        
        foreach ($_POST as $cle => $valeur) {
            if ($cle === 'submit') continue;
            $this->parametresModel->set($cle, trim($valeur));
        }
        
        $_SESSION['success'] = 'Paramètres mis à jour avec succès !';
        $this->redirect('parametres/index');
    }
}
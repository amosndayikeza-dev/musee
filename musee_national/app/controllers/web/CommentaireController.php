<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\CommentaireModel;
use App\Models\OeuvreModel;
use App\Middlewares\SessionMiddleware;

class CommentaireController extends Controller {
    
    private $commentaireModel;
    private $oeuvreModel;

    public function __construct() {
        SessionMiddleware::check();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            return;
        }
        $this->commentaireModel = new CommentaireModel();
        $this->oeuvreModel = new OeuvreModel();
    }

    public function addAction($oeuvreId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('public/oeuvre/show/' . $oeuvreId);
            return;
        }
        
        $contenu = trim($_POST['contenu'] ?? '');
        if (empty($contenu)) {
            $_SESSION['error'] = 'Le commentaire ne peut pas être vide';
            $this->redirect('public/oeuvre/show/' . $oeuvreId);
            return;
        }
        
        $this->commentaireModel->add($oeuvreId, $_SESSION['user_id'], $contenu);
        $_SESSION['success'] = 'Commentaire ajouté avec succès !';
        $this->redirect('public/oeuvre/show/' . $oeuvreId);
    }

    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('public/oeuvre');
            return;
        }
        
        $commentaire = $this->commentaireModel->getById($id);
        if (!$commentaire || ($commentaire->utilisateur_id != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin')) {
            $_SESSION['error'] = 'Vous n\'êtes pas autorisé à supprimer ce commentaire';
            $this->redirect('public/oeuvre');
            return;
        }
        
        $this->commentaireModel->delete($id);
        $_SESSION['success'] = 'Commentaire supprimé avec succès !';
        $this->redirect('public/oeuvre');
    }
}
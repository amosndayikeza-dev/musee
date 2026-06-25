<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Middlewares\SessionMiddleware;

class DocumentationController extends Controller {
    
    /**
     * Constructeur : vérifier que l'utilisateur est connecté (admin ou conservateur)
     */
    public function __construct() {

    SessionMiddleware::check();
        // Si l'utilisateur n'est pas connecté, rediriger vers login
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        // Seul l'admin et le conservateur peuvent accéder à la doc
        if (!in_array($_SESSION['role'], ['admin', 'conservateur'])) {
            $this->redirect('home/index');
            exit;
        }
    }

    /**
     * Page d'accueil de la documentation
     * URL: /documentation/index
     */
    public function indexAction() {
        $this->render('index', [
            'pageTitle' => 'Documentation'
        ]);
    }

    /**
     * Présentation du logiciel
     * URL: /documentation/presentation
     */
    public function presentationAction() {
        $this->render('presentation', [
            'pageTitle' => 'Présentation du logiciel'
        ]);
    }

    /**
     * Guide d'utilisation
     * URL: /documentation/guide
     */
    public function guideAction() {
        $this->render('guide', [
            'pageTitle' => "Guide d'utilisation"
        ]);
    }

    /**
     * Diagrammes UML
     * URL: /documentation/diagrammes
     */
    public function diagrammesAction() {
        $this->render('diagrammes', [
            'pageTitle' => 'Diagrammes UML'
        ]);
    }
}
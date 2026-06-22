<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\ThemeModel;

class ThemesController extends Controller {
    
    private $themeModel;

    public function __construct() {
        AuthMiddleware::requireAdmin();
        $this->themeModel = new ThemeModel();
    }

    public function indexAction() {
        $themes = $this->themeModel->getAll();
        
        $this->render('index', [
            'themes' => $themes,
            'pageTitle' => 'Gestion des thèmes'
        ]);
    }

    public function activateAction($id) {
        $this->themeModel->activate($id);
        $_SESSION['success'] = 'Thème activé avec succès !';
        $this->redirect('themes/index');
    }
}
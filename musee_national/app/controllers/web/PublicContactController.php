<?php
namespace App\Controllers\Web;

use App\Core\Controller;

class PublicContactController extends Controller {
    
    public function indexAction() {
        $this->render('index', [
            'pageTitle' => 'Contact'
        ], 'public');
    }
}
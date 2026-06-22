<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Services\SearchService;

class SearchController extends Controller {
    
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }

    public function indexAction() {
        $keyword = trim($_GET['q'] ?? '');
        $type = $_GET['type'] ?? 'all'; // all, oeuvre, auteur, exposition, pret, categorie

        if (empty($keyword)) {
            $this->render('index', ['results' => [], 'keyword' => '', 'type' => $type]);
            return;
        }

        $searchService = new SearchService();
        
        if ($type === 'all') {
            $results = $searchService->globalSearch($keyword);
        } else {
            $results = $searchService->searchByType($type, $keyword);
        }

        $this->render('index', [
            'results' => $results,
            'keyword' => $keyword,
            'type' => $type
        ]);
    }
}
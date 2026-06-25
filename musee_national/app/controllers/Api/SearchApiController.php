<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Services\SearchService;

class SearchApiController extends Controller {
    
    public function indexAction() {
        $keyword = $_GET['q'] ?? '';
        $type = $_GET['type'] ?? 'all';
        
        if (empty($keyword)) {
            $this->jsonResponse(['error' => 'Mot-clé requis'], 400);
        }
        
        $searchService = new SearchService();
        $results = ($type === 'all') ? $searchService->globalSearch($keyword) : $searchService->searchByType($type, $keyword);
        
        $this->jsonResponse($results);
    }
}
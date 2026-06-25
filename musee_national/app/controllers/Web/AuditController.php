<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Services\AuditService;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\SessionMiddleware;

class AuditController extends Controller {
    
    public function __construct() {
        SessionMiddleware::check();
        AuthMiddleware::requireAdmin();
    }

    public function indexAction() {
        $filters = [
            'action' => $_GET['action'] ?? '',
            'table' => $_GET['table'] ?? '',
            'date_debut' => $_GET['date_debut'] ?? '',
            'date_fin' => $_GET['date_fin'] ?? ''
        ];

        $auditService = new AuditService();
        $logs = $auditService->getLogs(50, $filters);

        $this->render('index', [
            'logs' => $logs,
            'pageTitle' => 'Journal d\'audit'
        ]);
    }
}
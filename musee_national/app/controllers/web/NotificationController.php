<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Services\NotificationService;
use App\Middlewares\AuthMiddleware;

class NotificationController extends Controller {

    public function __construct() {
        AuthMiddleware::requireAuth();
    }

    public function indexAction() {
        $userId = $_SESSION['user_id'];
        $notificationService = new NotificationService();
        $notifications = $notificationService->getAll($userId, 100);
        $unreadCount = $notificationService->countUnread($userId);

        $this->render('index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'pageTitle' => 'Mes notifications'
        ]);
    }

    public function markAsReadAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('notification/index');
            return;
        }
        $notificationService = new NotificationService();
        $notificationService->markAsRead($id, $_SESSION['user_id']);
        $this->redirect('notification/index');
    }

    public function markAllAsReadAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('notification/index');
            return;
        }
        $notificationService = new NotificationService();
        $notificationService->markAllAsRead($_SESSION['user_id']);
        $this->redirect('notification/index');
    }

    public function unreadCountAction() {
        $notificationService = new NotificationService();
        $count = $notificationService->countUnread($_SESSION['user_id']);
        $this->jsonResponse(['count' => $count]);
    }
}
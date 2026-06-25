<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\MessageContactModel;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\SessionMiddleware;


class MessagesController extends Controller {
    
    private $model;

    public function __construct() {
        SessionMiddleware::check();
        AuthMiddleware::requireAdminOrConservateur();
        $this->model = new MessageContactModel();
    }

    public function indexAction() {
        $messages = $this->model->getAll(100);
        $unread = $this->model->getUnreadCount();

        $this->render('index', [
            'messages' => $messages,
            'unread' => $unread,
            'pageTitle' => 'Messages de contact'
        ]);
    }

    public function showAction($id) {
        $message = $this->model->getById($id);
        if (!$message) {
            $this->redirect('admin/messages');
            return;
        }
        $this->model->markAsRead($id);

        $this->render('show', [
            'message' => $message,
            'pageTitle' => 'Détail du message'
        ]);
    }

    public function deleteAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/messages');
            return;
        }
        $this->model->delete($id);
        $_SESSION['success'] = 'Message supprimé avec succès !';
        $this->redirect('admin/messages');
    }

    public function markAsReadAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/messages');
            return;
        }
        $this->model->markAsRead($id);
        $this->redirect('admin/messages');
    }

    public function markAsRepliedAction($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/messages');
            return;
        }
        $this->model->markAsReplied($id);
        $_SESSION['success'] = 'Message marqué comme répondu.';
        $this->redirect('admin/messages');
    }
}
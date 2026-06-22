<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\ChatModel;

class ChatController extends Controller {
    
    private $chatModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            return;
        }
        $this->chatModel = new ChatModel();
    }

    public function indexAction() {
        $users = $this->chatModel->getUsers($_SESSION['user_id']);
        
        $this->render('index', [
            'users' => $users,
            'pageTitle' => 'Chat'
        ]);
    }

    public function messagesAction($userId) {
        $messages = $this->chatModel->getMessages($_SESSION['user_id'], $userId);
        
        $this->jsonResponse([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    public function sendAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['status' => 'error', 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $destinataireId = $input['destinataire_id'] ?? 0;
        $message = trim($input['message'] ?? '');
        
        if (empty($message)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Message vide']);
            return;
        }
        
        $this->chatModel->send($_SESSION['user_id'], $destinataireId, $message);
        
        $this->jsonResponse(['status' => 'success', 'message' => 'Message envoyé']);
    }

    public function unreadAction() {
        $count = $this->chatModel->countUnread($_SESSION['user_id']);
        
        $this->jsonResponse([
            'status' => 'success',
            'count' => $count
        ]);
    }
}
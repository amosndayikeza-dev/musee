<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\ChatModel;

class ChatController extends Controller {

    private $chatModel;

    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        $this->chatModel = new ChatModel();
    }

    /**
     * Affiche l'interface du chat
     */
    public function indexAction() {
        $userId = $_SESSION['user_id'];
        $users = $this->chatModel->getUsers($userId);

        $this->render('index', [
            'users' => $users,
            'pageTitle' => 'Chat'
        ]);
    }

    /**
     * Récupère les messages entre l'utilisateur courant et un autre (AJAX)
     */
    public function messagesAction($userId) {
        $expediteurId = $_SESSION['user_id'];
        $messages = $this->chatModel->getMessages($expediteurId, $userId);

        $this->jsonResponse([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    /**
     * Envoie un message (AJAX POST)
     */
    public function sendAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['status' => 'error', 'message' => 'Méthode non autorisée']);
            return;
        }

        $destinataireId = $_POST['destinataire_id'] ?? 0;
        $message = trim($_POST['message'] ?? '');
        $fichierPath = null;
        $typeFichier = null;

        // Gérer l'upload de fichier
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_DIR . 'chat/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $extension = pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $destination)) {
                $fichierPath = 'uploads/chat/' . $filename;
                $typeFichier = mime_content_type($destination);
            }
        }

            // Enregistrer le message
            $this->chatModel->send($_SESSION['user_id'], $destinataireId, $message, $fichierPath, $typeFichier);

            $this->jsonResponse(['status' => 'success', 'message' => 'Message envoyé']);
        }
    /**
     * Récupère le nombre de messages non lus (AJAX)
     */
    public function unreadAction() {
        $count = $this->chatModel->countUnread($_SESSION['user_id']);
        $this->jsonResponse([
            'status' => 'success',
            'count' => $count
        ]);
    }
}
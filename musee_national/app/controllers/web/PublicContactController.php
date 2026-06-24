<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\MessageContactModel;

class PublicContactController extends Controller {
    
    public function indexAction() {
        $this->render('index', [
            'pageTitle' => 'Contact'
        ], 'public');
    }

    public function sendAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('public/contact');
            return;
        }

        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $sujet = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            $this->render('index', [
                'error' => 'Tous les champs sont obligatoires.',
                'old' => compact('nom', 'email', 'sujet', 'message')
            ], 'public');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('index', [
                'error' => 'Adresse email invalide.',
                'old' => compact('nom', 'email', 'sujet', 'message')
            ], 'public');
            return;
        }

        $model = new MessageContactModel();
        $model->insert(compact('nom', 'email', 'sujet', 'message'));

        $this->render('index', [
            'success' => 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.'
        ], 'public');
    }
}
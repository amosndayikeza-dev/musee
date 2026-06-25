<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\UtilisateurModel;
use App\Services\EmailService;
use App\Middlewares\SessionMiddleware;

class ResetPasswordController extends Controller {
    
    private $utilisateurModel;

    public function __construct() {
        SessionMiddleware::check();
        $this->utilisateurModel = new UtilisateurModel();
    }

    /**
     * Affiche le formulaire "Mot de passe oublié"
     */
    public function forgotAction() {
        $this->render('forgot', [
            'pageTitle' => 'Mot de passe oublié'
        ], 'auth');
    }

    /**
     * Envoie l'email de réinitialisation
     */
    public function sendAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reset/forgot');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            $this->render('forgot', ['error' => 'Veuillez saisir votre email'], 'auth');
            return;
        }

        // Vérifier si l'email existe
        $user = $this->utilisateurModel->findByEmail($email);
        if (!$user) {
            $this->render('forgot', ['error' => 'Aucun compte trouvé avec cet email'], 'auth');
            return;
        }

        // Générer le token
        $token = $this->utilisateurModel->generateResetToken($email);
        $resetLink = BASE_URL . 'reset/password?token=' . $token;

        // Envoyer l'email
        $emailService = new EmailService();
        $subject = 'Réinitialisation de votre mot de passe - Musée National';
        $body = $this->getResetEmailBody($user->nom, $resetLink);
        
        $emailService->send($email, $subject, $body);

        $this->render('forgot', [
            'success' => 'Un email de réinitialisation vous a été envoyé. Vérifiez votre boîte mail.'
        ], 'auth');
    }

    /**
     * Affiche le formulaire de réinitialisation
     */
    public function passwordAction() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->redirect('reset/forgot');
            return;
        }

        // Vérifier le token
        $tokenData = $this->utilisateurModel->verifyResetToken($token);
        if (!$tokenData) {
            $this->render('forgot', ['error' => 'Ce lien est invalide ou a expiré.'], 'auth');
            return;
        }

        $this->render('reset', [
            'token' => $token,
            'pageTitle' => 'Réinitialiser mon mot de passe'
        ], 'auth');
    }

    /**
     * Réinitialise le mot de passe
     */
    public function updateAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reset/forgot');
            return;
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($token) || empty($password) || empty($confirm)) {
            $this->render('reset', [
                'token' => $token,
                'error' => 'Tous les champs sont obligatoires'
            ], 'auth');
            return;
        }

        if (strlen($password) < 6) {
            $this->render('reset', [
                'token' => $token,
                'error' => 'Le mot de passe doit contenir au moins 6 caractères'
            ], 'auth');
            return;
        }

        if ($password !== $confirm) {
            $this->render('reset', [
                'token' => $token,
                'error' => 'Les mots de passe ne correspondent pas'
            ], 'auth');
            return;
        }

        // Réinitialiser le mot de passe
        $result = $this->utilisateurModel->resetPassword($token, $password);
        if (!$result) {
            $this->render('reset', [
                'token' => $token,
                'error' => 'Ce lien est invalide ou a expiré'
            ], 'auth');
            return;
        }

        $this->render('success', [
            'message' => 'Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.'
        ], 'auth');
    }

    /**
     * Génère le corps de l'email de réinitialisation
     */
    private function getResetEmailBody($nom, $resetLink) {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <div style="text-align: center; padding: 20px; background: #1a2a3a; border-radius: 8px 8px 0 0;">
                <h1 style="color: #c9a84c; margin: 0;">Musée National</h1>
            </div>
            <div style="padding: 30px; background: #ffffff; border-radius: 0 0 8px 8px;">
                <h2>Bonjour ' . htmlspecialchars($nom) . ',</h2>
                <p>Vous avez demandé à réinitialiser votre mot de passe pour votre compte Musée National.</p>
                <p>Cliquez sur le lien ci-dessous pour définir un nouveau mot de passe :</p>
                <div style="text-align: center; margin: 30px 0;">
                    <a href="' . $resetLink . '" style="background: #c9a84c; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                        Réinitialiser mon mot de passe
                    </a>
                </div>
                <p style="font-size: 14px; color: #888;">Ce lien est valable pendant 1 heure.</p>
                <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
                <p style="font-size: 12px; color: #aaa; text-align: center;">
                    Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet email.
                </p>
            </div>
        </div>';
    }
}
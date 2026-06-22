<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\UtilisateurModel;

class AuthController extends Controller {
    
    public function loginAction() {
        // Vérifier si c'est un timeout
        $timeout = isset($_GET['timeout']);
        $error = null;
        
        if ($timeout) {
            $error = 'Votre session a expiré après 1 heure d\'inactivité. Veuillez vous reconnecter.';
        }
        
        // Si déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'conservateur') {
                $this->redirect('admin/dashboard');
            } else {
                $this->redirect('home/index');
            }
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $this->render('login', ['error' => 'Tous les champs sont requis'], 'auth');
                return;
            }
            
            $userModel = new UtilisateurModel();

            // Vérifier le verrouillage du compte
            $failedAttempts = $userModel->countFailedAttempts($email);
            if ($failedAttempts >= 5) {
                $this->render('login', [
                    'error' => 'Compte verrouillé. Trop de tentatives échouées. Réessayez dans 30 minutes.'
                ], 'auth');
                return;
            }

            $user = $userModel->findByEmail($email);
            
            // Vérification du mot de passe et de l'existence de l'utilisateur
            if (!$user || !password_verify($password, $user->mot_de_passe)) {
                // Log de l'échec de connexion
                $userModel->logConnexion($user->id ?? 0, $email, 'échec');
                $this->render('login', ['error' => 'Email ou mot de passe incorrect'], 'auth');
                return;
            }

            // Log de la connexion réussie
            $userModel->logConnexion($user->id, $user->email, 'succès');

            // Créer la session
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['role'] = $user->role;
            $_SESSION['nom'] = $user->nom;
            $_SESSION['last_activity'] = time();
            $_SESSION['login_time'] = time();

            $userModel->updateLastAccess($user->id);

            // Redirection selon le rôle
            if ($user->role === 'admin' || $user->role === 'conservateur') {
                $this->redirect('admin/dashboard');
            } else {
                $this->redirect('home/index');
            }
            return;
        }

        // Affichage du formulaire (GET)
        $this->render('login', ['error' => $error], 'auth');
    }

    public function logoutAction() {
        // Log de la déconnexion
        if (isset($_SESSION['user_id'])) {
            $userModel = new UtilisateurModel();
            $userModel->logConnexion($_SESSION['user_id'], $_SESSION['user_email'], 'déconnexion');
        }
        
        $_SESSION = array();
        session_destroy();
        $this->redirect('auth/login');
    }
}
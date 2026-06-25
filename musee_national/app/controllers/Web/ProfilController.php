<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\UtilisateurModel;
use App\Services\UploadService;
use App\Middlewares\SessionMiddleware;

class ProfilController extends Controller {
    
    private $utilisateurModel;

    public function __construct() {
        SessionMiddleware::check();
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            exit;
        }
        $this->utilisateurModel = new UtilisateurModel();
    }

    /**
     * Affiche le profil de l'utilisateur connecté
     * URL: /profil
     */
    public function indexAction() {
        $userId = $_SESSION['user_id'];
        $user = $this->utilisateurModel->getById($userId);
        $stats = $this->utilisateurModel->getUserStats($userId);
        
        // Récupérer l'historique des connexions
        $connexions = $this->utilisateurModel->getHistoriqueConnexions($userId, 10);
        
        $this->render('index', [
            'user' => $user,
            'stats' => $stats,
            'connexions' => $connexions,
            'pageTitle' => 'Mon profil'
        ]);
    }

    /**
     * Affiche le profil d'un utilisateur spécifique (admin seulement)
     * URL: /profil/show/{id}
     */
    public function showAction($id) {
        // Vérifier que l'utilisateur est admin ou que c'est son propre profil
        if (!in_array($_SESSION['role'], ['admin', 'conservateur']) && $_SESSION['user_id'] != $id) {
            $this->redirect('profil/index');
            return;
        }
        
        $user = $this->utilisateurModel->getById($id);
        if (!$user) {
            $this->redirect('profil/index');
            return;
        }
        
        $stats = $this->utilisateurModel->getUserStats($id);
        $connexions = $this->utilisateurModel->getHistoriqueConnexions($id, 10);
        
        $this->render('show', [
            'user' => $user,
            'stats' => $stats,
            'connexions' => $connexions,
            'pageTitle' => 'Profil de ' . $user->nom
        ]);
    }

    /**
     * Formulaire d'édition du profil
     * URL: /profil/edit
     */
    public function editAction() {
        $userId = $_SESSION['user_id'];
        $user = $this->utilisateurModel->getById($userId);
        
        $this->render('edit', [
            'user' => $user,
            'pageTitle' => 'Modifier mon profil'
        ]);
    }

    /**
     * Met à jour le profil
     * URL: /profil/update
     */
    public function updateAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profil/edit');
            return;
        }

        $userId = $_SESSION['user_id'];
        
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
            'biographie' => trim($_POST['biographie'] ?? ''),
            'adresse' => trim($_POST['adresse'] ?? ''),
            'ville' => trim($_POST['ville'] ?? ''),
            'code_postal' => trim($_POST['code_postal'] ?? ''),
            'pays' => trim($_POST['pays'] ?? ''),
            'date_naissance' => !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null,
            'genre' => $_POST['genre'] ?? null
        ];

        // Validation de l'email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide';
            $this->redirect('profil/edit');
            return;
        }

        // Vérifier si l'email existe déjà pour un autre utilisateur
        $existing = $this->utilisateurModel->findByEmail($data['email']);
        if ($existing && $existing->id != $userId) {
            $_SESSION['error'] = 'Cet email est déjà utilisé par un autre compte';
            $this->redirect('profil/edit');
            return;
        }

        // Gestion de la photo de profil
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadService = new UploadService();
            $photoPath = $uploadService->uploadProfilPhoto($_FILES['photo'], $userId);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        $this->utilisateurModel->updateProfil($userId, $data);
        
        // Mettre à jour la session
        $_SESSION['nom'] = $data['nom'];
        $_SESSION['user_email'] = $data['email'];
        
        $_SESSION['success'] = 'Votre profil a été mis à jour avec succès !';
        $this->redirect('profil/index');
    }

    /**
     * Formulaire de changement de mot de passe
     * URL: /profil/password
     */
    public function passwordAction() {
        $this->render('password', [
            'pageTitle' => 'Changer mon mot de passe'
        ]);
    }

    /**
     * Met à jour le mot de passe
     * URL: /profil/updatePassword
     */
    public function updatePasswordAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profil/password');
            return;
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Vérifier que tous les champs sont remplis
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            $this->redirect('profil/password');
            return;
        }

        // Vérifier que le nouveau mot de passe fait au moins 6 caractères
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'Le nouveau mot de passe doit contenir au moins 6 caractères';
            $this->redirect('profil/password');
            return;
        }

        // Vérifier que les mots de passe correspondent
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
            $this->redirect('profil/password');
            return;
        }

        // Vérifier l'ancien mot de passe
        $user = $this->utilisateurModel->getById($userId);
        if (!password_verify($currentPassword, $user->mot_de_passe)) {
            $_SESSION['error'] = 'Le mot de passe actuel est incorrect';
            $this->redirect('profil/password');
            return;
        }

        // Mettre à jour le mot de passe
        $this->utilisateurModel->changePassword($userId, $newPassword);
        
        $_SESSION['success'] = 'Votre mot de passe a été changé avec succès !';
        $this->redirect('profil/index');
    }
}
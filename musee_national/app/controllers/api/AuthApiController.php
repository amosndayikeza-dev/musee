<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\UtilisateurModel;
use App\Core\JwtHandler;
use App\Middlewares\ApiAuthMiddleware;

class AuthApiController extends Controller {
    
    private $utilisateurModel;

    public function __construct() {
        $this->utilisateurModel = new UtilisateurModel();
    }

    /**
     * POST /api/auth/login
     * Authentification et génération du token JWT
     */
    public function loginAction() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validation des champs
        if (!isset($input['email']) || !isset($input['password'])) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Email et mot de passe requis'
            ], 400);
            return;
        }

        $email = trim($input['email']);
        $password = $input['password'];

        // Vérifier l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Email invalide'
            ], 400);
            return;
        }

        // Rechercher l'utilisateur
        $user = $this->utilisateurModel->findByEmail($email);
        
        if (!$user) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
            return;
        }

        // Vérifier le mot de passe
        if (!password_verify($password, $user->mot_de_passe)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
            return;
        }

        // Mettre à jour le dernier accès
        $this->utilisateurModel->updateLastAccess($user->id);

        // Générer le token JWT
        $token = JwtHandler::generate($user->id, $user->email, $user->role);

        // Réponse succès
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Authentification réussie',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'role' => $user->role
                ],
                'expires_in' => JWT_EXPIRE
            ]
        ], 200);
    }

    /**
     * POST /api/auth/register
     * Inscription d'un nouvel utilisateur (visiteur)
     */
    public function registerAction() {
        $input = json_decode(file_get_contents('php://input'), true);

        // Validation des champs
        $requiredFields = ['nom', 'email', 'password', 'password_confirm'];
        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => "Le champ '$field' est requis"
                ], 400);
                return;
            }
        }

        $nom = trim($input['nom']);
        $prenom = trim($input['prenom'] ?? '');
        $email = trim($input['email']);
        $password = $input['password'];
        $passwordConfirm = $input['password_confirm'];

        // Vérifier l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Email invalide'
            ], 400);
            return;
        }

        // Vérifier la longueur du mot de passe
        if (strlen($password) < 6) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Le mot de passe doit contenir au moins 6 caractères'
            ], 400);
            return;
        }

        // Vérifier la confirmation du mot de passe
        if ($password !== $passwordConfirm) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Les mots de passe ne correspondent pas'
            ], 400);
            return;
        }

        // Vérifier si l'email existe déjà
        if ($this->utilisateurModel->emailExists($email)) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Cet email est déjà utilisé'
            ], 409);
            return;
        }

        // Créer l'utilisateur (par défaut rôle 'visiteur')
        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mot_de_passe' => $password,
            'role' => 'visiteur'
        ];

        $userId = $this->utilisateurModel->create($data);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Inscription réussie',
            'data' => [
                'id' => $userId,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'role' => 'visiteur'
            ]
        ], 201);
    }

    /**
     * POST /api/auth/logout
     * Déconnexion (invalidation du token)
     */
    public function logoutAction() {
        // Pour JWT, la déconnexion est gérée côté client (suppression du token)
        // Mais on peut ajouter une blacklist si nécessaire
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Déconnexion réussie'
        ], 200);
    }

    /**
     * GET /api/auth/me
     * Récupère les informations de l'utilisateur connecté
     */
    public function meAction() {
        // Vérifier le token
        $payload = ApiAuthMiddleware::check();
        
        // Récupérer l'utilisateur
        $user = $this->utilisateurModel->getById($payload['userId']);
        
        if (!$user) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Utilisateur non trouvé'
            ], 404);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'role' => $user->role,
                'date_creation' => $user->date_creation,
                'dernier_acces' => $user->dernier_acces
            ]
        ], 200);
    }

    /**
     * POST /api/auth/refresh
     * Rafraîchit le token JWT
     */
    public function refreshAction() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Token manquant'
            ], 401);
            return;
        }

        $newToken = JwtHandler::refresh($token);
        
        if (!$newToken) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Token invalide ou expiré'
            ], 401);
            return;
        }

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Token rafraîchi avec succès',
            'data' => [
                'token' => $newToken,
                'expires_in' => JWT_EXPIRE
            ]
        ], 200);
    }
}
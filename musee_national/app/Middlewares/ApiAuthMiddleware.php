<?php
namespace App\Middlewares;

use App\Core\JwtHandler;

class ApiAuthMiddleware {
    
    /**
     * Vérifie la présence et la validité du token JWT
     */
    public static function check() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        
        // Support: Bearer token
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            // Support: token dans l'URL (pour les tests)
            $token = $_GET['token'] ?? null;
        }

        if (!$token) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Token manquant. Veuillez vous authentifier.'
            ]);
            exit;
        }

        $payload = JwtHandler::verify($token);
        if (!$payload) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Token invalide ou expiré. Veuillez vous reconnecter.'
            ]);
            exit;
        }

        // Vérifier si le token est expiré
        if (JwtHandler::isExpired($token)) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Token expiré. Veuillez vous reconnecter.'
            ]);
            exit;
        }

        // Stocker le payload pour utilisation ultérieure
        $_REQUEST['jwt_payload'] = $payload;
        return $payload;
    }

    /**
     * Vérifie que l'utilisateur a le rôle admin
     */
    public static function requireAdmin() {
        $payload = self::check();
        if ($payload['role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Accès interdit. Rôle administrateur requis.'
            ]);
            exit;
        }
        return $payload;
    }

    /**
     * Vérifie que l'utilisateur a le rôle admin ou conservateur
     */
    public static function requireAdminOrConservateur() {
        $payload = self::check();
        if (!in_array($payload['role'], ['admin', 'conservateur'])) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Accès interdit. Rôle administrateur ou conservateur requis.'
            ]);
            exit;
        }
        return $payload;
    }

    /**
     * Vérifie un rôle spécifique
     */
    public static function requireRole($role) {
        $payload = self::check();
        if ($payload['role'] !== $role) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => "Accès interdit. Rôle $role requis."
            ]);
            exit;
        }
        return $payload;
    }
}
<?php
namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler {
    
    /**
     * Génère un token JWT
     */
    public static function generate($userId, $email, $role) {
        $issuedAt = time();
        $expire = $issuedAt + JWT_EXPIRE;
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'userId' => $userId,
            'email' => $email,
            'role' => $role
        ];
        
        return JWT::encode($payload, JWT_SECRET, 'HS256');
    }

    /**
     * Vérifie et décode un token JWT
     */
    public static function verify($token) {
        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Récupère le payload du token
     */
    public static function getPayload($token) {
        $payload = self::verify($token);
        if ($payload) {
            return $payload;
        }
        return null;
    }

    /**
     * Vérifie si le token est expiré
     */
    public static function isExpired($token) {
        $payload = self::verify($token);
        if ($payload && isset($payload['exp'])) {
            return $payload['exp'] < time();
        }
        return true;
    }

    /**
     * Rafraîchit le token (génère un nouveau)
     */
    public static function refresh($token) {
        $payload = self::verify($token);
        if ($payload) {
            return self::generate(
                $payload['userId'],
                $payload['email'],
                $payload['role']
            );
        }
        return null;
    }
}
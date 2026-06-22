<?php
namespace App\Middlewares;

class AuthMiddleware {
    
    /**
     * Vérifie que l'utilisateur est connecté
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifie que l'utilisateur a le rôle admin
     */
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Vérifie que l'utilisateur a le rôle conservateur
     */
    public static function isConservateur() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'conservateur';
    }

    /**
     * Vérifie que l'utilisateur a le rôle admin ou conservateur
     */
    public static function isAdminOrConservateur() {
        return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'conservateur']);
    }

    /**
     * Vérifie que l'utilisateur a un rôle spécifique
     */
    public static function hasRole($role) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    /**
     * Vérifie que l'utilisateur a un des rôles spécifiés
     */
    public static function hasAnyRole($roles) {
        return isset($_SESSION['role']) && in_array($_SESSION['role'], $roles);
    }

    /**
     * Redirige si non connecté
     */
    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    /**
     * Redirige si pas admin
     */
    public static function requireAdmin() {
        self::requireAuth();
        if (!self::isAdmin()) {
            header('Location: ' . BASE_URL . 'admin/dashboard');
            exit;
        }
    }

    /**
     * Redirige si pas admin ou conservateur
     */
    public static function requireAdminOrConservateur() {
        self::requireAuth();
        if (!self::isAdminOrConservateur()) {
            header('Location: ' . BASE_URL . 'home/index');
            exit;
        }
    }
}
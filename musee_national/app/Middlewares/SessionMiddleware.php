<?php
namespace App\Middlewares;

class SessionMiddleware {
    
    /**
     * Vérifie si la session est expirée
     */
    public static function check() {
        // Ne pas vérifier pour les pages d'authentification
        $url = $_GET['url'] ?? '';
        if (strpos($url, 'auth/') === 0 || strpos($url, 'api/') === 0) {
            return true;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            return true;
        }
        
        // Vérifier le temps d'inactivité
        $lastActivity = $_SESSION['last_activity'] ?? time();
        $inactiveTime = time() - $lastActivity;
        
        // Si inactif plus de SESSION_TIMEOUT secondes (1 heure)
        if ($inactiveTime > SESSION_TIMEOUT) {
            // Détruire la session
            $_SESSION = array();
            session_destroy();
            
            // Rediriger vers la page d'accueil (au lieu de la page de login)
            header('Location: ' . BASE_URL . 'home/index?timeout=1');
            exit;
        }
        
        // Mettre à jour le temps d'activité
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Vérifie si la session va expirer bientôt (pour afficher un avertissement)
     */
    public static function willExpireSoon() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_activity'])) {
            return false;
        }
        
        $inactiveTime = time() - $_SESSION['last_activity'];
        return $inactiveTime > (SESSION_TIMEOUT - SESSION_WARNING);
    }
    
    /**
     * Récupère le temps restant avant expiration
     */
    public static function getRemainingTime() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_activity'])) {
            return 0;
        }
        
        $inactiveTime = time() - $_SESSION['last_activity'];
        $remaining = SESSION_TIMEOUT - $inactiveTime;
        return $remaining > 0 ? $remaining : 0;
    }
}
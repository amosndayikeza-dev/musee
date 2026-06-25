<?php
namespace App\Services;

use App\Core\Database;

class NotificationService {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Ajoute une notification
     */
    public function add($userId, $type, $titre, $message, $lien = null) {
        $sql = "INSERT INTO notifications (utilisateur_id, type, titre, message, lien) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $type, $titre, $message, $lien]);
    }

    /**
     * Récupère les notifications non lues
     */
    public function getUnread($userId, $limit = 10) {
        $sql = "SELECT * FROM notifications WHERE utilisateur_id = ? AND est_lu = 0 ORDER BY date_creation DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère toutes les notifications
     */
    public function getAll($userId, $limit = 50) {
        $sql = "SELECT * FROM notifications WHERE utilisateur_id = ? ORDER BY date_creation DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Marque une notification comme lue
     */
    public function markAsRead($notificationId, $userId) {
        $sql = "UPDATE notifications SET est_lu = 1 WHERE id = ? AND utilisateur_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$notificationId, $userId]);
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllAsRead($userId) {
        $sql = "UPDATE notifications SET est_lu = 1 WHERE utilisateur_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }

    /**
     * Compte les notifications non lues
     */
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) as total FROM notifications WHERE utilisateur_id = ? AND est_lu = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch()->total ?? 0;
    }
}
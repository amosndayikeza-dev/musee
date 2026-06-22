<?php
namespace App\Services;

use App\Core\Database;

class AuditService {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Enregistre une action dans le journal d'audit
     */
    public function log($action, $table = null, $recordId = null, $oldData = null, $newData = null) {
        $userId = $_SESSION['user_id'] ?? null;
        $email = $_SESSION['user_email'] ?? 'anonyme';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        $sql = "INSERT INTO audit_logs (utilisateur_id, email, action, table_cible, enregistrement_id, 
                                         anciennes_valeurs, nouvelles_valeurs, ip_adresse) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            $userId,
            $email,
            $action,
            $table,
            $recordId,
            $oldData ? json_encode($oldData) : null,
            $newData ? json_encode($newData) : null,
            $ip
        ]);
    }

    /**
     * Récupère les logs d'audit
     */
    public function getLogs($limit = 100, $filters = []) {
        $sql = "SELECT * FROM audit_logs WHERE 1=1";
        $params = [];
        
        if (!empty($filters['utilisateur_id'])) {
            $sql .= " AND utilisateur_id = ?";
            $params[] = $filters['utilisateur_id'];
        }
        if (!empty($filters['action'])) {
            $sql .= " AND action LIKE ?";
            $params[] = '%' . $filters['action'] . '%';
        }
        if (!empty($filters['table'])) {
            $sql .= " AND table_cible = ?";
            $params[] = $filters['table'];
        }
        if (!empty($filters['date_debut'])) {
            $sql .= " AND date_action >= ?";
            $params[] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $sql .= " AND date_action <= ?";
            $params[] = $filters['date_fin'];
        }
        
        $sql .= " ORDER BY date_action DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
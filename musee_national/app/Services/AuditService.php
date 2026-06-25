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
    /**
 * Récupère les logs d'audit avec filtres et pagination
 */
public function getLogs($limit = 100, $filters = []) {
    $sql = "SELECT * FROM audit_logs WHERE 1=1";
    $params = [];
    
    if (!empty($filters['utilisateur_id'])) {
        $sql .= " AND utilisateur_id = :utilisateur_id";
        $params[':utilisateur_id'] = $filters['utilisateur_id'];
    }
    if (!empty($filters['action'])) {
        $sql .= " AND action LIKE :action";
        $params[':action'] = '%' . $filters['action'] . '%';
    }
    if (!empty($filters['table'])) {
        $sql .= " AND table_cible = :table_cible";
        $params[':table_cible'] = $filters['table'];
    }
    if (!empty($filters['date_debut'])) {
        $sql .= " AND date_action >= :date_debut";
        $params[':date_debut'] = $filters['date_debut'];
    }
    if (!empty($filters['date_fin'])) {
        $sql .= " AND date_action <= :date_fin";
        $params[':date_fin'] = $filters['date_fin'];
    }
    
    $sql .= " ORDER BY date_action DESC LIMIT :limit";
    
    $stmt = $this->db->prepare($sql);
    
    // Lier les paramètres dynamiques
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    // Lier LIMIT avec PDO::PARAM_INT (obligatoire pour LIMIT)
    $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll();
}
}
<?php
namespace App\Models;

use App\Core\Model;

class RestaurationModel extends Model {
    protected $table = 'restauration';

    /**
     * Récupère les restaurations avec filtres
     */
    public function getWithFilters($filters = []) {
        $sql = "SELECT r.*, o.titre as oeuvre_titre, o.statut as oeuvre_statut
                FROM restauration r
                JOIN oeuvre o ON r.oeuvre_id = o.id
                WHERE 1=1";
        $params = [];
        
        // Filtre par mot-clé (responsable, titre de l'œuvre, description)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (r.responsable LIKE :keyword OR o.titre LIKE :keyword OR r.description LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        // Filtre par œuvre
        if (!empty($filters['oeuvre_id'])) {
            $sql .= " AND r.oeuvre_id = :oeuvre_id";
            $params[':oeuvre_id'] = $filters['oeuvre_id'];
        }
        
        // Filtre par statut (en cours ou terminée)
        if (!empty($filters['statut'])) {
            if ($filters['statut'] === 'en cours') {
                $sql .= " AND (r.date_fin IS NULL OR r.date_fin > CURDATE())";
            } elseif ($filters['statut'] === 'terminée') {
                $sql .= " AND r.date_fin IS NOT NULL AND r.date_fin <= CURDATE()";
            }
        }
        
        // Filtre par période
        if (!empty($filters['date_debut'])) {
            $sql .= " AND r.date_debut >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $sql .= " AND r.date_fin <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }
        
        $sql .= " ORDER BY r.date_debut DESC";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Recherche par mot-clé
     */
    public function search($keyword) {
        $sql = "SELECT r.*, o.titre as oeuvre_titre 
                FROM restauration r
                JOIN oeuvre o ON r.oeuvre_id = o.id
                WHERE r.responsable LIKE :keyword OR o.titre LIKE :keyword OR r.description LIKE :keyword
                ORDER BY r.date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $search]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère une restauration avec les détails de l'œuvre
     */
    public function getWithOeuvre($id) {
        $sql = "SELECT r.*, o.titre as oeuvre_titre, o.statut as oeuvre_statut
                FROM restauration r
                JOIN oeuvre o ON r.oeuvre_id = o.id
                WHERE r.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Trouve une restauration active pour une œuvre
     */
    public function findActiveRestaurationByOeuvre($oeuvreId) {
        $stmt = $this->db->prepare("SELECT * FROM restauration WHERE oeuvre_id = :oeuvre_id AND (date_fin IS NULL OR date_fin > CURDATE())");
        $stmt->execute([':oeuvre_id' => $oeuvreId]);
        return $stmt->fetch();
    }

    /**
     * Récupère les restaurations en cours
     */
    public function getRestaurationEnCours() {
        $sql = "SELECT r.*, o.titre as oeuvre_titre 
                FROM restauration r
                JOIN oeuvre o ON r.oeuvre_id = o.id
                WHERE r.date_fin IS NULL OR r.date_fin > CURDATE()
                ORDER BY r.date_debut ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Terminer une restauration
     */
    public function terminerRestauration($id) {
        $stmt = $this->db->prepare("UPDATE restauration SET date_fin = CURDATE() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Récupère le coût total des restaurations
     */
    public function getCoutTotal() {
        $result = $this->db->query("SELECT SUM(cout) as total FROM restauration");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Récupère le coût total par œuvre
     */
    public function getCoutTotalParOeuvre($oeuvreId) {
        $stmt = $this->db->prepare("SELECT SUM(cout) as total FROM restauration WHERE oeuvre_id = :oeuvre_id");
        $stmt->execute([':oeuvre_id' => $oeuvreId]);
        return $stmt->fetch()->total ?? 0;
    }

    /**
     * Compter le nombre total de restaurations
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM restauration");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compter les restaurations en cours
     */
    public function countEnCours() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM restauration WHERE date_fin IS NULL OR date_fin > CURDATE()");
        return $result->fetch()->total ?? 0;
    }

    /**
 * Récupère les restaurations avec filtres et pagination
 */
public function getWithFiltersPaginated($filters = [], $limit = 10, $offset = 0) {
    $sql = "SELECT r.*, o.titre as oeuvre_titre 
            FROM restauration r
            JOIN oeuvre o ON r.oeuvre_id = o.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (r.responsable LIKE :keyword OR o.titre LIKE :keyword OR r.description LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['statut'])) {
        if ($filters['statut'] === 'en cours') {
            $sql .= " AND (r.date_fin IS NULL OR r.date_fin > CURDATE())";
        } elseif ($filters['statut'] === 'terminée') {
            $sql .= " AND r.date_fin IS NOT NULL AND r.date_fin <= CURDATE()";
        }
    }
    if (!empty($filters['oeuvre_id'])) {
        $sql .= " AND r.oeuvre_id = :oeuvre_id";
        $params[':oeuvre_id'] = $filters['oeuvre_id'];
    }
    
    $sql .= " ORDER BY r.date_debut DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Compte le nombre de restaurations avec filtres
 */
public function countWithFilters($filters = []) {
    $sql = "SELECT COUNT(*) as total 
            FROM restauration r
            JOIN oeuvre o ON r.oeuvre_id = o.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (r.responsable LIKE :keyword OR o.titre LIKE :keyword OR r.description LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['statut'])) {
        if ($filters['statut'] === 'en cours') {
            $sql .= " AND (r.date_fin IS NULL OR r.date_fin > CURDATE())";
        } elseif ($filters['statut'] === 'terminée') {
            $sql .= " AND r.date_fin IS NOT NULL AND r.date_fin <= CURDATE()";
        }
    }
    if (!empty($filters['oeuvre_id'])) {
        $sql .= " AND r.oeuvre_id = :oeuvre_id";
        $params[':oeuvre_id'] = $filters['oeuvre_id'];
    }
    
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return $stmt->fetch()->total ?? 0;
}
}
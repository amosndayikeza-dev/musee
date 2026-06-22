<?php
namespace App\Models;

use App\Core\Model;

class PretModel extends Model {
    protected $table = 'prets';

    /**
     * Récupère les prêts avec filtres
     */
    public function getWithFilters($filters = []) {
        $sql = "SELECT p.*, o.titre as oeuvre_titre 
                FROM prets p
                JOIN oeuvre o ON p.oeuvre_id = o.id
                WHERE 1=1";
        $params = [];
        
        // Filtre par mot-clé (emprunteur, titre de l'œuvre)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (p.emprunteur LIKE :keyword OR o.titre LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        // Filtre par statut
        if (!empty($filters['statut'])) {
            $sql .= " AND p.statut = :statut";
            $params[':statut'] = $filters['statut'];
        }
        
        // Filtre par œuvre
        if (!empty($filters['oeuvre_id'])) {
            $sql .= " AND p.oeuvre_id = :oeuvre_id";
            $params[':oeuvre_id'] = $filters['oeuvre_id'];
        }
        
        // Filtre par période
        if (!empty($filters['date_debut'])) {
            $sql .= " AND p.date_debut >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $sql .= " AND p.date_fin <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }
        
        $sql .= " ORDER BY p.date_debut DESC";
        
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
        $sql = "SELECT p.*, o.titre as oeuvre_titre 
                FROM prets p
                JOIN oeuvre o ON p.oeuvre_id = o.id
                WHERE p.emprunteur LIKE :keyword OR o.titre LIKE :keyword
                ORDER BY p.date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $search]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un prêt avec les détails de l'œuvre
     */
    public function getWithOeuvre($id) {
        $sql = "SELECT p.*, o.titre as oeuvre_titre, o.statut as oeuvre_statut
                FROM prets p
                JOIN oeuvre o ON p.oeuvre_id = o.id
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Trouve un prêt actif pour une œuvre
     */
    public function findActivePretByOeuvre($oeuvreId) {
        $stmt = $this->db->prepare("SELECT * FROM prets WHERE oeuvre_id = :oeuvre_id AND statut = 'en cours'");
        $stmt->execute([':oeuvre_id' => $oeuvreId]);
        return $stmt->fetch();
    }

    /**
     * Récupère les prêts en cours
     */
    public function getPretsEnCours() {
        $sql = "SELECT p.*, o.titre as oeuvre_titre 
                FROM prets p
                JOIN oeuvre o ON p.oeuvre_id = o.id
                WHERE p.statut = 'en cours' AND p.date_fin >= CURDATE()
                ORDER BY p.date_fin ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Récupère les prêts en retard
     */
    public function getPretsRetard() {
        $sql = "SELECT p.*, o.titre as oeuvre_titre, DATEDIFF(CURDATE(), p.date_fin) as jours_retard
                FROM prets p
                JOIN oeuvre o ON p.oeuvre_id = o.id
                WHERE p.statut = 'en cours' AND p.date_fin < CURDATE()
                ORDER BY p.date_fin ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Terminer un prêt (le marquer comme retourné)
     */
    public function terminerPret($id) {
        $stmt = $this->db->prepare("UPDATE prets SET statut = 'retourné' WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Compter le nombre total de prêts
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM prets");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compter les prêts en cours
     */
    public function countEnCours() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM prets WHERE statut = 'en cours'");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compter les prêts en retard
     */
    public function countRetard() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM prets WHERE statut = 'en cours' AND date_fin < CURDATE()");
        return $result->fetch()->total ?? 0;
    }


    /**
 * Récupère les prêts avec filtres et pagination
 */
public function getWithFiltersPaginated($filters = [], $limit = 10, $offset = 0) {
    $sql = "SELECT p.*, o.titre as oeuvre_titre 
            FROM prets p
            JOIN oeuvre o ON p.oeuvre_id = o.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (p.emprunteur LIKE :keyword OR o.titre LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['statut'])) {
        $sql .= " AND p.statut = :statut";
        $params[':statut'] = $filters['statut'];
    }
    if (!empty($filters['oeuvre_id'])) {
        $sql .= " AND p.oeuvre_id = :oeuvre_id";
        $params[':oeuvre_id'] = $filters['oeuvre_id'];
    }
    
    $sql .= " ORDER BY p.date_debut DESC LIMIT :limit OFFSET :offset";
    
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
 * Compte le nombre de prêts avec filtres
 */
public function countWithFilters($filters = []) {
    $sql = "SELECT COUNT(*) as total 
            FROM prets p
            JOIN oeuvre o ON p.oeuvre_id = o.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (p.emprunteur LIKE :keyword OR o.titre LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['statut'])) {
        $sql .= " AND p.statut = :statut";
        $params[':statut'] = $filters['statut'];
    }
    if (!empty($filters['oeuvre_id'])) {
        $sql .= " AND p.oeuvre_id = :oeuvre_id";
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
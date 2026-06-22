<?php
namespace App\Models;

use App\Core\Model;

class MouvementModel extends Model {
    protected $table = 'mouvement';

    /**
     * Récupère les mouvements avec filtres
     */
    public function getWithFilters($filters = []) {
        $sql = "SELECT m.*, o.titre as oeuvre_titre, o.statut as oeuvre_statut
                FROM mouvement m
                JOIN oeuvre o ON m.oeuvre_id = o.id
                WHERE 1=1";
        $params = [];
        
        // Filtre par mot-clé (provenance, destination, responsable, titre de l'œuvre)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (m.provenance LIKE :keyword OR m.destination LIKE :keyword OR m.responsable LIKE :keyword OR o.titre LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        // Filtre par œuvre
        if (!empty($filters['oeuvre_id'])) {
            $sql .= " AND m.oeuvre_id = :oeuvre_id";
            $params[':oeuvre_id'] = $filters['oeuvre_id'];
        }
        
        // Filtre par type
        if (!empty($filters['type'])) {
            $sql .= " AND m.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        // Filtre par période
        if (!empty($filters['date_debut'])) {
            $sql .= " AND m.date >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $sql .= " AND m.date <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }
        
        $sql .= " ORDER BY m.date DESC";
        
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
        $sql = "SELECT m.*, o.titre as oeuvre_titre 
                FROM mouvement m
                JOIN oeuvre o ON m.oeuvre_id = o.id
                WHERE m.provenance LIKE :keyword OR m.destination LIKE :keyword OR m.responsable LIKE :keyword OR o.titre LIKE :keyword
                ORDER BY m.date DESC";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $search]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un mouvement avec les détails de l'œuvre
     */
    public function getWithOeuvre($id) {
        $sql = "SELECT m.*, o.titre as oeuvre_titre, o.statut as oeuvre_statut
                FROM mouvement m
                JOIN oeuvre o ON m.oeuvre_id = o.id
                WHERE m.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupère les mouvements récents
     */
    public function getMouvementsRecents($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT m.*, o.titre as oeuvre_titre 
            FROM mouvement m
            JOIN oeuvre o ON m.oeuvre_id = o.id
            ORDER BY m.date DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les mouvements par période
     */
    public function getMouvementsParPeriode($dateDebut, $dateFin) {
        $stmt = $this->db->prepare("
            SELECT m.*, o.titre as oeuvre_titre 
            FROM mouvement m
            JOIN oeuvre o ON m.oeuvre_id = o.id
            WHERE m.date BETWEEN :date_debut AND :date_fin
            ORDER BY m.date DESC
        ");
        $stmt->execute([
            ':date_debut' => $dateDebut,
            ':date_fin' => $dateFin
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Compter les entrées
     */
    public function countEntrees() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM mouvement WHERE type = 'entrée'");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compter les sorties
     */
    public function countSorties() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM mouvement WHERE type = 'sortie'");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compter le nombre total de mouvements
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM mouvement");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Récupère les statistiques des mouvements par mois
     */
    public function getStatistiquesMouvements() {
        $sql = "SELECT type, COUNT(*) as total, DATE_FORMAT(date, '%Y-%m') as mois
                FROM mouvement
                GROUP BY type, mois
                ORDER BY mois DESC, type";
        return $this->db->query($sql)->fetchAll();
    }

    /**
 * Récupère les mouvements avec filtres et pagination
 */
public function getWithFiltersPaginated($filters = [], $limit = 10, $offset = 0) {
    $sql = "SELECT m.*, o.titre as oeuvre_titre 
            FROM mouvement m
            JOIN oeuvre o ON m.oeuvre_id = o.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (m.provenance LIKE :keyword OR m.destination LIKE :keyword OR m.responsable LIKE :keyword OR o.titre LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['type'])) {
        $sql .= " AND m.type = :type";
        $params[':type'] = $filters['type'];
    }
    if (!empty($filters['oeuvre_id'])) {
        $sql .= " AND m.oeuvre_id = :oeuvre_id";
        $params[':oeuvre_id'] = $filters['oeuvre_id'];
    }
    
    $sql .= " ORDER BY m.date DESC LIMIT :limit OFFSET :offset";
    
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
 * Compte le nombre de mouvements avec filtres
 */
public function countWithFilters($filters = []) {
    $sql = "SELECT COUNT(*) as total 
            FROM mouvement m
            JOIN oeuvre o ON m.oeuvre_id = o.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (m.provenance LIKE :keyword OR m.destination LIKE :keyword OR m.responsable LIKE :keyword OR o.titre LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['type'])) {
        $sql .= " AND m.type = :type";
        $params[':type'] = $filters['type'];
    }
    if (!empty($filters['oeuvre_id'])) {
        $sql .= " AND m.oeuvre_id = :oeuvre_id";
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
<?php
namespace App\Models;

use App\Core\Model;

class AuteurModel extends Model {
    protected $table = 'auteurs';

    /**
     * Génère automatiquement un matricule unique
     * Format : AUT-YYYY-XXX (ex: AUT-2025-001)
     */
    public function generateMatricule() {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT matricule FROM auteurs WHERE matricule LIKE ? ORDER BY id DESC LIMIT 1");
        $stmt->execute(['AUT-' . $year . '-%']);
        $last = $stmt->fetch();
        if ($last) {
            $parts = explode('-', $last->matricule);
            $num = intval($parts[2]) + 1;
            $num = str_pad($num, 3, '0', STR_PAD_LEFT);
        } else {
            $num = '001';
        }
        return 'AUT-' . $year . '-' . $num;
    }

    /**
     * Recherche par mot-clé (CORRIGÉ)
     */
    public function search($keyword) {
        // Utiliser des paramètres nommés pour éviter les confusions
        $sql = "SELECT * FROM auteurs 
                WHERE nom LIKE :keyword 
                OR prenom LIKE :keyword 
                OR nationalite LIKE :keyword 
                ORDER BY nom ASC";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $search]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les auteurs par nationalité
     */
    public function getByNationalite($nationalite) {
        $stmt = $this->db->prepare("SELECT * FROM auteurs WHERE nationalite = :nationalite ORDER BY nom ASC");
        $stmt->execute([':nationalite' => $nationalite]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer toutes les nationalités distinctes
     */
    public function getAllNationalites() {
        $stmt = $this->db->query("SELECT DISTINCT nationalite FROM auteurs WHERE nationalite IS NOT NULL AND nationalite != '' ORDER BY nationalite ASC");
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les œuvres d'un auteur
     */
    public function getOeuvres($auteurId) {
        $stmt = $this->db->prepare("SELECT * FROM oeuvre WHERE auteur_id = :auteur_id ORDER BY titre ASC");
        $stmt->execute([':auteur_id' => $auteurId]);
        return $stmt->fetchAll();
    }

    /**
     * Compter le nombre d'auteurs
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM auteurs");
        return $result->fetch()->total ?? 0;
    }

    /**
 * Récupère les auteurs avec filtres et pagination
 */
public function getWithFiltersPaginated($filters = [], $limit = 10, $offset = 0) {
    $sql = "SELECT * FROM auteurs WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (nom LIKE :keyword OR prenom LIKE :keyword OR nationalite LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['nationalite'])) {
        $sql .= " AND nationalite = :nationalite";
        $params[':nationalite'] = $filters['nationalite'];
    }
    
    $sql .= " ORDER BY nom ASC LIMIT :limit OFFSET :offset";
    
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
 * Compte le nombre d'auteurs avec filtres
 */
public function countWithFilters($filters = []) {
    $sql = "SELECT COUNT(*) as total FROM auteurs WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (nom LIKE :keyword OR prenom LIKE :keyword OR nationalite LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['nationalite'])) {
        $sql .= " AND nationalite = :nationalite";
        $params[':nationalite'] = $filters['nationalite'];
    }
    
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return $stmt->fetch()->total ?? 0;
}
}
<?php
namespace App\Models;

use App\Core\Model;
use App\Core\SoftDeleteTrait;

class CategorieModel extends Model {
    
    use SoftDeleteTrait;
    
    protected $table = 'categorie';

    /**
     * Récupère les catégories avec le nombre d'œuvres
     */
    public function getOeuvresCount() {
        $sql = "SELECT c.*, COUNT(o.id) as nb_oeuvres 
                FROM categorie c
                LEFT JOIN oeuvre o ON c.id = o.categorie_id
                GROUP BY c.id
                ORDER BY c.nom ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Recherche par mot-clé
     */
    public function search($keyword) {
        $sql = "SELECT c.*, COUNT(o.id) as nb_oeuvres 
                FROM categorie c
                LEFT JOIN oeuvre o ON c.id = o.categorie_id
                WHERE c.nom LIKE :keyword OR c.description LIKE :keyword
                GROUP BY c.id
                ORDER BY c.nom ASC";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $search]);
        return $stmt->fetchAll();
    }

    /**
     * Trouver une catégorie par son nom
     */
    public function findByName($nom) {
        $stmt = $this->db->prepare("SELECT * FROM categorie WHERE nom = :nom");
        $stmt->execute([':nom' => $nom]);
        return $stmt->fetch();
    }

    /**
     * Récupérer les œuvres d'une catégorie
     */
    public function getOeuvres($categorieId) {
        $stmt = $this->db->prepare("SELECT * FROM oeuvre WHERE categorie_id = :categorie_id ORDER BY titre ASC");
        $stmt->execute([':categorie_id' => $categorieId]);
        return $stmt->fetchAll();
    }

    /**
     * Compter le nombre de catégories
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM categorie");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Récupère les catégories avec filtres et pagination
     */
    public function getWithFiltersPaginated($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT c.*, COUNT(o.id) as nb_oeuvres 
                FROM categorie c
                LEFT JOIN oeuvre o ON c.id = o.categorie_id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['keyword'])) {
            $sql .= " AND (c.nom LIKE :keyword OR c.description LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        $sql .= " GROUP BY c.id ORDER BY c.nom ASC LIMIT :limit OFFSET :offset";
        
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
     * Compte le nombre de catégories avec filtres
     */
    public function countWithFilters($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM categorie WHERE 1=1";
        $params = [];
        
        if (!empty($filters['keyword'])) {
            $sql .= " AND (nom LIKE :keyword OR description LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetch()->total ?? 0;
    }
}
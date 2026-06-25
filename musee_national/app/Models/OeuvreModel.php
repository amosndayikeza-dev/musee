<?php
namespace App\Models;

use App\Core\Model;
use App\Core\SoftDeleteTrait;


class OeuvreModel extends Model {
    use SoftDeleteTrait;
    protected $table = 'oeuvre';

    /**
     * Récupère les œuvres avec filtres (sans pagination)
     * Utilisé pour le web
     */
    public function getWithFilters($filters = []) {
        $sql = "SELECT o.*, a.nom as auteur_nom, a.prenom as auteur_prenom, c.nom as categorie_nom 
                FROM oeuvre o
                LEFT JOIN auteurs a ON o.auteur_id = a.id
                LEFT JOIN categorie c ON o.categorie_id = c.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['keyword'])) {
            $sql .= " AND (o.titre LIKE :keyword OR a.nom LIKE :keyword OR a.prenom LIKE :keyword OR c.nom LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        if (!empty($filters['auteur_id'])) {
            $sql .= " AND o.auteur_id = :auteur_id";
            $params[':auteur_id'] = $filters['auteur_id'];
        }
        if (!empty($filters['categorie_id'])) {
            $sql .= " AND o.categorie_id = :categorie_id";
            $params[':categorie_id'] = $filters['categorie_id'];
        }
        if (!empty($filters['statut'])) {
            $sql .= " AND o.statut = :statut";
            $params[':statut'] = $filters['statut'];
        }
        if (!empty($filters['date_debut'])) {
            $sql .= " AND o.date_creation >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $sql .= " AND o.date_creation <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }
        
        $sql .= " ORDER BY o.id DESC";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les œuvres avec filtres et pagination
     * Utilisé pour l'API
     */
    public function getWithFiltersPaginated($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT o.*, a.nom as auteur_nom, a.prenom as auteur_prenom, c.nom as categorie_nom 
                FROM oeuvre o
                LEFT JOIN auteurs a ON o.auteur_id = a.id
                LEFT JOIN categorie c ON o.categorie_id = c.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['keyword'])) {
            $sql .= " AND (o.titre LIKE :keyword OR a.nom LIKE :keyword OR a.prenom LIKE :keyword OR c.nom LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        if (!empty($filters['auteur_id'])) {
            $sql .= " AND o.auteur_id = :auteur_id";
            $params[':auteur_id'] = $filters['auteur_id'];
        }
        if (!empty($filters['categorie_id'])) {
            $sql .= " AND o.categorie_id = :categorie_id";
            $params[':categorie_id'] = $filters['categorie_id'];
        }
        if (!empty($filters['statut'])) {
            $sql .= " AND o.statut = :statut";
            $params[':statut'] = $filters['statut'];
        }
        
        $sql .= " ORDER BY o.id DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Lier les paramètres dynamiques
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        // Lier LIMIT et OFFSET avec PDO::PARAM_INT
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre total d'œuvres qui correspondent aux filtres
     * Utilisé pour la pagination (API)
     */
    public function countWithFilters($filters = []) {
        // Construction de la requête COUNT
        $sql = "SELECT COUNT(*) as total 
                FROM oeuvre o
                LEFT JOIN auteurs a ON o.auteur_id = a.id
                LEFT JOIN categorie c ON o.categorie_id = c.id
                WHERE 1=1";
        $params = [];
        
        // Ajout des filtres (les mêmes que dans getWithFiltersPaginated)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (o.titre LIKE :keyword OR a.nom LIKE :keyword OR a.prenom LIKE :keyword OR c.nom LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        if (!empty($filters['auteur_id'])) {
            $sql .= " AND o.auteur_id = :auteur_id";
            $params[':auteur_id'] = $filters['auteur_id'];
        }
        if (!empty($filters['categorie_id'])) {
            $sql .= " AND o.categorie_id = :categorie_id";
            $params[':categorie_id'] = $filters['categorie_id'];
        }
        if (!empty($filters['statut'])) {
            $sql .= " AND o.statut = :statut";
            $params[':statut'] = $filters['statut'];
        }
        
        // Pas de LIMIT ni OFFSET ici ! C'est juste un comptage
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        // Retourner le total
        $result = $stmt->fetch();
        return $result->total ?? 0;
    }

    /**
     * Récupère une œuvre avec ses détails (auteur, catégorie)
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT o.*, a.nom as auteur_nom, a.prenom as auteur_prenom, c.nom as categorie_nom 
                FROM oeuvre o
                LEFT JOIN auteurs a ON o.auteur_id = a.id
                LEFT JOIN categorie c ON o.categorie_id = c.id
                WHERE o.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Recherche d'œuvres par mot-clé (recherche rapide)
     */
    public function search($keyword) {
        $sql = "SELECT o.*, a.nom as auteur_nom, a.prenom as auteur_prenom, c.nom as categorie_nom 
                FROM oeuvre o
                LEFT JOIN auteurs a ON o.auteur_id = a.id
                LEFT JOIN categorie c ON o.categorie_id = c.id
                WHERE o.titre LIKE :keyword OR a.nom LIKE :keyword OR a.prenom LIKE :keyword
                ORDER BY o.titre ASC
                LIMIT 20";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $search]);
        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre total d'œuvres
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM oeuvre");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Statistiques par statut
     */
    public function getStatsByStatut() {
        $sql = "SELECT statut, COUNT(*) as total FROM oeuvre GROUP BY statut";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Statistiques par catégorie
     */
    public function getStatsByCategorie() {
        $sql = "SELECT c.nom as categorie, COUNT(o.id) as total 
                FROM categorie c
                LEFT JOIN oeuvre o ON c.id = o.categorie_id
                GROUP BY c.id";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Top auteurs par nombre d'œuvres
     */
    public function getStatsByAuteur($limit = 5) {
        $sql = "SELECT a.nom, a.prenom, COUNT(o.id) as total 
                FROM auteurs a
                LEFT JOIN oeuvre o ON a.id = o.auteur_id
                GROUP BY a.id
                ORDER BY total DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Compte les œuvres sans auteur
     */
    public function countSansAuteur() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM oeuvre WHERE auteur_id IS NULL");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compte les œuvres sans catégorie
     */
    public function countSansCategorie() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM oeuvre WHERE categorie_id IS NULL");
        return $result->fetch()->total ?? 0;
    }
   

        /**
     * Récupère les œuvres avec les informations de l'auteur et de la catégorie
     * Utilisé pour les listes dans le back-office et le front-office
     */
    public function getWithAuteurAndCategorie() {
        $sql = "SELECT o.*, 
                    a.nom as auteur_nom, 
                    a.prenom as auteur_prenom, 
                    c.nom as categorie_nom 
                FROM oeuvre o
                LEFT JOIN auteurs a ON o.auteur_id = a.id
                LEFT JOIN categorie c ON o.categorie_id = c.id
                ORDER BY o.id DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

  
public function archive($id) {
    $stmt = $this->db->prepare("UPDATE oeuvre SET archive = 1 WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Restaure une œuvre archivée
 */
public function unarchive($id) {
    $stmt = $this->db->prepare("UPDATE oeuvre SET archive = 0 WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Récupère les œuvres archivées
 */
public function getArchived() {
    $sql = "SELECT * FROM oeuvre WHERE archive = 1 ORDER BY id DESC";
    return $this->db->query($sql)->fetchAll();
}

/**
 * Récupère les œuvres non archivées
 */
public function getActive() {
    $sql = "SELECT * FROM oeuvre WHERE archive = 0 ORDER BY id DESC";
    return $this->db->query($sql)->fetchAll();
}

// === SOFT DELETE ===

/**
 * Supprime l'élément (soft delete)
 */
public function delete($id) {
    $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Restaure un élément supprimé
 */
public function restore($id) {
    $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Supprime définitivement (hard delete)
 */
public function forceDelete($id) {
    $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Récupère tous les éléments (y compris les supprimés)
 */
public function getAllWithTrashed() {
    $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
    return $stmt->fetchAll();
}

/**
 * Récupère uniquement les éléments supprimés (corbeille)
 */
public function getTrashed() {
    $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC");
    return $stmt->fetchAll();
}

/**
 * Récupère uniquement les éléments actifs (non supprimés)
 */
public function getAll() {
    $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY id DESC");
    return $stmt->fetchAll();
}

/**
 * Récupère un élément par ID (actif ou supprimé)
 */
public function getByIdWithTrashed($id) {
    $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Supprime définitivement toutes les œuvres en corbeille
 */
public function forceDeleteAllTrashed() {
    $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE deleted_at IS NOT NULL");
    return $stmt->execute();
}


}
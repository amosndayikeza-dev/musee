<?php
namespace App\Models;

use App\Core\Model;
use App\Core\SoftDeleteTrait;

class ExpositionModel extends Model {

    use SoftDeleteTrait;
    protected $table = 'expositions';

    /**
     * Récupère les expositions avec filtres
     */
    public function getWithFilters($filters = []) {
        $sql = "SELECT * FROM expositions WHERE 1=1";
        $params = [];
        
        // Filtre par mot-clé
        if (!empty($filters['keyword'])) {
            $sql .= " AND (titre LIKE :keyword OR description LIKE :keyword OR lieu LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        // Filtre par statut
        if (!empty($filters['statut'])) {
            $sql .= " AND statut = :statut";
            $params[':statut'] = $filters['statut'];
        }
        
        // Filtre par période
        if (!empty($filters['date_debut'])) {
            $sql .= " AND date_debut >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $sql .= " AND date_fin <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }
        
        $sql .= " ORDER BY date_debut DESC";
        
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
        $sql = "SELECT * FROM expositions WHERE titre LIKE :keyword OR description LIKE :keyword OR lieu LIKE :keyword ORDER BY date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([':keyword' => $search]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les œuvres d'une exposition
     */
    public function getOeuvres($expositionId) {
        $stmt = $this->db->prepare("
            SELECT o.*, a.nom as auteur_nom 
            FROM oeuvre o
            JOIN exposition_oeuvre eo ON o.id = eo.oeuvre_id
            LEFT JOIN auteurs a ON o.auteur_id = a.id
            WHERE eo.exposition_id = :exposition_id
            ORDER BY o.titre ASC
        ");
        $stmt->execute([':exposition_id' => $expositionId]);
        return $stmt->fetchAll();
    }

    /**
     * Ajouter une œuvre à une exposition
     */
    public function addOeuvre($expositionId, $oeuvreId, $dateArrivee, $dateDepart = null) {
        $stmt = $this->db->prepare("
            INSERT INTO exposition_oeuvre (exposition_id, oeuvre_id, date_arrivee, date_depart)
            VALUES (:exposition_id, :oeuvre_id, :date_arrivee, :date_depart)
        ");
        return $stmt->execute([
            ':exposition_id' => $expositionId,
            ':oeuvre_id' => $oeuvreId,
            ':date_arrivee' => $dateArrivee,
            ':date_depart' => $dateDepart
        ]);
    }

    /**
     * Supprimer une œuvre d'une exposition
     */
    public function removeOeuvre($expositionId, $oeuvreId) {
        $stmt = $this->db->prepare("DELETE FROM exposition_oeuvre WHERE exposition_id = :exposition_id AND oeuvre_id = :oeuvre_id");
        return $stmt->execute([
            ':exposition_id' => $expositionId,
            ':oeuvre_id' => $oeuvreId
        ]);
    }

    /**
     * Supprimer toutes les œuvres d'une exposition
     */
    public function removeAllOeuvres($expositionId) {
        $stmt = $this->db->prepare("DELETE FROM exposition_oeuvre WHERE exposition_id = :exposition_id");
        return $stmt->execute([':exposition_id' => $expositionId]);
    }

    /**
     * Compter les expositions en cours
     */
    public function countEnCours() {
    $result = $this->db->query("SELECT COUNT(*) as total FROM expositions WHERE date_debut <= CURDATE() AND date_fin >= CURDATE()");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compter les expositions à venir
     */
    public function countAvenir() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM expositions WHERE date_debut > CURDATE()");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compter le total des expositions
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM expositions");
        return $result->fetch()->total ?? 0;
    }

    /**
 * Récupère les expositions avec filtres et pagination
 */
public function getWithFiltersPaginated($filters = [], $limit = 10, $offset = 0) {
    $sql = "SELECT * FROM expositions WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (titre LIKE :keyword OR description LIKE :keyword OR lieu LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['statut'])) {
        $sql .= " AND statut = :statut";
        $params[':statut'] = $filters['statut'];
    }
    if (!empty($filters['date_debut'])) {
        $sql .= " AND date_debut >= :date_debut";
        $params[':date_debut'] = $filters['date_debut'];
    }
    if (!empty($filters['date_fin'])) {
        $sql .= " AND date_fin <= :date_fin";
        $params[':date_fin'] = $filters['date_fin'];
    }
    
    $sql .= " ORDER BY date_debut DESC LIMIT :limit OFFSET :offset";
    
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
 * Compte le nombre d'expositions avec filtres
 */
public function countWithFilters($filters = []) {
    $sql = "SELECT COUNT(*) as total FROM expositions WHERE 1=1";
    $params = [];
    
    if (!empty($filters['keyword'])) {
        $sql .= " AND (titre LIKE :keyword OR description LIKE :keyword OR lieu LIKE :keyword)";
        $params[':keyword'] = '%' . $filters['keyword'] . '%';
    }
    if (!empty($filters['statut'])) {
        $sql .= " AND statut = :statut";
        $params[':statut'] = $filters['statut'];
    }
    if (!empty($filters['date_debut'])) {
        $sql .= " AND date_debut >= :date_debut";
        $params[':date_debut'] = $filters['date_debut'];
    }
    if (!empty($filters['date_fin'])) {
        $sql .= " AND date_fin <= :date_fin";
        $params[':date_fin'] = $filters['date_fin'];
    }
    
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return $stmt->fetch()->total ?? 0;
}

    /**
 * Récupère les expositions en cours (actuelles)
 * Une exposition est "en cours" si la date du jour est entre date_debut et date_fin
 */
public function getExpositionsEnCours() {
    $sql = "SELECT * FROM expositions 
            WHERE date_debut <= CURDATE() 
            AND date_fin >= CURDATE() 
            ORDER BY date_debut ASC";
    return $this->db->query($sql)->fetchAll();
}

/**
 * Récupère les expositions à venir (futures)
 * Une exposition est "à venir" si date_debut > date du jour
 */
public function getExpositionsAvenir() {
    $sql = "SELECT * FROM expositions 
            WHERE date_debut > CURDATE() 
            ORDER BY date_debut ASC";
    return $this->db->query($sql)->fetchAll();
}

/**
 * Récupère les expositions terminées (passées)
 * Une exposition est "terminée" si date_fin < date du jour
 */
public function getExpositionsTerminees() {
    $sql = "SELECT * FROM expositions 
            WHERE date_fin < CURDATE() 
            ORDER BY date_fin DESC";
    return $this->db->query($sql)->fetchAll();
}

// Ajouter une méthode pour mettre à jour la photo
public function updatePhoto($id, $photoPath) {
    $stmt = $this->db->prepare("UPDATE expositions SET photo = ? WHERE id = ?");
    return $stmt->execute([$photoPath, $id]);
}


}
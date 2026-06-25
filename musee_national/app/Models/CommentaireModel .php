<?php
namespace App\Models;

use App\Core\Model;

class CommentaireModel extends Model {
    
    protected $table = 'commentaires';

    /**
     * Ajoute un commentaire sur une œuvre
     */
    public function add($oeuvreId, $utilisateurId, $contenu, $parentId = null) {
        $sql = "INSERT INTO commentaires (oeuvre_id, utilisateur_id, contenu, parent_id) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$oeuvreId, $utilisateurId, $contenu, $parentId]);
    }

    /**
     * Récupère tous les commentaires d'une œuvre
     */
    public function getByOeuvre($oeuvreId) {
        $sql = "SELECT c.*, 
                       u.nom, u.prenom, u.email, u.photo,
                       (SELECT COUNT(*) FROM commentaires WHERE parent_id = c.id AND est_approuve = 1) as reponses
                FROM commentaires c
                LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                WHERE c.oeuvre_id = ? AND c.parent_id IS NULL AND c.est_approuve = 1
                ORDER BY c.date_creation DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$oeuvreId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère les réponses d'un commentaire parent
     */
    public function getReplies($commentaireId) {
        $sql = "SELECT c.*, 
                       u.nom, u.prenom, u.email, u.photo
                FROM commentaires c
                LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                WHERE c.parent_id = ? AND c.est_approuve = 1
                ORDER BY c.date_creation ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$commentaireId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un commentaire par son ID
     */
    public function getById($id) {
        $sql = "SELECT c.*, 
                       u.nom, u.prenom, u.email, u.photo
                FROM commentaires c
                LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                WHERE c.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Supprime un commentaire (physiquement)
     */
    public function delete($id) {
        // Supprimer d'abord les réponses
        $stmt = $this->db->prepare("DELETE FROM commentaires WHERE parent_id = ?");
        $stmt->execute([$id]);
        
        // Supprimer le commentaire parent
        $stmt = $this->db->prepare("DELETE FROM commentaires WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Masque un commentaire (le rend invisible)
     */
    public function hide($id) {
        $stmt = $this->db->prepare("UPDATE commentaires SET est_approuve = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Approuve un commentaire
     */
    public function approve($id) {
        $stmt = $this->db->prepare("UPDATE commentaires SET est_approuve = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupère tous les commentaires en attente d'approbation (admin)
     */
    public function getPending() {
        $sql = "SELECT c.*, 
                       u.nom, u.prenom, u.email,
                       o.titre as oeuvre_titre
                FROM commentaires c
                LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                LEFT JOIN oeuvre o ON c.oeuvre_id = o.id
                WHERE c.est_approuve = 0 AND c.parent_id IS NULL
                ORDER BY c.date_creation ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Récupère les derniers commentaires (pour widget)
     */
    public function getRecent($limit = 5) {
        $sql = "SELECT c.*, 
                       u.nom, u.prenom,
                       o.titre as oeuvre_titre
                FROM commentaires c
                LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                LEFT JOIN oeuvre o ON c.oeuvre_id = o.id
                WHERE c.est_approuve = 1 AND c.parent_id IS NULL
                ORDER BY c.date_creation DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre de commentaires par œuvre
     */
    public function countByOeuvre($oeuvreId) {
        $sql = "SELECT COUNT(*) as total FROM commentaires WHERE oeuvre_id = ? AND est_approuve = 1 AND parent_id IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$oeuvreId]);
        $result = $stmt->fetch();
        return $result ? $result->total : 0;
    }

    /**
     * Récupère tous les commentaires (admin)
     */
    public function getAll($limit = 100) {
        $sql = "SELECT c.*, 
                       u.nom, u.prenom, u.email,
                       o.titre as oeuvre_titre
                FROM commentaires c
                LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                LEFT JOIN oeuvre o ON c.oeuvre_id = o.id
                WHERE c.parent_id IS NULL
                ORDER BY c.date_creation DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Supprime tous les commentaires d'une œuvre
     */
    public function deleteByOeuvre($oeuvreId) {
        $stmt = $this->db->prepare("DELETE FROM commentaires WHERE oeuvre_id = ?");
        return $stmt->execute([$oeuvreId]);
    }

    /**
     * Vérifie si un utilisateur peut modifier/supprimer un commentaire
     */
    public function canModify($commentaireId, $utilisateurId, $role = 'visiteur') {
        $commentaire = $this->getById($commentaireId);
        if (!$commentaire) return false;
        
        // Admin peut tout faire
        if ($role === 'admin') return true;
        
        // L'utilisateur peut modifier son propre commentaire
        return $commentaire->utilisateur_id == $utilisateurId;
    }
}
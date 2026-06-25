<?php
namespace App\Models;

use App\Core\Model;
use App\Core\SoftDeleteTrait;

class UtilisateurModel extends Model {
    use SoftDeleteTrait;
    protected $table = 'utilisateurs';

    /**
     * Récupère un utilisateur par son email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Récupère tous les utilisateurs avec leurs rôles
     */
    public function getAllWithRoles() {
        $stmt = $this->db->query("SELECT * FROM utilisateurs ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }

    /**
     * Crée un nouvel utilisateur avec mot de passe hashé
     */
    public function create($data) {
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }

    /**
     * Met à jour un utilisateur (sans toucher au mot de passe si non fourni)
     */
    public function updateUser($id, $data) {
        if (empty($data['mot_de_passe'])) {
            unset($data['mot_de_passe']);
        } else {
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }
        return $this->update($id, $data);
    }

    /**
     * Met à jour le dernier accès
     */
    public function updateLastAccess($userId) {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET dernier_acces = NOW() WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    /**
     * Vérifie si un email existe déjà (hors utilisateur courant)
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM utilisateurs WHERE email = ?";
        $params = [$email];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()->total > 0;
    }

    /**
     * Compte le nombre total d'utilisateurs
     */
    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Compte le nombre d'administrateurs
     */
    public function countAdmins() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'admin'");
        return $result->fetch()->total ?? 0;
    }

    /**
     * Recherche des utilisateurs
     */
    public function search($keyword) {
        $sql = "SELECT * FROM utilisateurs 
                WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? 
                ORDER BY date_creation DESC";
        $stmt = $this->db->prepare($sql);
        $search = '%' . $keyword . '%';
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll();
    }


    /**
     * Ajoute une entrée dans l'historique des connexions
     */
    public function logConnexion($utilisateurId, $email, $statut = 'succès') {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $sql = "INSERT INTO historique_connexions (utilisateur_id, email, ip_adresse, user_agent, statut) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$utilisateurId, $email, $ip, $userAgent, $statut]);
    }

    /**
     * Récupère l'historique des connexions d'un utilisateur
     */
    public function getHistoriqueConnexions($utilisateurId, $limit = 20) {
        $sql = "SELECT * FROM historique_connexions 
                WHERE utilisateur_id = ? 
                ORDER BY date_connexion DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $utilisateurId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère toutes les connexions (pour l'admin)
     */
    public function getAllConnexions($limit = 50) {
        $sql = "SELECT h.*, u.nom, u.prenom 
                FROM historique_connexions h
                JOIN utilisateurs u ON h.utilisateur_id = u.id
                ORDER BY h.date_connexion DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Compte les tentatives de connexion échouées (pour le verrouillage)
     */
    public function countFailedAttempts($email) {
        $sql = "SELECT COUNT(*) as total 
                FROM historique_connexions 
                WHERE email = ? AND statut = 'échec' 
                AND date_connexion > DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch()->total ?? 0;
    }
   
     /**
     * Met à jour le profil d'un utilisateur
     */
    public function updateProfil($id, $data) {
        // Ne pas permettre la modification du rôle ou du mot de passe ici
        $allowedFields = ['nom', 'prenom', 'email', 'telephone', 'photo', 'biographie', 
                          'adresse', 'ville', 'code_postal', 'pays', 'date_naissance', 'genre'];
        
        $updateData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updateData[$key] = $value;
            }
        }
        
        if (empty($updateData)) {
            return false;
        }
        
        return $this->update($id, $updateData);
    }

    /**
     * Change le mot de passe d'un utilisateur
     */
    public function changePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    /**
     * Vérifie si un utilisateur peut modifier un autre utilisateur
     */
    public function canModify($currentUserId, $targetUserId) {
        // Un admin peut modifier tout le monde
        if ($_SESSION['role'] === 'admin') {
            return true;
        }
        // Un utilisateur ne peut modifier que son propre profil
        return $currentUserId == $targetUserId;
    }

    /**
     * Récupère les statistiques d'un utilisateur (nb d'actions, dernière connexion, etc.)
     */
    public function getUserStats($userId) {
        $stats = [];
        
        // Nombre de connexions
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM historique_connexions WHERE utilisateur_id = ?");
        $stmt->execute([$userId]);
        $stats['connexions'] = $stmt->fetch()->total ?? 0;
        
        // Dernière connexion
        $stmt = $this->db->prepare("SELECT date_connexion FROM historique_connexions WHERE utilisateur_id = ? ORDER BY date_connexion DESC LIMIT 1");
        $stmt->execute([$userId]);
        $stats['derniere_connexion'] = $stmt->fetch()->date_connexion ?? null;
        
        return $stats;
    }

    /**
 * Génère un token de réinitialisation de mot de passe
 */
public function generateResetToken($email) {
    // Supprimer les anciens tokens
    $stmt = $this->db->prepare("DELETE FROM reset_password_tokens WHERE email = ?");
    $stmt->execute([$email]);
    
    $token = bin2hex(random_bytes(32));
    $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $stmt = $this->db->prepare("INSERT INTO reset_password_tokens (email, token, date_expiration) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, $expiration]);
    
    return $token;
}

/**
 * Vérifie un token de réinitialisation
 */
public function verifyResetToken($token) {
    $stmt = $this->db->prepare("SELECT * FROM reset_password_tokens WHERE token = ? AND utilise = 0 AND date_expiration > NOW()");
    $stmt->execute([$token]);
    return $stmt->fetch();
}

    /**
     * Marque un token comme utilisé et met à jour le mot de passe
     */
    public function resetPassword($token, $newPassword) {
        $tokenData = $this->verifyResetToken($token);
        if (!$tokenData) {
            return false;
        }
        
        // Mettre à jour le mot de passe
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE email = ?");
        $stmt->execute([$hash, $tokenData->email]);
        
        // Marquer le token comme utilisé
        $stmt = $this->db->prepare("UPDATE reset_password_tokens SET utilise = 1 WHERE token = ?");
        $stmt->execute([$token]);
        
        return true;
    }
}


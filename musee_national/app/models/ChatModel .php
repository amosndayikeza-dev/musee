<?php
namespace App\Models;

use App\Core\Model;

class ChatModel extends Model {
    
    protected $table = 'messages_chat';

    /**
     * Récupère la liste des utilisateurs avec qui l'utilisateur a échangé
     * (ou tous les utilisateurs sauf lui-même)
     */
    public function getUsers($userId) {
        // Récupère les utilisateurs avec qui l'utilisateur a déjà discuté
        $sql = "SELECT DISTINCT 
                    u.id, u.nom, u.prenom, u.email, u.photo,
                    (SELECT COUNT(*) FROM messages_chat 
                     WHERE destinataire_id = ? AND expediteur_id = u.id AND est_lu = 0) as non_lus
                FROM utilisateurs u
                INNER JOIN messages_chat m ON (m.expediteur_id = u.id OR m.destinataire_id = u.id)
                WHERE (m.expediteur_id = ? OR m.destinataire_id = ?)
                AND u.id != ?
                ORDER BY u.nom ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $userId, $userId, $userId]);
        $result = $stmt->fetchAll();
        
        // Si aucun échange, retourner tous les autres utilisateurs
        if (empty($result)) {
            $sql = "SELECT id, nom, prenom, email, photo, 0 as non_lus 
                    FROM utilisateurs WHERE id != ? ORDER BY nom ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetchAll();
        }
        
        return $result;
    }

    /**
     * Récupère les messages entre deux utilisateurs
     */
    public function getMessages($expediteurId, $destinataireId) {
        // Marquer les messages comme lus
        $sql = "UPDATE messages_chat SET est_lu = 1 
                WHERE expediteur_id = ? AND destinataire_id = ? AND est_lu = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$destinataireId, $expediteurId]);
        
        // Récupérer les messages
        $sql = "SELECT m.*, 
                       u_exp.nom as expediteur_nom, u_exp.prenom as expediteur_prenom,
                       u_dest.nom as destinataire_nom, u_dest.prenom as destinataire_prenom
                FROM messages_chat m
                LEFT JOIN utilisateurs u_exp ON m.expediteur_id = u_exp.id
                LEFT JOIN utilisateurs u_dest ON m.destinataire_id = u_dest.id
                WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
                   OR (m.expediteur_id = ? AND m.destinataire_id = ?)
                ORDER BY m.date_envoi ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$expediteurId, $destinataireId, $destinataireId, $expediteurId]);
        return $stmt->fetchAll();
    }

    /**
     * Envoie un message
     */
    public function send($expediteurId, $destinataireId, $message) {
        $sql = "INSERT INTO messages_chat (expediteur_id, destinataire_id, message) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$expediteurId, $destinataireId, $message]);
    }

    /**
     * Compte les messages non lus pour un utilisateur donné
     */
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) as total FROM messages_chat WHERE destinataire_id = ? AND est_lu = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ? $result->total : 0;
    }

    /**
     * Marque tous les messages d'un expéditeur comme lus
     */
    public function markAsRead($expediteurId, $destinataireId) {
        $sql = "UPDATE messages_chat SET est_lu = 1 
                WHERE expediteur_id = ? AND destinataire_id = ? AND est_lu = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$expediteurId, $destinataireId]);
    }

    /**
     * Supprime un message (admin seulement)
     */
    public function deleteMessage($messageId) {
        $stmt = $this->db->prepare("DELETE FROM messages_chat WHERE id = ?");
        return $stmt->execute([$messageId]);
    }

    /**
     * Supprime toute la conversation entre deux utilisateurs
     */
    public function deleteConversation($userId1, $userId2) {
        $sql = "DELETE FROM messages_chat 
                WHERE (expediteur_id = ? AND destinataire_id = ?)
                   OR (expediteur_id = ? AND destinataire_id = ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId1, $userId2, $userId2, $userId1]);
    }
}
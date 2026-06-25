<?php
namespace App\Models;

use App\Core\Model;

class ChatModel extends Model {
    protected $table = 'messages_chat';

    public function getUsers($userId) {
        // Récupère les utilisateurs avec qui on a déjà discuté,
        // sinon tous les autres utilisateurs.
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

        if (empty($result)) {
            $sql = "SELECT id, nom, prenom, email, photo, 0 as non_lus 
                    FROM utilisateurs WHERE id != ? ORDER BY nom ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetchAll();
        }
        return $result;
    }

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
    public function send($expediteurId, $destinataireId, $message, $fichier = null, $typeFichier = null) {
        $sql = "INSERT INTO messages_chat (expediteur_id, destinataire_id, message, fichier, type_fichier) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$expediteurId, $destinataireId, $message, $fichier, $typeFichier]);
    }
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) as total FROM messages_chat WHERE destinataire_id = ? AND est_lu = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch()->total ?? 0;
    }

    // Autres méthodes (markAsRead, deleteMessage, etc.) si besoin
}
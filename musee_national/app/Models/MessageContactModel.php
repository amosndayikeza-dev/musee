<?php
namespace App\Models;

use App\Core\Model;

class MessageContactModel extends Model {
    protected $table = 'messages_contact';

    public function insert($data) {
        $sql = "INSERT INTO {$this->table} (nom, email, sujet, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['nom'], $data['email'], $data['sujet'], $data['message']]);
    }

    public function getAll($limit = 50) {
        $sql = "SELECT * FROM {$this->table} ORDER BY date_envoi DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUnreadCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE est_lu = 0");
        return $stmt->fetch()->total ?? 0;
    }

    public function markAsRead($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET est_lu = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function markAsReplied($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET repondu = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
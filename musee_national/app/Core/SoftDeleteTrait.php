<?php
namespace App\Core;

trait SoftDeleteTrait {
    

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function restore($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function forceDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function forceDeleteAllTrashed() {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE deleted_at IS NOT NULL");
        return $stmt->execute();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getAllWithTrashed() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getTrashed() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC");
        return $stmt->fetchAll();
    }

    public function getByIdWithTrashed($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
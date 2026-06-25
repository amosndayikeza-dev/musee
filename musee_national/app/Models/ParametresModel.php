<?php
namespace App\Models;

use App\Core\Model;

class ParametresModel extends Model {
    protected $table = 'parametres';

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM parametres ORDER BY cle");
        return $stmt->fetchAll();
    }

    public function get($cle) {
        $stmt = $this->db->prepare("SELECT valeur FROM parametres WHERE cle = ?");
        $stmt->execute([$cle]);
        $result = $stmt->fetch();
        return $result ? $result->valeur : null;
    }

    public function set($cle, $valeur) {
        $stmt = $this->db->prepare("INSERT INTO parametres (cle, valeur) VALUES (?, ?) ON DUPLICATE KEY UPDATE valeur = ?");
        return $stmt->execute([$cle, $valeur, $valeur]);
    }
}
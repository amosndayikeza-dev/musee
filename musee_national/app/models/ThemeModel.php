<?php
namespace App\Models;
use App\Core\SoftDeleteTrait;


use App\Core\Model;

class ThemeModel extends Model {
    use SoftDeleteTrait;
    protected $table = 'themes';

    /**
     * Récupère tous les thèmes
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY nom ASC");
        return $stmt->fetchAll();
    }

    /**
     * Récupère le thème actif
     */
    public function getActive() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE actif = 1 LIMIT 1");
        return $stmt->fetch();
    }

    /**
     * Récupère un thème par son ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Active un thème (désactive tous les autres)
     */
    public function activate($id) {
        // Désactiver tous les thèmes
        $this->db->exec("UPDATE {$this->table} SET actif = 0");

        // Activer le thème choisi
        $stmt = $this->db->prepare("UPDATE {$this->table} SET actif = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Ajoute un nouveau thème
     */
    public function insert($data) {
        $sql = "INSERT INTO {$this->table} (nom, couleur_primaire, couleur_secondaire, couleur_fond, couleur_texte, actif) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nom'],
            $data['couleur_primaire'] ?? '#1a2a3a',
            $data['couleur_secondaire'] ?? '#c9a84c',
            $data['couleur_fond'] ?? '#f4f6f9',
            $data['couleur_texte'] ?? '#333333',
            $data['actif'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Met à jour un thème
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                    nom = ?, 
                    couleur_primaire = ?, 
                    couleur_secondaire = ?, 
                    couleur_fond = ?, 
                    couleur_texte = ?,
                    actif = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['couleur_primaire'] ?? '#1a2a3a',
            $data['couleur_secondaire'] ?? '#c9a84c',
            $data['couleur_fond'] ?? '#f4f6f9',
            $data['couleur_texte'] ?? '#333333',
            $data['actif'] ?? 0,
            $id
        ]);
    }

    /**
     * Supprime un thème
     */
    public function delete($id) {
        // Empêcher la suppression du thème actif
        $theme = $this->getById($id);
        if ($theme && $theme->actif == 1) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
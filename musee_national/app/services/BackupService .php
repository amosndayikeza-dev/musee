<?php
namespace App\Services;

use App\core\Database;
class BackupService {
    
    private $backupDir;

    public function __construct() {
        $this->backupDir = dirname(__DIR__, 2) . '/backups/';
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0777, true);
        }
    }

    /**
     * Crée une sauvegarde de la base de données
     */
    public function createBackup() {
        $tables = $this->getTables();
        $backup = "-- Sauvegarde du " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($tables as $table) {
            $backup .= $this->getTableBackup($table);
        }
        
        $filename = $this->backupDir . 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        file_put_contents($filename, $backup);
        
        return $filename;
    }

    /**
     * Récupère la liste des tables
     */
    private function getTables() {
        $db = Database::getInstance();
        $stmt = $db->query("SHOW TABLES");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Génère la sauvegarde d'une table
     */
    private function getTableBackup($table) {
        $db = Database::getInstance();
        
        // Structure de la table
        $stmt = $db->query("SHOW CREATE TABLE $table");
        $row = $stmt->fetch(\PDO::FETCH_OBJ);
        $backup = "\n\n-- Structure de la table $table\n";
        $backup .= "DROP TABLE IF EXISTS `$table`;\n";
        $backup .= $row->{'Create Table'} . ";\n\n";
        
        // Données
        $stmt = $db->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $backup .= "-- Données de la table $table\n";
            foreach ($rows as $row) {
                $values = array_map(function($value) {
                    return $value === null ? 'NULL' : "'" . addslashes($value) . "'";
                }, array_values($row));
                $columns = array_keys($row);
                $backup .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
            }
            $backup .= "\n";
        }
        
        return $backup;
    }

    /**
     * Restaure une sauvegarde
     */
    public function restoreBackup($filename) {
        $filepath = $this->backupDir . $filename;
        if (!file_exists($filepath)) {
            return false;
        }
        
        $sql = file_get_contents($filepath);
        $db = Database::getInstance();
        
        try {
            $db->exec($sql);
            return true;
        } catch (\PDOException $e) {
            error_log("Erreur de restauration : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère la liste des sauvegardes disponibles
     */
    public function getBackups() {
        $files = glob($this->backupDir . 'backup_*.sql');
        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => filesize($file),
                'date' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }
        rsort($backups);
        return $backups;
    }

    /**
     * Supprime une sauvegarde
     */
    public function deleteBackup($filename) {
        $filepath = $this->backupDir . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
            return true;
        }
        return false;
    }
}
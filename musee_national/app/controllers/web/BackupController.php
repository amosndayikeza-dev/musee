<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Services\BackupService;

class BackupController extends Controller {
    
    public function __construct() {
        AuthMiddleware::requireAdmin();
    }

    /**
     * Liste des sauvegardes
     */
    public function indexAction() {
        $backupService = new BackupService();
        $backups = $backupService->getBackups();
        
        $this->render('index', [
            'backups' => $backups,
            'pageTitle' => 'Sauvegardes'
        ]);
    }

    /**
     * Crée une sauvegarde
     */
    public function createAction() {
        $backupService = new BackupService();
        $filename = $backupService->createBackup();
        
        if ($filename) {
            $_SESSION['success'] = 'Sauvegarde créée avec succès : ' . basename($filename);
        } else {
            $_SESSION['error'] = 'Erreur lors de la création de la sauvegarde';
        }
        
        $this->redirect('backup/index');
    }

    /**
     * Restaure une sauvegarde
     */
    public function restoreAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('backup/index');
            return;
        }
        
        $filename = $_POST['filename'] ?? '';
        if (empty($filename)) {
            $_SESSION['error'] = 'Aucun fichier sélectionné';
            $this->redirect('backup/index');
            return;
        }
        
        $backupService = new BackupService();
        $result = $backupService->restoreBackup($filename);
        
        if ($result) {
            $_SESSION['success'] = 'Restauration réussie : ' . $filename;
        } else {
            $_SESSION['error'] = 'Erreur lors de la restauration';
        }
        
        $this->redirect('backup/index');
    }

    /**
     * Supprime une sauvegarde
     */
    public function deleteAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('backup/index');
            return;
        }
        
        $filename = $_POST['filename'] ?? '';
        if (empty($filename)) {
            $_SESSION['error'] = 'Aucun fichier sélectionné';
            $this->redirect('backup/index');
            return;
        }
        
        $backupService = new BackupService();
        $result = $backupService->deleteBackup($filename);
        
        if ($result) {
            $_SESSION['success'] = 'Sauvegarde supprimée : ' . $filename;
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        
        $this->redirect('backup/index');
    }
}
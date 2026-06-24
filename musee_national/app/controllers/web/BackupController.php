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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/backup');
            return;
        }
        
        $backupService = new BackupService();
        $filename = $backupService->createBackup();
        
        if ($filename) {
            $_SESSION['success'] = 'Sauvegarde créée avec succès : ' . basename($filename);
        } else {
            $_SESSION['error'] = 'Erreur lors de la création de la sauvegarde';
        }
        
        $this->redirect('admin/backup');
    }

    /**
     * Restaure une sauvegarde
     */
    public function restoreAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/backup');
            return;
        }
        
        $filename = $_POST['filename'] ?? '';
        if (empty($filename)) {
            $_SESSION['error'] = 'Aucun fichier sélectionné';
            $this->redirect('admin/backup');
            return;
        }
        
        $backupService = new BackupService();
        $result = $backupService->restoreBackup($filename);
        
        if ($result) {
            $_SESSION['success'] = 'Restauration réussie : ' . $filename;
        } else {
            $_SESSION['error'] = 'Erreur lors de la restauration';
        }
        
        $this->redirect('admin/backup');
    }

    /**
     * Supprime une sauvegarde
     */
    public function deleteAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/backup');
            return;
        }
        
        $filename = $_POST['filename'] ?? '';
        if (empty($filename)) {
            $_SESSION['error'] = 'Aucun fichier sélectionné';
            $this->redirect('admin/backup');
            return;
        }
        
        $backupService = new BackupService();
        $result = $backupService->deleteBackup($filename);
        
        if ($result) {
            $_SESSION['success'] = 'Sauvegarde supprimée : ' . $filename;
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        
        $this->redirect('admin/backup');
    }
}
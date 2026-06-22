<?php
// cron/backup.php - À exécuter avec cron tous les jours à 2h00
require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Services\BackupService;

$backupService = new BackupService();
$filename = $backupService->createBackup();

if ($filename) {
    error_log("Sauvegarde automatique créée : " . $filename);
} else {
    error_log("Erreur lors de la sauvegarde automatique");
}
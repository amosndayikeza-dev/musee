<?php
// Charger l'autoloader de Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';
// Charger la configuration
require_once dirname(__DIR__) . '/app/config/config.php';

// Autoloader PSR-4 simplifié pour les classes du namespace App\
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Charger le Router (car il n'est pas encore dans le namespace App\Core ? Si vous l'avez mis, l'autoloader le chargera)
// Mais pour être sûr, on le require explicitement
require_once dirname(__DIR__) . '/app/core/Router.php';

use App\Core\Router;

$router = new Router();
$router->dispatch();
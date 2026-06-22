<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/app/config/config.php';

// Test de l'autoloader
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

echo "Test de chargement de la classe AuthApiController...\n";

$class = 'App\\Controllers\\Api\\AuthApiController';
if (class_exists($class)) {
    echo "✅ Classe trouvée !\n";
    echo "Chemin : " . (new \ReflectionClass($class))->getFileName() . "\n";
} else {
    echo "❌ Classe non trouvée !\n";
    echo "Vérifiez le namespace et le chemin du fichier.\n";
}
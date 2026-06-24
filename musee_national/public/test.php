<?php
require_once __DIR__ . '/../app/config/config.php';

echo "<h2>Test d'affichage des images</h2>";

// Vérifier les fichiers présents
$dirs = ['uploads/expositions/', 'uploads/auteurs/'];
foreach ($dirs as $dir) {
    echo "<h3>Dossier : $dir</h3>";
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $url = BASE_URL . $dir . $file;
                echo "<div style='display:inline-block; margin:10px;'>";
                echo "<img src='$url' style='max-width:200px; max-height:200px;'><br>";
                echo "<code>$url</code>";
                echo "</div>";
            }
        }
    } else {
        echo "<p style='color:red'>Le dossier $path n'existe pas !</p>";
    }
}
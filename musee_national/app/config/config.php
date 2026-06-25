<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'musee_national');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// URL de base - CORRIGÉ pour le nouveau chemin
define('BASE_URL', 'http://localhost/musee/musee_national/');

// Dossier d'upload - CORRIGÉ
define('UPLOAD_DIR', dirname(__DIR__, 2) . '/public/uploads/');

// Sécurité - JWT
define('JWT_SECRET', 'VotreCleSecreteTresLongueEtAleatoire123!@#$%^&*()_+');
define('JWT_EXPIRE', 3600);

// Autres
define('SITE_NAME', 'Musée National');
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_ACTION', 'index');

// Session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Session - Durée de vie (1 heure)
define('SESSION_TIMEOUT', 3600);
define('SESSION_WARNING', 300);
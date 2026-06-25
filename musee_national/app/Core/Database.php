<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // Récupération des variables d'environnement (Render) ou valeurs par défaut (Local)
            $host     = getenv('DB_HOST')     ?: 'localhost';
            $port     = getenv('DB_PORT')     ?: '3306';
            $dbname   = getenv('DB_NAME')     ?: 'musee_national'; // <-- Change ceci par ton nom local
            $user     = getenv('DB_USER')     ?: 'root';
            $password = getenv('DB_PASS')     ?: ''; // <-- Ton mot de passe local (souvent vide sur XAMPP)
            $charset  = 'utf8';

            // Construction du DSN avec prise en compte du port (important pour certains serveurs cloud)
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
            
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die('Erreur de connexion BDD : ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
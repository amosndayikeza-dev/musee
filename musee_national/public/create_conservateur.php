<?php
// create_conservateur.php
require_once __DIR__ . '/../app/config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('❌ Erreur de connexion : ' . $e->getMessage());
}

// Mot de passe
$password = 'conservateur123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Supprimer l'ancien conservateur s'il existe
$pdo->exec("DELETE FROM utilisateurs WHERE email = 'conservateur@musee.com'");

// Créer le conservateur
$stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
$result = $stmt->execute([
    'Martin',
    'Sophie',
    'conservateur@musee.com',
    $hash,
    'conservateur'
]);

if ($result) {
    echo "✅ Compte CONSERVATEUR créé avec succès !\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📧 Email    : conservateur@musee.com\n";
    echo "🔑 Mot de passe : conservateur123\n";
    echo "👤 Rôle     : Conservateur\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n🔍 Ce que le conservateur peut faire :\n";
    echo "   ✅ Voir le dashboard\n";
    echo "   ✅ Gérer les œuvres (CRUD sauf suppression)\n";
    echo "   ✅ Gérer les auteurs (CRUD sauf suppression)\n";
    echo "   ✅ Gérer les catégories (CRUD sauf suppression)\n";
    echo "   ✅ Gérer les expositions (CRUD sauf suppression)\n";
    echo "   ✅ Gérer les prêts (CRUD sauf suppression)\n";
    echo "   ✅ Gérer les restaurations (CRUD sauf suppression)\n";
    echo "   ✅ Gérer les mouvements (CRUD sauf suppression)\n";
    echo "   ❌ Gérer les utilisateurs\n";
    echo "   ❌ Supprimer des données\n";
} else {
    echo "❌ Erreur lors de la création.\n";
}
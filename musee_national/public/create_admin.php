<?php
// create_admin.php
require_once __DIR__ . '../../app/config/config.php';

// Connexion directe à la base
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('❌ Erreur de connexion : ' . $e->getMessage());
}

// Le mot de passe que vous voulez utiliser
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Supprimer l'ancien utilisateur s'il existe
$pdo->exec("DELETE FROM utilisateurs WHERE email = 'admin@musee.com'");

// Insérer le nouvel utilisateur
$stmt = $pdo->prepare("
    INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) 
    VALUES (?, ?, ?, ?, ?)
");
$result = $stmt->execute([
    'Administrateur',
    'Admin',
    'admin@musee.com',
    $hash,
    'admin'
]);

if ($result) {
    echo "✅ Utilisateur ADMIN créé avec succès !\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📧 Email    : admin@musee.com\n";
    echo "🔑 Mot de passe : admin123\n";
    echo "🔒 Hash     : " . $hash . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Tester immédiatement la vérification
    echo "\n🔍 Test de vérification :\n";
    if (password_verify($password, $hash)) {
        echo "✅ Le mot de passe 'admin123' correspond au hash.\n";
        echo "✅ Vous pouvez vous connecter maintenant !\n";
    } else {
        echo "❌ Problème : le hash ne correspond pas au mot de passe.\n";
    }
} else {
    echo "❌ Erreur lors de l'insertion.\n";
}

// Afficher tous les utilisateurs existants
echo "\n📋 Liste des utilisateurs dans la base :\n";
$users = $pdo->query("SELECT id, nom, email, role FROM utilisateurs")->fetchAll();
if (empty($users)) {
    echo "Aucun utilisateur trouvé.\n";
} else {
    foreach ($users as $user) {
        echo " - ID: {$user->id}, Nom: {$user->nom}, Email: {$user->email}, Rôle: {$user->role}\n";
    }
}
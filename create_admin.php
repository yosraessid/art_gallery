<?php
require_once 'config.php';

// Paramètres de l'administrateur par défaut
$default_username = 'admin';
$default_password = 'admin123'; // À changer après la première connexion !

try {
    // Vérifier si un administrateur existe déjà
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM administrators');
    $count = $stmt->fetch()['count'];

    if ($count > 0) {
        die("Un administrateur existe déjà dans la base de données.\n");
    }

    // Hasher le mot de passe
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

    // Insérer l'administrateur
    $stmt = $pdo->prepare('INSERT INTO administrators (username, password) VALUES (?, ?)');
    if ($stmt->execute([$default_username, $hashed_password])) {
        echo "Administrateur créé avec succès !\n";
        echo "Username: " . $default_username . "\n";
        echo "Password: " . $default_password . "\n";
        echo "IMPORTANT : Changez le mot de passe après votre première connexion !\n";
    }
} catch(PDOException $e) {
    die("Erreur lors de la création de l'administrateur : " . $e->getMessage() . "\n");
} 
<?php
require_once 'config.php';

try {
    // Test de la connexion
    $query = "SELECT 1";
    $pdo->query($query);
    echo "✅ Connexion à la base de données réussie !";
} catch(PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
?> 
<?php
// Utilisation des mêmes paramètres que config.php
$host = 'localhost';
$username = 'root';
$password = 'root';

try {
    // Connexion au serveur MySQL sans sélectionner de base de données
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Création de la base de données
    $sql = "CREATE DATABASE IF NOT EXISTS art_gallery";
    $pdo->exec($sql);
    echo "✅ Base de données créée avec succès !<br>";

    // On se connecte maintenant à la base de données créée
    $pdo->exec("USE art_gallery");

    // Création de la table administrators
    $sql = "CREATE TABLE IF NOT EXISTS administrators (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Table administrators créée avec succès !<br>";

    // Création de la table warehouses
    $sql = "CREATE TABLE IF NOT EXISTS warehouses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        address TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Table warehouses créée avec succès !<br>";

    // Création de la table artworks
    $sql = "CREATE TABLE IF NOT EXISTS artworks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        production_year INT NOT NULL,
        artist_name VARCHAR(100) NOT NULL,
        width DECIMAL(10,2),
        height DECIMAL(10,2),
        warehouse_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "✅ Table artworks créée avec succès !<br>";

    echo "<br>✨ Initialisation de la base de données terminée avec succès !<br>";
    echo "<br>➡️ <a href='create_admin.php'>Cliquez ici pour créer un compte administrateur</a>";

} catch(PDOException $e) {
    die("❌ Erreur : " . $e->getMessage() . "<br>");
} 
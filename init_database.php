<?php
/**
 * Database Initialization Script
 * Creates the database and required tables for the application
 */

// Use the same parameters as config.php
$host = 'localhost';
$username = 'root';
$password = 'root';

try {
    // Connect to MySQL server without selecting a database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the database
    $sql = "CREATE DATABASE IF NOT EXISTS art_gallery";
    $pdo->exec($sql);
    echo "✅ Database created successfully!<br>";

    // Connect to the created database
    $pdo->exec("USE art_gallery");

    // Create administrators table
    $sql = "CREATE TABLE IF NOT EXISTS administrators (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Administrators table created successfully!<br>";

    // Create warehouses table
    $sql = "CREATE TABLE IF NOT EXISTS warehouses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        address TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Warehouses table created successfully!<br>";

    // Create artworks table
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
    echo "✅ Artworks table created successfully!<br>";

    echo "<br>✨ Database initialization completed successfully!<br>";
    echo "<br>➡️ <a href='create_admin.php'>Click here to create an administrator account</a>";

} catch(PDOException $e) {
    die("❌ Error: " . $e->getMessage() . "<br>");
} 
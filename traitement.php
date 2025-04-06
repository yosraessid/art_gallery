<?php
/**
 * Data Processing Script
 * Handles form submission and data insertion into the database
 */

require_once 'config.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    try {
        // Prepare SQL query for data insertion
        $sql = "INSERT INTO utilisateurs (nom, email) VALUES (:nom, :email)";
        $stmt = $pdo->prepare($sql);
        
        // Execute the query with the provided values
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email
        ]);
        
        echo "Data saved successfully!";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?> 
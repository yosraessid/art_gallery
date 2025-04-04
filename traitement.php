<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    try {
        // Préparation de la requête SQL
        $sql = "INSERT INTO utilisateurs (nom, email) VALUES (:nom, :email)";
        $stmt = $pdo->prepare($sql);
        
        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email
        ]);
        
        echo "Données enregistrées avec succès !";
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?> 
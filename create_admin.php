<?php
/**
 * Administrator Account Creation Script
 * Creates the default administrator account in the database
 */

require_once 'config.php';

// Default administrator parameters
$default_username = 'admin';
$default_password = 'admin123'; // Change after first login!

try {
    // Check if an administrator already exists
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM administrators');
    $count = $stmt->fetch()['count'];

    if ($count > 0) {
        die("An administrator already exists in the database.\n");
    }

    // Hash the password
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

    // Insert the administrator
    $stmt = $pdo->prepare('INSERT INTO administrators (username, password) VALUES (?, ?)');
    if ($stmt->execute([$default_username, $hashed_password])) {
        echo "Administrator created successfully!\n";
        echo "Username: " . $default_username . "\n";
        echo "Password: " . $default_password . "\n";
        echo "IMPORTANT: Change the password after your first login!\n";
    }
} catch(PDOException $e) {
    die("Error creating administrator: " . $e->getMessage() . "\n");
} 
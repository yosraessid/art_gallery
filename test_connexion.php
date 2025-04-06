<?php
/**
 * Database Connection Test Script
 * Tests the connection to the database and displays the result
 */

require_once 'config.php';

try {
    // Test the connection by executing a simple query
    $query = "SELECT 1";
    $pdo->query($query);
    echo "✅ Database connection successful!";
} catch(PDOException $e) {
    echo "❌ Connection error: " . $e->getMessage();
}
?> 
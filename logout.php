<?php
/**
 * Logout Page
 * Handles user logout and session destruction
 */

require_once 'config.php';

// Destroy the current session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?> 
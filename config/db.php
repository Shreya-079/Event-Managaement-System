<?php
// Database configuration
$host = "localhost";       // Your database host
$user = "root";            // Default XAMPP username
$pass = "";                // Default XAMPP password (empty)
$db   = "event_management"; // Database we created in schema.sql

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>

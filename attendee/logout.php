<?php
session_start();

// Destroy all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: http://localhost/Event_Management_Website/public/login.php");
exit;
?>

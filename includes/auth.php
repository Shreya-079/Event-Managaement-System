<?php
session_start();

function checkAuth($role = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /Event_Management_website/public/login.php");
        exit;
    }
    if ($role && $_SESSION['role'] !== $role) {
        header("Location: /Event_Management_website/public/login.php");
        exit;
    }
}
?>

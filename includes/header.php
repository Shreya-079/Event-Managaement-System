<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Event Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Event_Management_website/assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="/Event_Management_website/public/index.php">EventManager</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="/Event_Management_website/public/my_tickets.php">My Tickets</a></li>
            <li class="nav-item"><a class="nav-link" href="/Event_Management_website/public/logout.php">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/Event_Management_website/public/login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="/Event_Management_website/public/register.php">Register</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-4">

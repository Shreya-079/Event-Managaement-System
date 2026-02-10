<?php
session_start();
include('../config/db.php');

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Fetch stats
$total_users   = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_events  = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'];
$total_tickets = $conn->query("SELECT COUNT(*) AS total FROM tickets")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">EventSphere</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <!-- Ideally this should clear session before redirect -->
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Analytics & Settings</h2>
    <div class="row text-center mt-4">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3><?php echo $total_events; ?></h3>
                    <p>Total Events</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3><?php echo $total_tickets; ?></h3>
                    <p>Tickets Sold</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3>Settings</h3>
                    <p><a href="manage_users.php">Manage Attendees</a></p>
                    <p><a href="manage_organizers.php">Manage Users</a></p>
                    <p><a href="manage_categories.php">Event Categories</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start();
include('../config/db.php');

// Only allow organizers
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organizer') {
    header("Location: ../public/login.php");
    exit;
}

$organizer_id = $_SESSION['user_id'];

// Handle Delete Action
if (isset($_GET['delete'])) {
    $event_id = intval($_GET['delete']);
    $conn->query("DELETE FROM events WHERE id=$event_id AND organizer_id=$organizer_id");
    header("Location: manage_event.php?msg=deleted");
    exit;
}

// Fetch Organizer's Events
$result = $conn->query("SELECT * FROM events WHERE organizer_id=$organizer_id ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/organizer/manage_event.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">EventSphere</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-secondary me-2" href="dashboard.php">← Back to Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary me-2" href="create_event.php">+ Create New Event</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Manage Your Events</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') { ?>
        <div class="alert alert-danger">Event deleted successfully.</div>
    <?php } ?>

    <div class="event-table-card">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['title']) . "</td>
                            <td>{$row['event_date']}</td>
                            <td>" . htmlspecialchars($row['location']) . "</td>
                            <td>{$row['status']}</td>
                            <td>
                                <a href='attendees.php?id={$row['id']}' class='btn btn-info btn-sm'>View Attendees</a>
                                <a href='edit_event.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='manage_event.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No events found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

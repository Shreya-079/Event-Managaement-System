<?php
session_start();
include('../config/db.php');

// Only allow logged-in organizers
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organizer') {
    header("Location: ../public/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/organizer/dashboard.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">EventSphere</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn btn-primary me-2" href="create_event.php">+ Create Event</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning me-2" href="manage_event.php">Manage Events</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-success me-2" href="checkin.php">Check-In (QR)</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger" href="http://localhost/Event_Management_Website/public/login.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Organizer)</h2>
    <hr>

    <h3>Your Upcoming Events</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $organizer_id = $_SESSION['user_id'];
        $result = $conn->query("SELECT * FROM events WHERE organizer_id=$organizer_id ORDER BY event_date ASC");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['title']) . "</td>
                        <td>{$row['event_date']}</td>
                        <td>" . htmlspecialchars($row['location']) . "</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No events created yet.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>

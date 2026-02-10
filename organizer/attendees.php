<?php
session_start();
include('../config/db.php');

// Only allow organizers
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organizer') {
    header("Location: ../public/login.php");
    exit;
}

$organizer_id = $_SESSION['user_id'];

// Check if event ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_event.php?error=noevent");
    exit;
}
$event_id = intval($_GET['id']);

// Fetch event (to ensure it belongs to this organizer)
$event = $conn->query("SELECT * FROM events WHERE id=$event_id AND organizer_id=$organizer_id")->fetch_assoc();
if (!$event) {
    header("Location: manage_event.php?error=notfound");
    exit;
}

// Fetch attendees
$attendees = $conn->query("SELECT u.name, u.email, t.qr_code, t.registered_at, t.id AS ticket_id
                           FROM tickets t 
                           JOIN users u ON t.user_id = u.id 
                           WHERE t.event_id=$event_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Attendees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/organizer/attendees.css">
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
                    <a class="btn btn-secondary me-2" href="manage_event.php">← Back to Manage Events</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="event-table-card">
        <h2>Attendees for: <?php echo htmlspecialchars($event['title']); ?></h2>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Ticket Code</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendees->num_rows > 0): ?>
                    <?php while ($row = $attendees->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                            <td><?php echo $row['registered_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No attendees yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

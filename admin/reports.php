<?php
session_start();
include('../config/db.php');

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Total stats
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_organizers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='organizer'")->fetch_assoc()['total'];
$total_attendees = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='attendee'")->fetch_assoc()['total'];
$total_events = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'];
$total_tickets = $conn->query("SELECT COUNT(*) AS total FROM tickets")->fetch_assoc()['total'];

// Tickets per event
$events_stats = $conn->query("SELECT e.title, COUNT(t.id) AS tickets_sold 
                              FROM events e 
                              LEFT JOIN tickets t ON e.id=t.event_id 
                              GROUP BY e.id 
                              ORDER BY tickets_sold DESC");

// Tickets per category
$categories_stats = $conn->query("SELECT c.name, COUNT(t.id) AS tickets_sold
                                  FROM categories c
                                  LEFT JOIN events e ON e.category_id=c.id
                                  LEFT JOIN tickets t ON t.event_id=e.id
                                  GROUP BY c.id
                                  ORDER BY tickets_sold DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Platform Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Platform Reports & Analytics</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <div class="row mb-4">
        <div class="col-md-3"><div class="card bg-primary text-white p-3">Total Users: <?php echo $total_users; ?></div></div>
        <div class="col-md-3"><div class="card bg-success text-white p-3">Organizers: <?php echo $total_organizers; ?></div></div>
        <div class="col-md-3"><div class="card bg-warning text-white p-3">Attendees: <?php echo $total_attendees; ?></div></div>
        <div class="col-md-3"><div class="card bg-info text-white p-3">Events: <?php echo $total_events; ?></div></div>
    </div>
    <div class="mb-4">
        <div class="card p-3">
            <h5>Total Tickets Sold: <?php echo $total_tickets; ?></h5>
        </div>
    </div>

    <!-- Tickets per Event -->
    <h4>Tickets Sold per Event</h4>
    <table class="table table-striped mb-4">
        <thead>
            <tr>
                <th>Event</th>
                <th>Tickets Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($events_stats->num_rows > 0): ?>
                <?php while ($row = $events_stats->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo $row['tickets_sold']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="2">No events found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tickets per Category -->
    <h4>Tickets Sold per Category</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Tickets Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($categories_stats->num_rows > 0): ?>
                <?php while ($row = $categories_stats->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo $row['tickets_sold']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="2">No categories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

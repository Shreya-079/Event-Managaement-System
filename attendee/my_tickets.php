<?php
session_start();
include('../config/db.php');

// Check login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'attendee') {
    header("Location: ../public/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch tickets + event info
$stmt = $conn->prepare("
SELECT e.title, e.event_date, e.location, t.qr_code, t.id AS ticket_id
FROM tickets t
JOIN events e ON t.event_id = e.id
WHERE t.user_id = ?
ORDER BY e.event_date ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <title>My Tickets</title>
     <link rel="stylesheet" href="../assets/css/attendee/ticket.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">EventSphere</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="btn btn-secondary me-2" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-danger" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
    <h2>My Tickets</h2>
    <hr>
  
    <?php if ($result->num_rows > 0): ?>
      <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Ticket ID</th>
                    <th>QR Code</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo $row['event_date']; ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td>#<?php echo $row['ticket_id']; ?></td>
                        <td>
                            <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?php echo urlencode($row['qr_code']); ?>&choe=UTF-8" alt="QR Code">
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
      </div>
    <?php else: ?>
        <p class="text-muted">You haven’t registered for any events yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>

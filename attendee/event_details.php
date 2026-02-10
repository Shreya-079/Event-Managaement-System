<?php
session_start();
include('../config/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location:http://localhost/Event_Management_Website/attendee/index.php");
  // exit;
}

$event_id = $_GET['id'];

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
  echo "Event not found.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($event['title']); ?> - Event Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/attendee/detail.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="btn btn-secondary me-2" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
  <div class="event-card">
    <h2><?php echo htmlspecialchars($event['title']); ?></h2>
    <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'attendee'): ?>
      <a href="http://localhost/Event_Management_Website/attendee/event_register.php?id=<?php echo $event['id']; ?>" class="btn btn-success">Register for Event</a>
      <?php else: ?>
        <p class="text-muted">Please <a href="http://localhost/Event_Management_Website/public/login.php">login</a> as an attendee to register.</p>
        <?php endif; ?>

        <a href="http://localhost/Event_Management_Website/attendee/index.php" class="btn btn-secondary">Home</a>
      </div>
</div>

</body>
</html>

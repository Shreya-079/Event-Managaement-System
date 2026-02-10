<?php
session_start();
include('../config/db.php');

// Redirect if not attendee
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'attendee') {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendee Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/attendee/dashboard.css">
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
          <li class="nav-item">
            <a class="btn btn-secondary me-2" href="my_tickets.php">My Tickets</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-danger" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h2>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Attendee)</h2>
  
    <h3 class="mt-4">Upcoming Events</h3>
    <ul class="list-group">
      <?php
        $result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li class='list-group-item'>
                        <strong>".htmlspecialchars($row['title'])."</strong> - ".$row['event_date']."
                        <a href='http://localhost/Event_Management_Website/attendee/event_details.php?id=".$row['id']."' class='btn btn-sm btn-primary float-end'>View</a>
                      </li>";
            }
        } else {
            echo "<li class='list-group-item'>No upcoming events.</li>";
        }
      ?>
    </ul>

    <h3 class="mt-4">My Registered Events</h3>
    <ul class="list-group">
      <?php
        $stmt = $conn->prepare("SELECT e.title, e.event_date FROM tickets t 
                                JOIN events e ON t.event_id = e.id 
                                WHERE t.user_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li class='list-group-item'>
                        <strong>".htmlspecialchars($row['title'])."</strong> - ".$row['event_date']."
                      </li>";
            }
        } else {
            echo "<li class='list-group-item'>You haven’t registered for any events yet.</li>";
        }
      ?>
    </ul>
  </div>

</body>
</html>

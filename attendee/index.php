<?php
session_start();
include('../config/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EventSphere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/attendee/attendee_index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">EventSphere</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="btn btn-primary me-2" href="http://localhost/Event_Management_Website/attendee/dashboard.php">My Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-secondary me-2" href="http://localhost/Event_Management_Website/attendee/my_tickets.php">My Tickets</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-danger" href="http://localhost/Event_Management_Website/attendee/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-primary me-2" href="http://localhost/Event_Management_Website/public/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-success" href="http://localhost/Event_Management_Website/public/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        <?php endif; ?>

        <h3 class="mt-4">Upcoming Events</h3>
        <div class="row">
            <?php
            $result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-3'>
                            <div class='card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>".htmlspecialchars($row['title'])."</h5>
                                    <p class='card-text'>".$row['event_date']."</p>
                                    <a href='event_details.php?id=".$row['id']."' class='btn btn-sm btn-primary'>View Details</a>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p>No upcoming events at the moment.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

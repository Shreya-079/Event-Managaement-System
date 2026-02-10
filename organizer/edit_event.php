<?php
session_start();
include('../config/db.php');

// Only allow organizers
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organizer') {
    header("Location: ../public/login.php");
    exit;
}

$organizer_id = $_SESSION['user_id'];

// Validate Event ID
if (!isset($_GET['id'])) {
    header("Location: manage_event.php");
    exit;
}
$event_id = intval($_GET['id']);

// Fetch event details
$event = $conn->query("SELECT * FROM events WHERE id=$event_id AND organizer_id=$organizer_id")->fetch_assoc();
if (!$event) {
    header("Location: manage_event.php?error=notfound");
    exit;
}

// Update event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $date        = $conn->real_escape_string($_POST['date']);
    $location    = $conn->real_escape_string($_POST['location']);
    $status      = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE events 
                  SET title='$title', description='$description', event_date='$date', location='$location', status='$status' 
                  WHERE id=$event_id AND organizer_id=$organizer_id");

    header("Location: manage_event.php?msg=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/organizer/event_edit.css">
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
                    <a class="btn btn-secondary me-2" href="manage_event.php">← Back</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="form-card">
        <h2>Edit Event</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" 
                       value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" 
                       value="<?php echo $event['event_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" 
                       value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="upcoming"  <?php if ($event['status']=='upcoming')  echo 'selected'; ?>>Upcoming</option>
                    <option value="completed" <?php if ($event['status']=='completed') echo 'selected'; ?>>Completed</option>
                    <option value="cancelled" <?php if ($event['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>
</div>
</body>
</html>

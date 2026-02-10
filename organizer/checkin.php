<?php
session_start();
include('../config/db.php');

// Only allow organizers
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organizer') {
    header("Location: ../public/login.php");
    exit;
}

// Process scanned ticket
$message = "";
if (isset($_POST['ticket_code'])) {
    $ticket_code = $conn->real_escape_string($_POST['ticket_code']);

    // Find ticket
    $stmt = $conn->prepare("SELECT t.id, t.event_id, t.user_id, t.checkin_time, e.title, u.name, e.organizer_id
                            FROM tickets t
                            JOIN events e ON t.event_id = e.id
                            JOIN users u ON t.user_id = u.id
                            WHERE t.qr_code LIKE ?");
    $like_code = "%" . $ticket_code . "%";
    $stmt->bind_param("s", $like_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the event belongs to the logged-in organizer
        if ($row['organizer_id'] != $_SESSION['user_id']) {
            $message = "<div class='alert alert-danger'>❌ This ticket does not belong to your event!</div>";
        } elseif ($row['checkin_time']) {
            $message = "<div class='alert alert-warning'>⚠️ Ticket already used by **{$row['name']}** for event **{$row['title']}**!</div>";
        } else {
            // Mark as checked in
            $stmt_update = $conn->prepare("UPDATE tickets SET checkin_time=NOW() WHERE id=?");
            $stmt_update->bind_param("i", $row['id']);
            $stmt_update->execute();
            
            $message = "<div class='alert alert-success'>✅ **{$row['name']}** checked in for event **{$row['title']}**.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>❌ Invalid ticket code!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Check-In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/organizer/checking.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
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
                    <a class="btn btn-secondary me-2" href="dashboard.php">← Back to Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Event Check-In (QR Scan)</h2>

    <?php echo $message; ?>

    <div class="checkin-card">
        <div class="row">
            <div class="col-md-6">
                <div id="qr-reader" style="width:100%"></div>
            </div>
            <div class="col-md-6">
                <form method="POST">
                    <label>Or Enter Ticket Code Manually</label>
                    <input type="text" name="ticket_code" class="form-control mb-2" required>
                    <button type="submit" class="btn btn-primary">Check In</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.querySelector("input[name='ticket_code']").value = decodedText;
        document.querySelector("form").submit();
    }
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
</body>
</html>

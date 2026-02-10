<?php
session_start();
include('../config/db.php');

// Check login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'attendee') {
    header("Location: http://localhost/Event_Management_Website/public/login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: http://localhost/Event_Management_Website/attendee/index.php");
    exit;
}

$event_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Check if already registered
$stmt = $conn->prepare("SELECT * FROM tickets WHERE user_id = ? AND event_id = ?");
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Already registered
    header("Location: http://localhost/Event_Management_Website/attendee/my_tickets.php?msg=already_registered");
    exit;
}

// Generate a unique ticket code
$ticket_code = md5(uniqid(rand(), true));

// Insert ticket
$stmt = $conn->prepare("INSERT INTO tickets (user_id, event_id, qr_code) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $event_id, $ticket_code);
if ($stmt->execute()) {
    header("Location: http://localhost/Event_Management_Website/attendee/my_tickets.php?msg=success");
} else {
    echo "Error: Could not register for event.";
}

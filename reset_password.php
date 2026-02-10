<?php
include('config/db.php');

// Default password for all users
$defaultPassword = "12345";

// Hash once
$hashed = password_hash($defaultPassword, PASSWORD_BCRYPT);

// Update all users
$stmt = $conn->prepare("UPDATE users SET password=?");
$stmt->bind_param("s", $hashed);

if ($stmt->execute()) {
    echo "✅ All user passwords reset to '$defaultPassword' (stored as hashed).";
} else {
    echo "❌ Error: " . $conn->error;
}

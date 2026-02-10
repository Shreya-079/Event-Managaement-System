<?php
// Include DB connection
include('config/db.php');

// Fetch all users
$result = $conn->query("SELECT id, password FROM users");

while ($row = $result->fetch_assoc()) {
    $userId   = $row['id'];
    $password = $row['password'];

    // Check if password is already hashed (bcrypt hashes always start with $2y$ or $2a$)
    if (strpos($password, '$2y$') === 0 || strpos($password, '$2a$') === 0) {
        continue; // already hashed, skip
    }

    // Hash the plain-text password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Update user with hashed password
    $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $update->bind_param("si", $hashedPassword, $userId);
    $update->execute();

    echo "Updated user ID $userId with hashed password.<br>";
}

echo "✅ Passwords updated successfully!";
?>

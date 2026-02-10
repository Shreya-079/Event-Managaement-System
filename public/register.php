<?php
session_start();
include('../config/db.php'); // connect to DB

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = $_POST['role']; // attendee OR organizer

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $message = "All fields are required!";
    } else {
        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email=?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();

        if ($checkEmail->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            // Secure password
            if (password_needs_rehash($password, PASSWORD_BCRYPT)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            } else {
                $hashedPassword = $password; // already hashed
            }

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now login.";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Event Management</title>
  <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <div class="login-container">
  <h2>User Registration</h2>
  
  <?php if(!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>
  
  <form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
      <option value="attendee">Attendee</option>
      <option value="organizer">Organizer</option>
    </select><br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login here</a></p>

</div>
</body>
</html>

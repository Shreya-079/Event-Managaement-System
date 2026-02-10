<?php
session_start();
include('../config/db.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "Both fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Save session data
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role']      = $user['role'];

                // Redirect based on role
                if ($user['role'] == 'attendee') {
                    header("Location: ../attendee/index.php"); // attendee home
                } elseif ($user['role'] == 'organizer') {
                    header("Location: ../organizer/dashboard.php");
                } elseif ($user['role'] == 'admin') {
                    header("Location: ../admin/dashboard.php");
                }
                exit;
            } else {
                $message = "Invalid password!";
            }
        } else {
            $message = "No account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Event Management</title>
  <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
  <h2>User Login</h2>

  <?php if(!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>

  <form method="POST" action="">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

  <p>Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>

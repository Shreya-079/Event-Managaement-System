<?php
$plainPassword = "admin123";   // the real password you try to log in with
$hashedPassword = "$2y$10$WQ1i5GLXx5.ggs5G7Jtg5.FpBkZc0rAa5I02n9AQEcz..."; // copy the password column from DB

if (password_verify($plainPassword, $hashedPassword)) {
    echo "✅ Match!";
} else {
    echo "❌ No match!";
}
?>

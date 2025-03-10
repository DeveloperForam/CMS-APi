<?php
require 'connect.php'; // Database Connection

$email = "admin@gmail.com";
$password = "123"; // Your Plain Password

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Generate a token for the user
$token = base64_encode(json_encode(["email" => $email]));

// Insert user with hashed password and token
$query = "INSERT INTO admin (email, password, token) VALUES ('$email', '$hashedPassword', '$token')";

if (mysqli_query($conn, $query)) {
    echo "✅ Password Hashed and Token Generated Successfully!";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>

<?php
$host = "localhost";  // Change to your database host
$dbname = "cms";   // Change to your CMS database name
$username = "root";   // Change to your database username
$password = "";       // Change to your database password

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Uncomment for testing
// echo "Database connected successfully!";
?>

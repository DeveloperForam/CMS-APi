<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'connect.php';

$password = '123'; // Your Default Password
$hash = password_hash($password, PASSWORD_DEFAULT);

$email = 'admin@gmail.com';

$sql = "UPDATE admin SET password='$hash' WHERE email='$email'";

if (mysqli_query($conn, $sql)) {
    echo "Password Hashed Successfully ✅";
} else {
    echo "Failed to Hash Password ❌: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

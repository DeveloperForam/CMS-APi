<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php'; // Include your DB connection file

$sql = "SELECT * FROM doctor";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $doctors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $doctors]);
} else {
    echo json_encode(["status" => "error", "message" => "No doctors found"]);
}

mysqli_close($conn);
?>

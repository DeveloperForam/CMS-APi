<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php'; // Database Connection

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email)) {
    $email = mysqli_real_escape_string($conn, $data->email);

    $stmt = $conn->prepare("UPDATE admin SET token = NULL WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Logout failed"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing email"]);
}

mysqli_close($conn);
?>

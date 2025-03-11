<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'connect.php'; // Database Connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Missing email or password"]);
    exit();
}

$email = mysqli_real_escape_string($conn, $data['email']);
$password = $data['password'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();

    // Verify Password
    if (password_verify($password, $admin['password'])) {
        // Generate a secure random token (64 characters)
        $token = bin2hex(random_bytes(32));

        // Set token expiry (30 minutes from now)
        $expires_at = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        // Store the token and expiry in the database
        $updateStmt = $conn->prepare("UPDATE admin SET token = ?, expires_at = ? WHERE email = ?");
        if (!$updateStmt) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
            exit();
        }

        $updateStmt->bind_param("sss", $token, $expires_at, $email);
        
        if ($updateStmt->execute()) {
            echo json_encode([
                "status" => "success",
                "token" => $token,
                "expires_at" => $expires_at, // Send expiry time to client
                "user" => [
                    "id" => $admin['id'],
                    "email" => $admin['email']
                ]
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["status" => "error", "message" => "Failed to update token"]);
        }
        $updateStmt->close();
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    }
} else {
    http_response_code(401); // Unauthorized
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
}

$stmt->close();
mysqli_close($conn);
?>

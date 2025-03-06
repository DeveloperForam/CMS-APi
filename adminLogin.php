<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->password)) {
    $email = mysqli_real_escape_string($conn, $data->email);
    $password = mysqli_real_escape_string($conn, $data->password);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $token = base64_encode(json_encode(["email" => $email, "date" => date("Y-m-d H:i:s")]));

            // Store Token in Database
            $update = "UPDATE admin SET password='$token' WHERE email='$email'";
            mysqli_query($conn, $update);

            echo json_encode(["status" => "success", "token" => $token]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid or missing data"]);
}

mysqli_close($conn);
?>

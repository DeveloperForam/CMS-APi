<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php'; // Include the database connection file

// Get the data from POST request
$data = json_decode(file_get_contents("php://input"));

// Check if required data exists
if (isset($data->c_name) && isset($data->c_mobileno) && isset($data->c_address) && isset($data->c_email) && isset($data->clinic_referenceid) && isset($data->clinic_image)) {
    $c_name = mysqli_real_escape_string($conn, $data->c_name);
    $c_mobileno = mysqli_real_escape_string($conn, $data->c_mobileno);
    $c_address = mysqli_real_escape_string($conn, $data->c_address);
    $c_email = mysqli_real_escape_string($conn, $data->c_email);
    $clinic_referenceid = mysqli_real_escape_string($conn, $data->clinic_referenceid);
    $clinic_image = mysqli_real_escape_string($conn, $data->clinic_image);

    // Insert data into the database
    $sql = "INSERT INTO clinic (c_name, c_mobileno, c_address, c_email, clinic_referenceid, clinic_image) VALUES ('$c_name', '$c_mobileno', '$c_address', '$c_email', '$clinic_referenceid', '$clinic_image')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Clinic added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid or missing data"]);
}

// Close the connection
mysqli_close($conn);
?>

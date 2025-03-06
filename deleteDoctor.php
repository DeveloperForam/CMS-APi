<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php'; // DB Connection File

$data = json_decode(file_get_contents("php://input"));

if (isset($data->clinic_id) && isset($data->doctor_id)) {
    $clinic_id = mysqli_real_escape_string($conn, $data->clinic_id);
    $doctor_id = mysqli_real_escape_string($conn, $data->doctor_id);

    // Check if Doctor Exists with clinic_id and doctor_id
    $check_sql = "SELECT * FROM doctor WHERE clinic_id = '$clinic_id' AND id = '$doctor_id'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        $delete_sql = "DELETE FROM doctor WHERE clinic_id = '$clinic_id' AND id = '$doctor_id'";

        if (mysqli_query($conn, $delete_sql)) {
            echo json_encode(["status" => "success", "message" => "Doctor deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete doctor: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No doctor found for this Clinic ID and Doctor ID"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid or missing data"]);
}

mysqli_close($conn);
?>

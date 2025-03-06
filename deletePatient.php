<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->clinic_id) && isset($data->doctor_id)) {
    $id = mysqli_real_escape_string($conn, $data->id);
    $clinic_id = mysqli_real_escape_string($conn, $data->clinic_id);
    $doctor_id = mysqli_real_escape_string($conn, $data->doctor_id);

    $sql = "DELETE FROM patient WHERE id = '$id' AND clinic_id = '$clinic_id' AND doctor_id = '$doctor_id'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Patient deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid or Missing Data"]);
}

mysqli_close($conn);
?>

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
    $p_name = mysqli_real_escape_string($conn, $data->p_name);
    $p_dob = mysqli_real_escape_string($conn, $data->p_dob);
    $p_mobileno = mysqli_real_escape_string($conn, $data->p_mobileno);
    $p_address = mysqli_real_escape_string($conn, $data->p_address);
    $p_email = mysqli_real_escape_string($conn, $data->p_email);
    $p_year = mysqli_real_escape_string($conn, $data->p_year);
    $p_gender = mysqli_real_escape_string($conn, $data->p_gender);

    $sql = "UPDATE patient SET 
            p_name = '$p_name',
            p_dob = '$p_dob',
            p_mobileno = '$p_mobileno',
            p_address = '$p_address',
            p_email = '$p_email',
            p_year = '$p_year',
            p_gender = '$p_gender',
            update_at = CURRENT_TIMESTAMP
            WHERE id = '$id' AND clinic_id = '$clinic_id' AND doctor_id = '$doctor_id'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Patient updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data provided"]);
}

mysqli_close($conn);
?>

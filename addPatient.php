<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->clinic_id) && isset($data->doctor_id) && isset($data->p_name) && isset($data->p_dob) && isset($data->p_mobileno) && isset($data->p_address) && isset($data->p_email) && isset($data->p_year) && isset($data->p_gender)) {

    $clinic_id = mysqli_real_escape_string($conn, $data->clinic_id);
    $doctor_id = mysqli_real_escape_string($conn, $data->doctor_id);
    $p_name = mysqli_real_escape_string($conn, $data->p_name);
    $p_dob = mysqli_real_escape_string($conn, $data->p_dob);
    $p_mobileno = mysqli_real_escape_string($conn, $data->p_mobileno);
    $p_address = mysqli_real_escape_string($conn, $data->p_address);
    $p_email = mysqli_real_escape_string($conn, $data->p_email);
    $p_year = mysqli_real_escape_string($conn, $data->p_year);
    $p_gender = mysqli_real_escape_string($conn, $data->p_gender);

    $sql = "INSERT INTO patient (clinic_id, doctor_id, p_name, p_dob, p_mobileno, p_address, p_email, p_year, p_gender) 
            VALUES ('$clinic_id', '$doctor_id', '$p_name', '$p_dob', '$p_mobileno', '$p_address', '$p_email', '$p_year', '$p_gender')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Patient added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Data Provided"]);
}

mysqli_close($conn);
?>

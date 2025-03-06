<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php'; // Include your DB connection file

$data = json_decode(file_get_contents("php://input"));

if (isset($data->clinic_id) && isset($data->d_name) && isset($data->d_email) && isset($data->d_contact) && isset($data->d_specialization) && isset($data->d_gender) && isset($data->d_experience) && isset($data->d_qualification)) {

    $clinic_id = mysqli_real_escape_string($conn, $data->clinic_id);
    $d_name = mysqli_real_escape_string($conn, $data->d_name);
    $d_specialization = mysqli_real_escape_string($conn, $data->d_specialization);
    $d_gender = mysqli_real_escape_string($conn, $data->d_gender);
    $d_experience = mysqli_real_escape_string($conn, $data->d_experience);
    $d_contact = mysqli_real_escape_string($conn, $data->d_contact);
    $d_email = mysqli_real_escape_string($conn, $data->d_email);
    $d_qualification = mysqli_real_escape_string($conn, $data->d_qualification);

    // ✅ Check if Email Already Exists
    $check_email = "SELECT d_email FROM doctor WHERE d_email = '$d_email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
    } else {
        $sql = "INSERT INTO doctor (clinic_id, d_name, d_specialization, d_gender, d_experience, d_contact, d_email, d_qualification) 
        VALUES ('$clinic_id', '$d_name', '$d_specialization', '$d_gender', '$d_experience', '$d_contact', '$d_email', '$d_qualification')";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Doctor added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid or missing data"]);
}

mysqli_close($conn);
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->clinic_id) && isset($data->doctor_id)) {
    $clinic_id = mysqli_real_escape_string($conn, $data->clinic_id);
    $doctor_id = mysqli_real_escape_string($conn, $data->doctor_id);

    $sql = "SELECT * FROM patient WHERE clinic_id = '$clinic_id' AND doctor_id = '$doctor_id'";
    $result = mysqli_query($conn, $sql);

    $patients = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $patients[] = $row;
        }
        echo json_encode(["status" => "success", "patients" => $patients]);
    } else {
        echo json_encode(["status" => "error", "message" => "No patients found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid or Missing Data"]);
}

mysqli_close($conn);
?>

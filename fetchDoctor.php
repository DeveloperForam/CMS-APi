<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require 'connect.php'; // Include your DB connection file

$sql = "SELECT d.*, c.c_name FROM doctor d 
        JOIN clinic c ON d.clinic_id = c.id 
        ORDER BY d.clinic_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $clinics = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $clinic_id = $row['clinic_id'];

        if (!isset($clinics[$clinic_id])) {
            $clinics[$clinic_id] = [
                "clinic_id" => $clinic_id,
                "clinic_name" => $row['c_name'],
                "doctors" => []
            ];
        }

        $clinics[$clinic_id]["doctors"][] = [
            "id" => $row["id"],
            "d_name" => $row["d_name"],
            "d_specialization" => $row["d_specialization"],
            "d_gender" => $row["d_gender"],
            "d_experience" => $row["d_experience"],
            "d_contact" => $row["d_contact"],
            "d_email" => $row["d_email"],
            "d_qualification" => $row["d_qualification"],
            "d_schedule" => json_decode($row["d_schedule"]) // Convert JSON schedule
        ];
    }

    echo json_encode(["status" => "success", "data" => array_values($clinics)]);
} else {
    echo json_encode(["status" => "error", "message" => "No doctors found"]);
}

mysqli_close($conn);
?>

<?php
include 'connect.php';

// ✅ Set CORS Headers (Only Allow Frontend Origin)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// ✅ Handle Preflight Request (OPTIONS Method)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit();
}

// ✅ Read JSON Input
$data = json_decode(file_get_contents("php://input"), true);

// ✅ Validate Required Fields
if (!isset($data['clinic_name'], $data['address'], $data['contact'], $data['email'], $data['password'], $data['doctor_name'])) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit();
}

$clinic_name = $data['clinic_name'];
$address = $data['address'];
$contact = $data['contact'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); // ✅ Hash Password
$doctor_name = $data['doctor_name'];

// ✅ Check if Clinic Already Exists (Fix: Use correct table name)
$stmt = $conn->prepare("SELECT id FROM doctors WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Clinic already exists"]);
    exit();
}

// ✅ Insert Clinic into Database
$stmt = $conn->prepare("INSERT INTO doctors (name, address, contact, email, password, doctor_name) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $clinic_name, $address, $contact, $email, $password, $doctor_name);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Clinic added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add clinic"]);
}

$stmt->close();
$conn->close();
?>

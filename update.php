<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");

// Get the raw input data and decode it into a PHP array
$input = json_decode(file_get_contents("php://input"), true);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "enrollment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Extract data
$id = isset($input['id']) ? intval($input['id']) : null;
$firstName = $input['firstName'];
$lastName = $input['lastName'];
$course = $input['course'];
$year = $input['year'];
$enrolled = $input['enrolled'];

// Validate that the 'id' is provided
if (!$id) {
    http_response_code(400);
    echo json_encode(["message" => "Student ID is required"]);
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("UPDATE students SET firstName = ?, lastName = ?, course = ?, year = ?, enrolled = ? WHERE id = ?");
$stmt->bind_param("sssssi", $firstName, $lastName, $course, $year, $enrolled, $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Student updated successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Failed to update student"]);
}

$stmt->close();
$conn->close();
?>

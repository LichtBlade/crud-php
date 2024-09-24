<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "enrollment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$input = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($input['firstName']) || !isset($input['lastName']) || !isset($input['course']) || !isset($input['year']) || !isset($input['enrolled'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input"]);
    exit();
}

// Extract data
$firstName = $input['firstName'];
$lastName = $input['lastName'];
$course = $input['course'];
$year = $input['year'];
$enrolled = $input['enrolled'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO students (firstName, lastName, course, year, enrolled) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $firstName, $lastName, $course, $year, $enrolled);

if ($stmt->execute()) {
    echo json_encode(["message" => "Student added successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to add student"]);
}

$stmt->close();
$conn->close();
?>

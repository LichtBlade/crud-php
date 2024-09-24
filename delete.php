<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");

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

// Validate that the 'id' is provided
if (!$id) {
    http_response_code(400);
    echo json_encode(["message" => "Student ID is required"]);
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "Student deleted successfully"]);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Student not found"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Failed to delete student"]);
}

$stmt->close();
$conn->close();
?>

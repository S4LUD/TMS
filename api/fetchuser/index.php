<?php
require_once '../../src/controllers/Auth/index.php';

// Set response headers
header('Content-Type: application/json');

// Check if the searchTerm parameter is provided in the GET request
if (!isset($_GET['searchTerm'])) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Search term parameter is missing"));
    exit;
}

// Sanitize and validate the searchTerm parameter
$searchTerm = $_GET['searchTerm'];
if (!is_string($searchTerm) || empty($searchTerm)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Invalid search term"));
    exit;
}

// Call userDetails function to fetch user details
$result = Auth::userDetails($searchTerm);

// Check if user details were found
if ($result === null) {
    http_response_code(404); // Not Found
    echo json_encode(array("error" => "User not found"));
    exit;
}

// Output the result
echo $result;

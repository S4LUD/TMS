<?php
require_once '../../src/controllers/Users/index.php';

// Check if both abbreviation and department parameters are set
if (isset($_GET['abbreviation'], $_GET['department'])) {
    // Assign values to variables
    $abbreviation = $_GET['abbreviation'];
    $department = $_GET['department'];

    // Call the insertDepartment method and store the result
    $result = Users::insertDepartment($abbreviation, $department);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Check if the result is not empty and echo it
    if (!empty($result)) {
        echo json_encode(['message' => $result]);
    } else {
        // If the result is empty, return an error message
        echo json_encode(['error' => 'Failed to add department']);
    }
} else {
    // If either abbreviation or department parameters are not set, return an error message
    echo json_encode(['error' => 'Both abbreviation and department parameters are required']);
}

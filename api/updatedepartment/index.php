<?php
require_once '../../src/controllers/Users/index.php';

// Check if all required parameters are set
if (isset($_GET['departmentId'], $_GET['abbreviation'], $_GET['department'])) {
    // Assign values to variables
    $departmentId = $_GET['departmentId'];
    $abbreviation = $_GET['abbreviation'];
    $department = $_GET['department'];

    // Call the updateDepartment method and store the result
    $result = Users::updateDepartment($departmentId, $abbreviation, $department);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Check if the result is not empty and echo it
    if (!empty($result)) {
        echo json_encode(['message' => $result]);
    } else {
        // If the result is empty, return an error message
        echo json_encode(['error' => 'Failed to update department']);
    }
} else {
    // If any required parameter is missing, return an error message
    echo json_encode(['error' => 'All parameters (departmentId, abbreviation, department) are required']);
}

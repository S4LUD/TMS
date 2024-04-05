<?php
require_once '../../src/controllers/Users/index.php';

// Check if all required parameters are set
if (isset($_GET['roleId'], $_GET['role'])) {
    // Assign values to variables
    $roleId = $_GET['roleId'];
    $role = $_GET['role'];

    // Call the updateRole method and store the result
    $result = Users::updateRole($roleId, $role);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Check if the result is not empty and echo it
    if (!empty($result)) {
        echo json_encode(['message' => $result]);
    } else {
        // If the result is empty, return an error message
        echo json_encode(['error' => 'Failed to update role']);
    }
} else {
    // If any required parameter is missing, return an error message
    echo json_encode(['error' => 'All parameters (roleId, role) are required']);
}

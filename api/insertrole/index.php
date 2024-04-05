<?php
require_once '../../src/controllers/Users/index.php';

// Check if both abbreviation and role parameters are set
if (isset($_GET['role'])) {
    // Assign values to variables
    $role = $_GET['role'];

    // Call the insertRole method and store the result
    $result = Users::insertRole($role);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Check if the result is not empty and echo it
    if (!empty($result)) {
        echo json_encode(['message' => $result]);
    } else {
        // If the result is empty, return an error message
        echo json_encode(['error' => 'Failed to add role']);
    }
} else {
    // If either abbreviation or role parameters are not set, return an error message
    echo json_encode(['error' => 'Both abbreviation and role parameters are required']);
}

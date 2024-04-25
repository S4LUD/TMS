<?php
require_once '../../src/controllers/Users/index.php';

// Check if all required parameters are set
if (isset($_GET['userId'])) {
    // Assign values to variables
    $userId = $_GET['userId'];

    // Call the updateRole method and store the result
    $result = Users::unblockUser($userId);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Check if the result is not empty and echo it
    if (!empty($result)) {
        echo json_encode(['message' => $result]);
    } else {
        // If the result is empty, return an error message
        echo json_encode(['error' => 'Unblocking user failed']);
    }
} else {
    // If any required parameter is missing, return an error message
    echo json_encode(['error' => 'All parameters (userId) are required']);
}

<?php
require_once '../../src/controllers/Auth/index.php';

// Check if all required parameters are set
if (isset($_GET['userId'], $_GET['currentPassword'], $_GET['newPassword'])) {
    // Assign values to variables
    $userId = $_GET['userId'];
    $currentPassword = $_GET['currentPassword'];
    $newPassword = $_GET['newPassword'];

    // Call the changePassword method and store the result
    $result = Auth::changePassword($userId, $currentPassword, $newPassword);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    echo $result;
} else {
    // If any required parameter is missing, return an error message
    echo json_encode(['error' => 'All parameters (userId, currentPassword, newPassword) are required']);
}

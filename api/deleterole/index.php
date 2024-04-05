<?php

require_once '../../src/controllers/Users/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (isset($_GET['roleId'])) {
            $roleId = $_GET['roleId'];

            // Check if the role is being used before deletion
            $result = Users::deleteRole($roleId);

            if (is_string($result)) {
                // If the result is a string, it contains a message indicating the role is being used
                echo json_encode(['error' => $result]);
            } else if ($result) {
                // If the result is true, the role was successfully deleted
                echo json_encode(['message' => 'Successfully deleted role']);
            } else {
                // If the result is false, the role deletion failed
                echo json_encode(['error' => 'Failed to delete role']);
            }
        } else {
            echo json_encode([
                'error' => 'Invalid request. Role ID is missing',
            ]);
        }
    } catch (Exception $e) {
        // Respond with error
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Respond with method not allowed
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}

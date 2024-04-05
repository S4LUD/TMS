<?php

require_once '../../src/controllers/Users/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (isset($_GET['roleId'])) {
            $roleId = $_GET['roleId'];

            $result = Users::deleteRole($roleId);

            if ($result) {
                echo json_encode(['message' => 'Successfully delete role']);
            } else {
                echo json_encode([
                    'error' => 'Failed to delete role',
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'Invalid request. role ID is missing',
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

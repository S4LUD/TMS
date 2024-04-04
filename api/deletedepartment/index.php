<?php

require_once '../../src/controllers/Users/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (isset($_GET['departmentId'])) {
            $departmentId = $_GET['departmentId'];

            $result = Users::deleteDepartment($departmentId);

            if ($result) {
                echo json_encode(['message' => 'Successfully delete department']);
            } else {
                echo json_encode([
                    'error' => 'Failed to delete department',
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'Invalid request. Department ID is missing',
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

<?php

require_once '../../src/controllers/Tasks/index.php';

// Set response header
header('Content-Type: application/json');

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the role parameter is set in the GET request
    if (isset($_GET['role'])) {
        $role = $_GET['role'];

        // Call the fetchPublicUsers method from the Tasks class
        $result = Tasks::fetchPublicUsers($role);

        // Return the result as JSON
        echo json_encode(['visibility' => $result]);
    } else {
        // If the role parameter is not set, return an error message
        echo json_encode(['error' => 'Role parameter is missing']);
    }
} else {
    // If the request method is not GET, return an error message
    echo json_encode(['error' => 'Invalid request method']);
}

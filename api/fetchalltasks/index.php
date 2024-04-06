<?php
require_once '../../src/controllers/Tasks/index.php';

// Function to send JSON response
function sendResponse($data)
{
    header('Content-Type: application/json');
    echo $data;
    exit;
}

// Validate and sanitize input parameters
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;
$role = isset($_GET['role']) ? $_GET['role'] : null;
$userId = isset($_GET['userId']) ? $_GET['userId'] : null;

try {
    // Fetch tasks based on parameters
    if ($startDate !== null && $endDate !== null) {
        $result = Tasks::fetchAllTasks($startDate, $endDate, $role, $userId);
    } else {
        $result = Tasks::fetchAllTasks(null, null, null, null);
    }

    sendResponse($result);
} catch (Exception $e) {
    sendResponse(['error' => $e->getMessage()]);
}

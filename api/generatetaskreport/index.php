<?php
require_once '../../src/controllers/Tasks/index.php';

// Check if the required parameters are set
if (isset($_GET['startDate'], $_GET['endDate'])) {
    // Get the provided parameters
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
    $statusFilter = isset($_GET['statusFilter']) ? $_GET['statusFilter'] : null;
    $usernames = isset($_GET['usernames']) ? $_GET['usernames'] : null;

    // Call the GenerateTaskReport function with the provided parameters
    $result = Tasks::GenerateTaskReport($startDate, $endDate, $statusFilter, $usernames);

    // Set the response header to JSON
    header('Content-Type: application/json');

    // Echo the result
    echo $result;
} else {
    // If required parameters are missing, return an error message
    echo json_encode(['error' => 'startDate and endDate parameters are required']);
}

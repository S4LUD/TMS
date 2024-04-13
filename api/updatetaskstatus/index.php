<?php
require_once '../../src/controllers/Tasks/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Start a database transaction
    $pdo = Tasks::getPDO(); // Replace with your actual method for getting a PDO instance
    $pdo->beginTransaction();

    try {
        if (isset($_GET['taskId']) && isset($_GET['statusId'])) {
            $taskId = $_GET['taskId'];
            $statusId = $_GET['statusId'];

            $result = Tasks::updateTaskStatus($taskId, $statusId);

            if ($result) {
                $pdo->commit();
                echo json_encode(['message' => 'Successfully updated task status']);
            } else {
                echo json_encode([
                    'error' => 'Updating permissions failed',
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'userId and permissions parameters are required',
            ]);
        }
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $pdo->rollBack();

        // Respond with error
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Respond with method not allowed
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}

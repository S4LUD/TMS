<?php
require_once '../../src/controllers/Tasks/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Start a database transaction
    $pdo = Tasks::getPDO(); // Replace with your actual method for getting a PDO instance
    $pdo->beginTransaction();

    try {
        if (isset($_GET['task_id']) && isset($_GET['task_type']) && isset($_GET['user_id']) && isset($_GET['dueAt'])) {
            // Access form fields
            $task_type = $_GET['task_type'];
            $user_id = $_GET['user_id'];
            $dueAt = $_GET['dueAt'];
            $task_id = $_GET['task_id'];

            Tasks::distributeTask($task_id, $task_type, $user_id, $dueAt);

            $pdo->commit();
            echo json_encode(['message' => 'Successfully distributed task']);
        } else {
            echo json_encode([
                'message' => 'Distributing task failed',
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

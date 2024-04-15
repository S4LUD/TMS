<?php

require_once '../../src/controllers/Tasks/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pdo = Tasks::getPDO();
    $pdo->beginTransaction();

    try {
        if (isset($_GET['task_id']) && !empty($_GET['task_id'])) {
            $task_id = $_GET['task_id'];

            $result = Tasks::unassignTask($task_id);

            if ($result) {
                $pdo->commit(); // Commit the transaction
                echo json_encode([
                    'message' => 'Successfully unassigned the task',
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'Invalid request. Task ID is missing',
            ]);
        }
    } catch (Exception $e) {
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

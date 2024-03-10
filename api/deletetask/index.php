<?php

require_once '../../src/controllers/Tasks/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Tasks::getPDO();
    $pdo->beginTransaction();

    try {
        if (isset($_POST['task_id']) && !empty($_POST['task_id'])) {
            $task_id = $_POST['task_id'];

            $result = Tasks::viewTask($task_id);
            $resultArray = json_decode($result, true);

            if ($resultArray && !array_key_exists('error', $resultArray)) {
                // Remove associated files
                if (!empty($resultArray['files'])) {
                    foreach ($resultArray['files'] as $file) {
                        Tasks::removeFile($file['file_id']);
                    }
                }

                // Delete the task
                $taskDeleted = Tasks::deleteTask($task_id);

                if ($taskDeleted) {
                    $pdo->commit();
                    echo json_encode(['message' => 'Successfully deleted task']);
                } else {
                    echo json_encode([
                        'message' => 'Failed to delete task',
                    ]);
                }
            } else {
                echo json_encode([
                    'message' => 'Failed to delete task. Task not found',
                ]);
            }
        } else {
            echo json_encode([
                'message' => 'Invalid request. Task ID is missing',
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

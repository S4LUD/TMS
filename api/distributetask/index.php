<?php
require_once '../../src/controllers/Tasks/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/tms/src/assets/uploads/';

    // Start a database transaction
    $pdo = Tasks::getPDO(); // Replace with your actual method for getting a PDO instance
    $pdo->beginTransaction();

    try {
        if (isset($_POST['task_id']) && isset($_POST['task_type']) && isset($_POST['user_id']) && isset($_POST['dueAt'])) {
            // Access form fields
            $task_type = $_POST['task_type'];
            $user_id = $_POST['user_id'];
            $dueAt = $_POST['dueAt'];
            $task_id = $_POST['task_id'];

            $result = Tasks::updateTask($task_id, $title, $details);

            if ($result) {
                if (isset($_FILES['files'])) {
                    $files = $_FILES['files'];

                    foreach ($files['tmp_name'] as $key => $tmp_name) {
                        $file_name = $files['name'][$key];
                        $file_size = $files['size'][$key];
                        $file_type = $files['type'][$key];

                        $uniqueFilename = time() . '_' . md5(uniqid()) . '_' . str_replace(' ', '_', $file_name);
                        $destination = $uploadDirectory . $uniqueFilename;

                        // Attempt to insert data into the database
                        $inserted = insertTaskFile($file_name, $file_size, $destination, $taskId);

                        if ($inserted) {
                            // If the insertion is successful, move the file to the destination directory
                            move_uploaded_file($tmp_name, $destination);
                        } else {
                            // Handle database insertion failure
                            throw new Exception('Updating database failed for file.');
                        }
                    }
                }
            }

            $pdo->commit();
            echo json_encode(['message' => 'Successfully updated task']);
        } else {
            echo json_encode([
                'message' => 'Updating task failed',
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
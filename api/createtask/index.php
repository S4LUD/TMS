<?php
require_once '../../src/controllers/Tasks/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Access form fields
    $title = $_POST['title'];
    $details = $_POST['details'];
    $role = $_POST['role'];
    $createdBy = $_POST['createdBy'];
    $department_id = $_POST['department_id'] ?? "";

    $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/tms/src/assets/uploads/';

    // Start a database transaction
    $pdo = Tasks::getPDO(); // Replace with your actual method for getting a PDO instance
    $pdo->beginTransaction();

    try {
        // Attempt to create a new task
        $result = Tasks::newTask($title, $details, $department_id, $role, $createdBy);

        if ($result && is_object($result) && property_exists($result, 'id')) {
            $taskId = $result->id;

            if (isset($_FILES['files'])) {
                $files = $_FILES['files'];

                foreach ($files['tmp_name'] as $key => $tmp_name) {
                    $file_name = $files['name'][$key];
                    $file_size = $files['size'][$key];
                    $file_type = $files['type'][$key];

                    $uniqueFilename = time() . '_' . md5(uniqid()) . '_' . str_replace(' ', '_', $file_name);
                    $destination = $uploadDirectory . $uniqueFilename;

                    // Attempt to insert data into the database
                    $inserted = insertTaskFile($file_name, $file_size, $destination, $result->id);

                    if ($inserted) {
                        // If the insertion is successful, move the file to the destination directory
                        move_uploaded_file($tmp_name, $destination);
                    } else {
                        // Handle database insertion failure
                        throw new Exception('Database insertion failed for file.');
                    }
                }
            }

            // Commit the transaction if everything is successful
            $pdo->commit();

            echo json_encode([
                'message' => 'Successfully created task',
            ]);
        } else {
            echo json_encode([
                'message' => 'Task creation failed',
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

// Function to insert data into the database
function insertTaskFile($uniqueFilename, $file_size, $destination, $task_id)
{
    $result = Tasks::newFile($uniqueFilename, $file_size, $destination,  $task_id);
    return $result;
}

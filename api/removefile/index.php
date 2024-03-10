<?php
require_once '../../src/controllers/Tasks/index.php';

if (isset($_GET['file_id'])) {
    $file_id = trim($_GET['file_id']);

    $result = Tasks::removeFile($file_id);

    header('Content-Type: application/json');

    echo json_encode($result);
} else {
    echo json_encode(['error' => 'File not found in the database']);
}

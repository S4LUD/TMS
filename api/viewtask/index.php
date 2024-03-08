<?php
require_once '../../src/controllers/Tasks/index.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $result = Tasks::viewTask($task_id);
    header('Content-Type: application/json');
    echo $result;
} else {
    header('Content-Type: application/json');
    echo json_encode(['message' => 'task not found']);
}

<?php
require_once '../../src/controllers/Users/index.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $result = Users::fetchAlltUserTasks($user_id);

    header('Content-Type: application/json');

    // Echo the result
    echo $result;
} else {
    echo json_encode(['error' => 'user_id parameter is missing']);
}

<?php
require_once '../../src/controllers/Auth/index.php';

if (isset($_GET['username']) && isset($_GET['password']) && isset($_GET['department_id']) && isset($_GET['role_id'])) {
    $username = trim($_GET['username']);
    $password = trim($_GET['password']);
    $departmentId = trim($_GET['department_id']);
    $roleId = trim($_GET['role_id']);

    $result = Auth::createUser($username, $password, $departmentId, $roleId);

    header('Content-Type: application/json');

    echo $result;
} else {
    echo json_encode(['error' => 'Failed to register user']);
}

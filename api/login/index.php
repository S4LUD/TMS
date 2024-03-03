<?php
require_once '../../src/controllers/Auth/index.php';

if (isset($_GET['username']) && isset($_GET['password'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];

    $result = Auth::login($username, $password);

    header('Content-Type: application/json');

    // Echo the result
    echo $result;
} else {
    echo json_encode(['error' => 'Invalid login credentials']);
}

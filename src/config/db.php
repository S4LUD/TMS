<?php
$host = 'localhost';
$dbname = 'task_management_system_v2';
$username = 'root';
$password = '';

date_default_timezone_set('Asia/Manila');

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

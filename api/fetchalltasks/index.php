<?php
require_once '../../src/controllers/Tasks/index.php';

if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    $result = Tasks::fetchAllTasks($startDate, $endDate);

    header('Content-Type: application/json');

    echo $result;
} else {
    $result = Tasks::fetchAllTasks(null, null);

    header('Content-Type: application/json');

    echo $result;
}

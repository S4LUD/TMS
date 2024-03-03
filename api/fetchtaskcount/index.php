<?php
require_once '../../src/controllers/Performance/index.php';

$result = Performance::fetchTasksCountByCategory();

header('Content-Type: application/json');
echo $result;

<?php
require_once '../../src/controllers/Performance/index.php';

$result = Performance::tasksCount();

header('Content-Type: application/json');
echo $result;

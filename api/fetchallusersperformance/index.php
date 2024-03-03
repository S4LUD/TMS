<?php
require_once '../../src/controllers/Performance/index.php';

$result = Performance::fetchAllUsersPerformance();

header('Content-Type: application/json');
echo $result;

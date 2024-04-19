<?php
require_once '../../src/controllers/Users/index.php';

$result = Users::fetchTaskStatuses();

header('Content-Type: application/json');

// Echo the result
echo $result;

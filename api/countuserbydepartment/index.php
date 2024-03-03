<?php
require_once '../../src/controllers/Users/index.php';

$result = Users::countUsersByDepartment();

header('Content-Type: application/json');
echo $result;

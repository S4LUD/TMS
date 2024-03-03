<?php
require_once '../../src/controllers/Users/index.php';

$result = Users::countUsersByRole();

header('Content-Type: application/json');
echo $result;

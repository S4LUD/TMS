<?php
require_once '../../src/controllers/Users/index.php';

$result = Users::countUsers();

header('Content-Type: application/json');
echo $result;

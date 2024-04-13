<?php
require_once '../../src/controllers/Users/index.php';

if (isset($_GET['searchaccount']) && !empty($_GET['searchaccount'])) {
    $searchaccount = $_GET['searchaccount'];
    $result = Users::fetchAllUsers($searchaccount, null);
    header('Content-Type: application/json');
    echo $result;
} elseif (isset($_GET['abbreviation']) && !empty($_GET['abbreviation'])) {
    $abbreviation = $_GET['abbreviation'];
    $result = Users::fetchAllUsers(null, $abbreviation);
    header('Content-Type: application/json');
    echo $result;
} else {
    $result = Users::fetchAllUsers(null, null);
    header('Content-Type: application/json');
    echo $result;
}

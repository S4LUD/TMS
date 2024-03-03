<?php
require_once '../../src/controllers/Users/index.php';

if (isset($_GET['searchaccount'])) {
    $searchaccount = $_GET['searchaccount'];

    $result = Users::fetchAllUsers($searchaccount);

    header('Content-Type: application/json');

    echo $result;
} else {
    $result = Users::fetchAllUsers(null);

    header('Content-Type: application/json');

    echo $result;
}

<?php
session_start();

$pageTitle = "TMS | 404 page not found";
$contentView = '/tms/src/views/404.php';
include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/layouts/index.php');

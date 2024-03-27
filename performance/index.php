<?php
session_start();

$pageTitle = "Performance Page";
$contentView = '/tms/src/views/performance.php';
include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/layouts/index.php');

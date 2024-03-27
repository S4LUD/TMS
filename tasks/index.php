<?php
session_start();

$pageTitle = "TMS | Tasks Page";
$contentView = '/tms/src/views/tasks.php';
include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/layouts/index.php');

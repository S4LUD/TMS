<?php

if (isset($_GET['file'])) {
    $fileDestination = $_GET['file'];

    if (file_exists($fileDestination)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileDestination) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileDestination));
        readfile($fileDestination);
        exit;
    } else {
        echo 'File not found';
    }
} else {
    echo 'Invalid request';
}

<?php
session_start();
session_destroy();
echo '<script>localStorage.clear();</script>';
header("refresh:0;url=/tms/login/");

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'dbconnect.php';
SqlConn::add();
header("Location: index.php");
?>

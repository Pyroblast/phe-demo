<?php
session_start();
ob_start();
$dsn = "mysql:host=localhost;dbname=phe-demo";
$db = new PDO($dsn, 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost:3306";
$username   = "root";
$password   = "";
$database   = "carproject";

$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

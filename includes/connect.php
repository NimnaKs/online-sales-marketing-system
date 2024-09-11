<?php 
$con = new mysqli('localhost', 'root', '', 'sales_system_db');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>

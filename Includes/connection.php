<?php
// Establish a connection to the database
$host = "localhost";
$username = "root";
$password = "";
$database = "suspect_tracker";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php 

$serverName = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'phplogindb';

// DB connection
$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

// If connection fails
if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
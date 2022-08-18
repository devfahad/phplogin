<?php

$serverName = 'localhost';
$dbUsername = 'devfahad_phploginuser';
$dbPassword = 'phploginuser123';
$dbName     = 'devfahad_phplogin';

// DB connection
$conn = mysqli_connect( $serverName, $dbUsername, $dbPassword, $dbName );

// Check connection
if ( !$conn ) {
    die( 'Connection failed: ' . mysqli_connect_error() );
}
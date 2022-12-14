<?php

if ( isset( $_POST['submit'] ) ) {

    $username = $_POST['uid'];
    $pwd      = $_POST['pwd'];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    // Error handlers
    if ( emptyInputLogin( $username, $pwd ) !== false ) {
        header( 'location: ../login.php?error=emptyinput' );
        exit();
    }

    // Login the user
    loginUser( $conn, $username, $pwd );

} else {
    header( 'location: ../login.php' );
    exit();
}
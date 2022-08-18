<?php

if ( isset( $_POST['reset-request-submit'] ) ) {

    // Create two tokens to avoid timing attacks by hacker
    $selector = bin2hex( random_bytes( 8 ) );
    $token    = random_bytes( 32 );

    // Create url to be sent to the user
    $url = 'www.devfahad.com/phplogin/create-new-password.php?selector=' . $selector . '&validator=' . bin2hex( $token );

    // Create expiry date for the tokens
    $expires = date( 'U' ) + 1800;

    // Set up token inside the DB
    require 'dbh.inc.php';

    $userEmail = $_POST['email'];

    // Delete existing token inside DB using prepared stmt
    $sql  = 'DELETE FROM pwdreset WHERE pwdResetEmail=?;';
    $stmt = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare( $stmt, $sql ) ) {
        echo 'There was an error1!';
        exit();
    } else {
        // Bind and Execute
        mysqli_stmt_bind_param( $stmt, 's', $userEmail );
        mysqli_stmt_execute( $stmt );
    }

    // Insert tokens inside DB table using prepared stmt
    $sql  = 'INSERT INTO pwdreset(pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES(?, ?, ?, ?);';
    $stmt = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare( $stmt, $sql ) ) {
        echo 'There was an error!';
        exit();
    } else {
        // Hash the Token
        $hashedToken = password_hash( $token, PASSWORD_DEFAULT );
        // bind and execute
        mysqli_stmt_bind_param( $stmt, 'ssss', $userEmail, $selector, $hashedToken, $expires );
        mysqli_stmt_execute( $stmt );
    }

    // Close all stmt
    mysqli_stmt_close( $stmt );
    // Close connection to save resources
    mysqli_close( $conn );

    // Send email to user
    $to      = $userEmail;
    $subject = 'Reset your password';

    $message = '<p>We received a password reset request. The link to reset your passowrd is below. If you did not make this request, you can ignore this email.</p>';
    $message .= '<p>Here is your password reset link: </br></p>';
    $message .= '<a href="' . $url . '">' . $url . '</a>';

    // $headers = 'From: devFahad <info@devfahad.com>';
    // $headers .= 'Reply-To: info@devfahad.com';
    // $headers .= 'Content-type: text/plain';

    $headers = 'From: devFahad <info@devfahad.com>' . "\r\n" .
    'Reply-To: info@devfahad.com' . "\r\n" .
    'Content-type: text/html' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    mail( $to, $subject, $message, $headers );

    header( 'location: ../reset-password.php?reset=success' );

} else {
    header( 'location: ../index.php' );
    exit();
}
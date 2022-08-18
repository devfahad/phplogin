<?php

// Update the resetted password inside DB

if ( isset( $_POST['reset-password-submit'] ) ) {

    $selector       = $_POST['selector'];
    $validator      = $_POST['validator'];
    $password       = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    // Error handlers
    if ( empty( $password ) || empty( $passwordRepeat ) ) {
        header( 'location: ../signup.php?newpwd=empty' );
        exit();
    } else if ( $password != $passwordRepeat ) {
        header( 'location: ../signup.php?newpwd=pwdnotsame' );
        exit();
    }

    $currentDate = date( 'U' );

    require 'dbh.inc.php';

    // Select tokens from the DB
    $sql = 'SELECT * FROM pwdreset WHERE pwdResetSelector=? AND pwdResetExpires >= ?;';
    // Prepare stmt init
    $stmt = mysqli_stmt_init( $conn );
    // Check if it's possible to run the stmt
    if ( !mysqli_stmt_prepare( $stmt, $sql ) ) {
        echo 'There was an error!';
        exit();
    } else {
        // Bind the placeholder value
        mysqli_stmt_bind_param( $stmt, 'ss', $selector, $currentDate );
        // Execute stmt
        mysqli_stmt_execute( $stmt );

        // Store result from DB
        $result = mysqli_stmt_get_result( $stmt );
        if ( !$row = mysqli_fetch_assoc( $result ) ) {
            echo 'You need to re-submit your reset request.';
            exit();
        } else {
            $tokenBin = hex2bin( $validator );

            // Match token from DB to ours
            $tokenCheck = password_verify( $tokenBin, $row['pwdResetToken'] );

            if ( $tokenCheck === false ) {
                echo 'You need to re-submit your reset request.';
                exit();
            } else {         
                $tokenEmail = $row['pwdResetEmail'];

                $sql = 'SELECT * FROM users WHERE usersEmail=?;';
                $stmt = mysqli_stmt_init( $conn );
                if( !mysqli_stmt_prepare( $stmt, $sql ) ) {
                    echo 'There was an error!';
                    exit();
                } else {
                    
                    // Start updating the password
                    $sql = 'UPDATE users SET usersPwd=? WHERE usersEmail=?';
                    // Prepared stmt
                    $stmt = mysqli_stmt_init( $conn );
                    if( !mysqli_stmt_prepare( $stmt, $sql ) ) {
                        echo 'There was an error!';
                        exit();
                    } else {
                        $newPwdHash = password_hash( $password, PASSWORD_DEFAULT );
                        mysqli_stmt_bind_param( $stmt, 'ss', $newPwdHash, $tokenEmail );
                        mysqli_stmt_execute( $stmt );

                        // Delete the token
                        $sql = 'DELETE FROM pwdreset WHERE pwdResetEmail=?;';
                        $stmt = mysqli_stmt_init( $conn );
                        if( !mysqli_stmt_prepare( $stmt, $sql ) ) {
                            echo 'There was an error!';
                            exit();
                        } else {
                            mysqli_stmt_bind_param( $stmt, 's', $tokenEmail );
                            mysqli_stmt_execute( $stmt );
                            header( 'location: ../login.php?newpwd=passwordupdated' );
                        }
                    }
                }

            }
        }

    }

} else {
    header( 'location: ../index.php' );
    exit();
}
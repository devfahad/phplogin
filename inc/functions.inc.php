<?php

function emptyInputSignup( $name, $email, $username, $pwd, $pwdRepeat ) {
    $result = false;

    if ( empty( $name ) || empty( $email ) || empty( $username ) || empty( $pwd ) || empty( $pwdRepeat ) ) {
        $result = true;
    }

    return $result;
}

function invalidUid( $username ) {
    $result = false;
    // If there is a missmatch in the $username, result=true
    // preg_match is a search algorithm
    if ( !preg_match( '/^[a-zA-Z0-9]*$/', $username ) ) {
        $result = true;
    }
    return $result;
}

function invalidEmail( $email ) {
    $result = false;
    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        $result = true;
    }
    return $result;
}

function pwdMatch( $pwd, $pwdRepeat ) {
    $result = false;
    if ( $pwd !== $pwdRepeat ) {
        $result = true;
    }
    return $result;
}

function uidExists( $conn, $username, $email ) {
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";

    // Initialize Prepared Statement
    $stmt = mysqli_stmt_init( $conn );

    if ( !mysqli_stmt_prepare( $stmt, $sql ) ) {
        header( 'location: ../signup.php?error=stmtfailed' );
        exit();
    }
    // Binding prepared statement
    mysqli_stmt_bind_param( $stmt, 'ss', $username, $email );
    // Execute statement
    mysqli_stmt_execute( $stmt );

    $resultData = mysqli_stmt_get_result( $stmt );

    if ( $row = mysqli_fetch_assoc( $resultData ) ) {
        // if user exists in db, return userdata from db
        return $row;
    } else {
        $result = false;
        return $result;
    }

    // Close prepared statement
    mysqli_stmt_close( $stmt );
}

function createUser( $conn, $name, $email, $username, $pwd ) {
    $sql = 'INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?);';

    // Initialize Prepared Statement
    $stmt = mysqli_stmt_init( $conn );

    if ( !mysqli_stmt_prepare( $stmt, $sql ) ) {
        header( 'location: ../signup.php?error=stmtfailed' );
        exit();
    }

    // Password hashing algorithm
    $hashedPwd = password_hash( $pwd, PASSWORD_DEFAULT );

    // Binding prepared statement
    mysqli_stmt_bind_param( $stmt, 'ssss', $name, $email, $username, $hashedPwd );

    // Execute statement
    mysqli_stmt_execute( $stmt );

    // Close prepared statement
    mysqli_stmt_close( $stmt );

    header( 'location: ../signup.php?error=none' );
    exit();
}

function emptyInputLogin( $username, $pwd ) {
    $result = false;

    if ( empty( $username ) || empty( $pwd ) ) {
        $result = true;
    }

    return $result;
}

function loginUser( $conn, $username, $pwd ) {
    $uidExists = uidExists( $conn, $username, $username );

    if ( $uidExists === false ) {
        header( 'location: ../login.php?error=wronglogin' );
        exit();
    }

    // Hashed password from db
    $pwdHashed = $uidExists['usersPwd'];

    $checkPwd = password_verify( $pwd, $pwdHashed );

    if ( $checkPwd === false ) {
        header( 'location: ../login.php?error=wronglogin' );
        exit();
    } else if ( $checkPwd === true ) {
        session_start();
        $_SESSION['userid']  = $uidExists['usersId'];
        $_SESSION['useruid'] = $uidExists['usersUid'];

        header( 'location: ../index.php' );
        exit();
    }
}
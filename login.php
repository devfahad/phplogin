<?php include_once 'inc/templates/header.php';?>

    <section class="signup-form">
        <h2>Log In</h2>
        <form action="inc/login.inc.php" method="POST">
            <input type="text" name="uid" placeholder="Username/Email Address">
            <input type="password" name="pwd" placeholder="Password">
            <button type="submit" name="submit">Log In</button>
        </form>

        <p class="member-reg">Not a member? <a href="signup.php">Sign up</a></p>

        <?php
        if ( isset( $_GET['error'] ) ) {
            if ( $_GET['error'] == 'emptyinput' ) {
                echo '<p>Fill in all the fields!</p>';
            } else if ( $_GET['error'] == 'wronglogin' ) {
                echo '<p>Incorrect login information!</p>';
            }
        }
        ?>

    </section>

<?php include_once 'inc/templates/footer.php';?>
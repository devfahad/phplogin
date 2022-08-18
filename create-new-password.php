<?php include_once 'inc/templates/header.php';?>
<!-- Here the user updates the password after receiving the email -->

    <section class="signup-form">
        <h2>Create new password</h2>

        <?php

            $selector = $_GET['selector'];
            $validator = $_GET['validator'];

            if( empty( $selector ) || empty( $validator ) ) {
                echo 'Could not validate your request!';
            } else {
                // Check if the tokens are in hexadecimal format. Will return boolean
                if( ctype_xdigit( $selector ) !== false &&  ctype_xdigit( $validator ) !== false ) { ?>
                
                    <form action="inc/reset-password.inc.php" method="POST">
                        <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                        <input type="hidden" name="validator" value="<?php echo $validator; ?>">
                        <input type="password" name="pwd" placeholder="Enter a new password">
                        <input type="password" name="pwd-repeat" placeholder="Repeat new password">
                        <button type="submit" name="reset-password-submit">Reset Password</button>
                    </form>

                <?php }
            } ?>

    </section>

<?php include_once 'inc/templates/footer.php';?>
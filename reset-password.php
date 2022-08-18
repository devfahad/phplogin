<?php include_once 'inc/templates/header.php';?>
<!-- Here we create the form which starts the password recovery process! -->

    <section class="signup-form">
        <h2>Reset your password</h2>
        <p class="reset-inst">An e-mail will be send to you with instructions<br> on how to reset your password.</p>
        <form action="inc/reset-request.inc.php" method="POST">
            <input type="text" name="email" placeholder="Enter your e-mail adress...">
            <button type="submit" name="reset-request-submit">Receive new password by e-mail</button>
        </form>
        <?php
            if( isset( $_GET['reset'] ) ) :
                if( $_GET['reset'] == 'success' ) :
                    echo '<p class="success">Check your e-mail!</p>';
                endif;
            endif;
        ?>

    </section>

<?php include_once 'inc/templates/footer.php';?>
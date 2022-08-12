<?php include_once 'inc/templates/header.php';?>

    <section class="index-intro">
        <?php
        if ( isset( $_SESSION['useruid'] ) ) {
            echo '<h2>Hello there ' . $_SESSION['useruid'] . '</h2>';
        }
        ?>
        <h1>This is an introduction</h1>
        <p>Here is an important paragraph that explains the purpose of the website.</p>
    </section>

    <section class="index-categories">
        <h2>Categories</h2>
        <div class="index-categories-list">
            <div>
                <h3>Catetory 1</h3>
            </div>
            <div>
                <h3>Catetory 2</h3>
            </div>
            <div>
                <h3>Catetory 3</h3>
            </div>
            <div>
                <h3>Catetory 4</h3>
            </div>
        </div>
    </section>

<?php include_once 'inc/templates/footer.php';?>
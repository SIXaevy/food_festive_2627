<?php
    include "session.php"; 
    include "require_login.php";
?>
<?php include 'layouts/_header.php';?>
    <?php include "layouts/_navigation.php" ?>
    <main class="account">
        <section id="account" class="container">
            <div id="account-container">               
                <?php include "layouts/_account-navigation.php" ?>
                <div id="account-preview">
                    <h1>Welcome <?= $_SESSION['name'] ?></h1>
                </div>
            </div>
        </section>
    </main>
<?php include 'layouts/_footer.php';?>
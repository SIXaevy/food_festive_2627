<?php 
    include "./models/login.php";
    include "./session.php";

    $auth_page = 'login';
    $errors = []; 

    if(isset($_SESSION['id'])) {
        header("Location: account");
    }

    if(isset($_POST['submit'])) {
        if(!$_POST['email']) {
            $errors[] = "Email is required.";
        }
        if(!$_POST['password']) {
            $errors[] = "Password is required.";
        }
        if(empty($errors)) {
            $user = login_account($_POST['email'], $_POST['password']);
            if(!empty($user)) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];

                header("Location: account");
            } else {    
                $errors[] = "The email that you've entered does not match any account.";
            }
        }
    } else {
        $_POST = [
            'email' => '',
            'password' => '',
        ];
    }
?>
<?php include "layouts/_header.php"; ?>
    <?php include "layouts/_navigation.php"; ?>
    <main class="content">
        <section id="signin" class="container">
            <div id="signin-form">
                <?php if (!empty($errors)) { ?>
                    <?php include "layouts/_errors.php" ?>
                <?php } ?>
                <div class="form card">
                    <div class="auth-visual" aria-hidden="true">
                        <span>Good food. Good moments.</span>
                    </div>
                    <div class="auth-content">
                        <p class="auth-eyebrow">Welcome back</p>
                        <h1>Log in to your account.</h1>
                        <p class="auth-description">Sign in to continue to Food Festive.</p>
                        <form method="post">
                            <div class="input-control">
                                <label for="login-email">Email address</label>
                                <input id="login-email" type="email" name="email" class="input-field input-md" value="<?= htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" required />
                            </div>
                            <div class="input-control">
                                <label for="login-password">Password</label>
                                <input id="login-password" type="password" name="password" class="input-field input-md" autocomplete="current-password" required />
                            </div>
                            <div class="input-control">
                                <input type="submit" name="submit" class="btn btn-md btn-rounded" value="Login" />
                            </div>
                            <div id="signup-account">
                                <p>Don't have an account? <a href="/register">Create one</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include "layouts/_footer.php"; ?>
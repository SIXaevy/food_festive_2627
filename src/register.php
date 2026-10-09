<?php
    include "models/registration.php";
    include "session.php";

    $auth_page = 'register';
    $errors = [];

    if(isset($_SESSION['id'])) {
        header("Location: account");
    }

    if(isset($_POST['submit'])) {
        if(!$_POST['name']) {
            $errors[] = "Name is required.";
        }
        if(!$_POST['email']) {
            $errors[] = "Email is required.";
        }
        if(!$_POST['password']) {
            $errors[] = "Password is required.";
        }
        if($_POST['password'] != $_POST['confirm_password']) {
            $errors[] = "You must confirm your password.";
        }
        if(empty($errors)) {
            if(!check_existing_email($_POST['email'])) {
                $user_type = 'user';
                $user = save_registration($_POST['name'],$_POST['email'], $_POST['password']);
                if(!empty($user)) {
                    header("Location: /login");
                    exit;
                } else {
                    $errors[] = "There was an error registering your account.";
                }
            } else {
                $errors[] = "Email address already exist.";
            }
        }
    } else {
        $_POST = [
            'name' => '',
            'password' => '',
            'email' => ''
        ];
    }
?>
<?php include "layouts/_header.php"; ?>
    <?php include "layouts/_navigation.php"; ?>
    <main class="content">
        <section id="signup" class="container">
            <div id="signup-form">
                <?php if (!empty($errors)) { ?>
                    <?php include "layouts/_errors.php" ?>
                <?php } ?>
                <div class="form card">
                    <div class="auth-visual" aria-hidden="true">
                        <span>Gather around something delicious.</span>
                    </div>
                    <div class="auth-content">
                        <p class="auth-eyebrow">Join Food Festive</p>
                        <h1>Create your account.</h1>
                        <p class="auth-description">A few details and you’ll be ready to get started.</p>
                        <form method="post">
                            <div class="input-control">
                                <label for="register-name">Name</label>
                                <input id="register-name" type="text" name="name" class="input-field input-md" value="<?= htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="name" required />
                            </div>
                            <div class="input-control">
                                <label for="register-email">Email address</label>
                                <input id="register-email" type="email" name="email" class="input-field input-md" value="<?= htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" required />
                            </div>
                            <div class="input-control">
                                <label for="register-password">Password</label>
                                <input id="register-password" type="password" name="password" class="input-field input-md" autocomplete="new-password" required />
                            </div>
                            <div class="input-control">
                                <label for="confirm-password">Confirm password</label>
                                <input id="confirm-password" type="password" name="confirm_password" class="input-field input-md" autocomplete="new-password" required />
                            </div>
                            <div class="input-control">
                                <input type="submit" name="submit" class="btn btn-md btn-rounded" value="Create account" />
                            </div>
                            <div id="signup-account">
                                <p>Already have an account? <a href="/login">Log in</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include "layouts/_footer.php"; ?>
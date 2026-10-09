<?php 
    include "session.php"; 
    include "models/dish.php";
    include "require_login.php";

    if(isset($_POST['submit'])) {
        $thumbnail_upload = $_FILES['thumbnail'] ?? [
            'tmp_name' => '',
            'size' => 0,
            'error' => UPLOAD_ERR_NO_FILE,
        ];
        $errors = validate_form_dish(
            $_POST['name'],
            $_POST['price'],
            $thumbnail_upload['size'],
            $thumbnail_upload['error']
        );
        if(empty($errors)) {
            $thumbnail = file_get_contents($thumbnail_upload['tmp_name']);
            if($thumbnail === false) {
                $errors[] = "Could not read the uploaded image. Please try again.";
            } else {
                $save_dish = save_dish($_POST['name'], $_SESSION['id'], $_POST['price'], $thumbnail);
                if($save_dish) {
                    $_SESSION['flash_message'] = "You have successfully added a dish.";
                    header("Location: /dishes");
                } else {
                    $errors[] = "Could not create a dish. Please try again later.";
                }
            }
        }
    } else {
        $_POST = [
            'name' => '',
            'price' => '',
            'thumbnail' => '',
        ];
    }
?>

<?php include "layouts/_header.php"; ?>
    <?php include "layouts/_navigation.php"; ?>
    <main class="account">
        <section id="new-dish" class="container">
            <div id="account-container">
                <?php include "layouts/_account-navigation.php" ?>
                <div id="account-preview">
                    <h3>Add New Dish</h3>
                    <?php include "layouts/_form-dish.php" ?>
                </div>
            </div>
        </section>
    </main>
<?php include "layouts/_footer.php"; ?>
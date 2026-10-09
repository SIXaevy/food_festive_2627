<?php 
    include "session.php"; 
    include "models/dish.php";
    include "require_login.php";

    if (array_key_exists("id", $_GET)) {
        $dish = view_dish($_GET['id']);
        $dish_id = $dish['id'];
        if(isset($_POST['submit'])) {
            $thumbnail_upload = $_FILES['thumbnail'] ?? [
                'tmp_name' => '',
                'size' => 0,
                'error' => UPLOAD_ERR_NO_FILE,
            ];
            $_POST['thumbnail'] = $_POST['thumbnail'] ?? base64_encode($dish['thumbnail']);
            $errors = validate_form_dish(
                $_POST['name'],
                $_POST['price'],
                $thumbnail_upload['size'],
                $thumbnail_upload['error'],
                false
            );
            if(empty($errors)) {
                $thumbnail = $thumbnail_upload['error'] === UPLOAD_ERR_NO_FILE
                    ? $dish['thumbnail']
                    : file_get_contents($thumbnail_upload['tmp_name']);
                if($thumbnail === false) {
                    $errors[] = "Could not read the uploaded image. Please try again.";
                } else {
                    $save_dish = save_dish($_POST['name'], $_SESSION['id'], $_POST['price'], $thumbnail, $dish_id);
                    if($save_dish) {
                        $_SESSION['flash_message'] = "You have successfully updated a dish.";
                        header("Location: /dishes");
                    } else {
                        $errors[] = "Could not update the dish. Please try again later.";
                    }
                }
            }
        } else {
            $_POST = [
                'name' => isset($_POST['name']) ? $_POST['name'] : $dish['name'],
                'price' => isset($_POST['price']) ? $_POST['price'] : $dish['price'],
                'thumbnail' => isset($_POST['thumbnail']) ? $_POST['thumbnail'] : base64_encode($dish['thumbnail']),
            ];
        }
    }
?>

<?php include "layouts/_header.php"; ?>
    <?php include "layouts/_navigation.php"; ?>
    <main class="account">
        <section id="edit-dish" class="container">
            <div id="account-container">
                <?php include "layouts/_account-navigation.php" ?>
                <div id="account-preview">
                    <h3>Edit Dish</h3>
                    <?php include "layouts/_form-dish.php" ?>
                </div>
            </div>
        </section>
    </main>
<?php include "layouts/_footer.php"; ?>
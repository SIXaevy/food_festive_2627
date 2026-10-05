<div id="form-dish">
    <?php if (!empty($errors)) { ?>
        <?php include "layouts/_errors.php" ?>
    <?php } ?>
    <form method="post" enctype="multipart/form-data">
        <div class="input-control">
            <label for="name">Name: </label>
            <input type="text" name="name" class="input-field input-sm" value="<?= $_POST['name'] ?>" />
        </div>
        <div class="input-control">
            <label for="name">Price: </label>
            <input type="number" name="price" class="input-field input-sm" value="<?= $_POST['price'] ?>" />
        </div>
        <div class="input-control">
            <label for="category">Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="input-field input-sm" />
            <input type="hidden" value="<?= $_POST['thumbnail'] ?>" name="thumbnail">
        </div>
        <?php if(!empty($_POST['thumbnail'])) { ?>
            <div class="thumbnail">
                <img src="data:image/jpeg;base64,<?= $_POST['thumbnail'] ?>" alt="" width="300">
            </div>
        <?php } ?>
        <div class="input-control">
            <input type="submit" name="submit" class="btn btn-sm btn-rounded" value="Save" />
        </div>
    </form>
</div>
<div class="alert alert-success">
    <p>
        <?= $_SESSION['flash_message'] ?>
        <?php unset($_SESSION['flash_message']); ?>
    </p>
</div>
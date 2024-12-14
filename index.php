<?php
include('includes/header.php');
require_once '../config.php';
require_once '../functions.php';
?>
<div class="d-flex">
    <?php include('includes/sidebar.php'); ?>
    <div class="content p-4">
        <?php include('pages/' . (@$_GET['page'] ?? 'dashboard') . '.php'); ?>
    </div>
</div>
<?php include('includes/footer.php'); ?>
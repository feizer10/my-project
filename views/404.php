<?php 
require_once 'views/header.php';
require_once 'includes/translations.php';
?>

<div class="container text-center py-5">
    <h1 class="display-1"><?= t('404_title') ?></h1>
    <p class="lead"><?= t('404_message') ?></p>
    <a href="/" class="btn btn-primary">
        <i class="fas fa-home me-2"></i>
        <?= t('back_to_home') ?>
    </a>
</div>

<?php require_once 'views/footer.php'; ?> 
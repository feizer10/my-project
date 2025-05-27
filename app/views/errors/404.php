<?php
$title = '404 - Сторінку не знайдено';
require_once APP_PATH . '/views/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h1 class="display-1">404</h1>
            <h2>Сторінку не знайдено</h2>
            <p class="lead">На жаль, запитана сторінка не існує.</p>
            <a href="/" class="btn btn-primary">Повернутися на головну</a>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/includes/footer.php'; ?> 
<?php
$title = 'Сторінку не знайдено';
require_once APP_PATH . '/views/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mt-5">
                <h1 class="display-1 text-primary">404</h1>
                <h2 class="mb-4">Сторінку не знайдено</h2>
                <p class="lead mb-5">Вибачте, але сторінка, яку ви шукаєте, не існує або була переміщена.</p>
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="bi bi-house-door"></i> Повернутися на головну
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/includes/footer.php'; ?> 
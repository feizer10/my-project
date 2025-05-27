<?php require_once 'views/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-exclamation-triangle text-warning display-1 mb-4"></i>
                    <h2 class="card-title mb-4">Помилка</h2>
                    
                    <?php if (isset($error)): ?>
                        <p class="card-text text-muted mb-4">
                            <?= htmlspecialchars($error) ?>
                        </p>
                    <?php else: ?>
                        <p class="card-text text-muted mb-4">
                            Виникла помилка при обробці вашого запиту. Будь ласка, спробуйте пізніше.
                        </p>
                    <?php endif; ?>

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <a href="/" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>
                            На головну
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Повернутися назад
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?> 
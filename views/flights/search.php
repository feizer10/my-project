<?php require_once 'views/header.php'; ?>

<div class="container mt-4">
    <div class="search-summary bg-light p-4 rounded mb-4">
        <h2>Результати пошуку</h2>
        <p class="mb-0">
            <strong>Звідки:</strong> <?= htmlspecialchars($from) ?><br>
            <strong>Куди:</strong> <?= htmlspecialchars($to) ?><br>
            <strong>Дата:</strong> <?= htmlspecialchars($date) ?><br>
            <strong>Пасажирів:</strong> <?= htmlspecialchars($passengers) ?>
        </p>
    </div>

    <?php if (!empty($flights)): ?>
        <?php foreach ($flights_by_day as $day => $day_flights): ?>
            <div class="day-section">
                <h3 class="day-header"><?= htmlspecialchars($day) ?></h3>
                <div class="row">
                    <?php foreach ($day_flights as $flight): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card flight-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <?= htmlspecialchars($flight['airline']['name']) ?>
                                            </h5>
                                            <small class="text-muted">
                                                Рейс <?= htmlspecialchars($flight['flight_number']) ?>
                                            </small>
                                        </div>
                                        <span class="badge <?= $flight['status'] === 'scheduled' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= htmlspecialchars(ucfirst($flight['status'])) ?>
                                        </span>
                                    </div>

                                    <div class="row">
                                        <div class="col-5">
                                            <div class="flight-time">
                                                <?= date('H:i', strtotime($flight['departure']['time'])) ?>
                                            </div>
                                            <div>
                                                <?= htmlspecialchars($flight['departure']['city']) ?>
                                                (<?= htmlspecialchars($flight['departure']['airport']) ?>)
                                            </div>
                                            <?php if ($flight['departure']['terminal']): ?>
                                                <small class="text-muted">
                                                    Термінал <?= htmlspecialchars($flight['departure']['terminal']) ?>
                                                    <?php if ($flight['departure']['gate']): ?>
                                                        , Гейт <?= htmlspecialchars($flight['departure']['gate']) ?>
                                                    <?php endif; ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-2 text-center">
                                            <i class="fas fa-plane"></i>
                                        </div>
                                        <div class="col-5">
                                            <div class="flight-time">
                                                <?= date('H:i', strtotime($flight['arrival']['time'])) ?>
                                            </div>
                                            <div>
                                                <?= htmlspecialchars($flight['arrival']['city']) ?>
                                                (<?= htmlspecialchars($flight['arrival']['airport']) ?>)
                                            </div>
                                            <?php if ($flight['arrival']['terminal']): ?>
                                                <small class="text-muted">
                                                    Термінал <?= htmlspecialchars($flight['arrival']['terminal']) ?>
                                                    <?php if ($flight['arrival']['gate']): ?>
                                                        , Гейт <?= htmlspecialchars($flight['arrival']['gate']) ?>
                                                    <?php endif; ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if ($flight['departure']['delay'] || $flight['arrival']['delay']): ?>
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <?php if ($flight['departure']['delay']): ?>
                                                <div>Затримка вильоту: <?= htmlspecialchars($flight['departure']['delay']) ?> хв.</div>
                                            <?php endif; ?>
                                            <?php if ($flight['arrival']['delay']): ?>
                                                <div>Затримка прибуття: <?= htmlspecialchars($flight['arrival']['delay']) ?> хв.</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-3">
                                        <a href="/booking/create?flight=<?= htmlspecialchars($flight['flight_number']) ?>&passengers=<?= htmlspecialchars($passengers) ?>" 
                                           class="btn btn-primary">Забронювати</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">
            <h4>Рейси не знайдено</h4>
            <p>На жаль, ми не змогли знайти рейси за вашим запитом. Спробуйте змінити параметри пошуку.</p>
            <a href="/" class="btn btn-primary">Новий пошук</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/footer.php'; ?> 
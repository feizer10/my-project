<?php require_once 'views/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Мої бронювання</h2>
                <a href="/flights" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Забронювати новий рейс
                </a>
            </div>

            <?php if (empty($bookings)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-ticket-alt text-muted display-1 mb-4"></i>
                        <h3 class="text-muted">У вас поки немає бронювань</h3>
                        <p class="text-muted mb-4">
                            Знайдіть та забронюйте свій перший рейс прямо зараз!
                        </p>
                        <a href="/flights" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>
                            Знайти рейси
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($bookings as $booking): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card booking-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">
                                            Рейс <?= htmlspecialchars($booking['flight_number']) ?>
                                        </h5>
                                        <span class="badge bg-<?= $booking['status_color'] ?>">
                                            <?= htmlspecialchars($booking['status']) ?>
                                        </span>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <div class="flight-time">
                                                <?= date('H:i', strtotime($booking['departure_time'])) ?>
                                            </div>
                                            <div>
                                                <?= htmlspecialchars($booking['departure_city']) ?>
                                                (<?= htmlspecialchars($booking['departure_airport']) ?>)
                                            </div>
                                        </div>
                                        <div class="col-2 text-center">
                                            <i class="fas fa-plane"></i>
                                        </div>
                                        <div class="col-5">
                                            <div class="flight-time">
                                                <?= date('H:i', strtotime($booking['arrival_time'])) ?>
                                            </div>
                                            <div>
                                                <?= htmlspecialchars($booking['arrival_city']) ?>
                                                (<?= htmlspecialchars($booking['arrival_airport']) ?>)
                                            </div>
                                        </div>
                                    </div>

                                    <div class="booking-details">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Дата вильоту</small>
                                                <div><?= date('d.m.Y', strtotime($booking['departure_time'])) ?></div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Пасажири</small>
                                                <div><?= $booking['passengers'] ?> особи</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Загальна вартість</small>
                                            <div class="h5 mb-0"><?= number_format($booking['total_price'], 2) ?> грн</div>
                                        </div>
                                        <div>
                                            <a href="/booking/details/<?= $booking['id'] ?>" class="btn btn-outline-primary">
                                                Деталі
                                            </a>
                                            <?php if ($booking['can_cancel']): ?>
                                                <a href="/booking/cancel/<?= $booking['id'] ?>" 
                                                   class="btn btn-outline-danger ms-2"
                                                   onclick="return confirm('Ви впевнені, що хочете скасувати бронювання?')">
                                                    Скасувати
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?> 
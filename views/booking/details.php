<?php require_once 'views/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title">Деталі бронювання</h2>
                        <span class="badge bg-<?= $booking['status'] === 'pending' ? 'warning' : 
                                               ($booking['status'] === 'confirmed' ? 'success' : 'danger') ?>">
                            <?= htmlspecialchars(ucfirst($booking['status'])) ?>
                        </span>
                    </div>

                    <!-- Інформація про рейс -->
                    <div class="flight-info mb-4">
                        <h4 class="mb-3">Інформація про рейс</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <div class="flight-time h4">
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
                                        <div class="flight-time h4">
                                            <?= date('H:i', strtotime($booking['arrival_time'])) ?>
                                        </div>
                                        <div>
                                            <?= htmlspecialchars($booking['arrival_city']) ?>
                                            (<?= htmlspecialchars($booking['arrival_airport']) ?>)
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">Дата вильоту</small>
                                    <div><?= date('d.m.Y', strtotime($booking['departure_time'])) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Інформація про пасажирів -->
                    <div class="passengers-info mb-4">
                        <h4 class="mb-3">Пасажири</h4>
                        <?php foreach ($booking['passengers'] as $index => $passenger): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Пасажир <?= $index + 1 ?></h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Ім'я</small>
                                            <div class="mb-2">
                                                <?= htmlspecialchars($passenger['first_name']) ?>
                                            </div>
                                            <small class="text-muted">Прізвище</small>
                                            <div>
                                                <?= htmlspecialchars($passenger['last_name']) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Номер паспорта</small>
                                            <div class="mb-2">
                                                <?= htmlspecialchars($passenger['passport_number']) ?>
                                            </div>
                                            <small class="text-muted">Дата народження</small>
                                            <div>
                                                <?= date('d.m.Y', strtotime($passenger['birth_date'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Контактна інформація -->
                    <div class="contact-info mb-4">
                        <h4 class="mb-3">Контактна інформація</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Email</small>
                                        <div class="mb-2">
                                            <?= htmlspecialchars($booking['contact_email']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Телефон</small>
                                        <div>
                                            <?= htmlspecialchars($booking['contact_phone']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Інформація про оплату -->
                    <div class="payment-info mb-4">
                        <h4 class="mb-3">Інформація про оплату</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Вартість за пасажира</small>
                                        <div class="mb-2">
                                            <?= number_format($booking['flight_price'], 2) ?> грн
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Кількість пасажирів</small>
                                        <div class="mb-2">
                                            <?= count($booking['passengers']) ?>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Загальна вартість</h5>
                                    <div class="h4 mb-0">
                                        <?= number_format($booking['total_price'], 2) ?> грн
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки дій -->
                    <div class="d-flex justify-content-between">
                        <a href="/booking" class="btn btn-outline-secondary">
                            Повернутися до списку
                        </a>
                        <?php if ($booking['status'] === 'pending'): ?>
                            <a href="/booking/cancel/<?= $booking['id'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Ви впевнені, що хочете скасувати бронювання?')">
                                Скасувати бронювання
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?> 
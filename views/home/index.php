<?php require_once 'views/header.php'; ?>

<?php
// Функція перекладу статусів
function translateStatus($status) {
    $translations = [
        'scheduled' => 'За розкладом',
        'delayed' => 'Затримується',
        'cancelled' => 'Скасовано',
        'pending' => 'Очікує',
        'confirmed' => 'Підтверджено'
    ];
    return isset($translations[$status]) ? $translations[$status] : $status;
}
?>

<div class="container mt-4">
    <!-- Форма пошуку рейсів -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title mb-4">Пошук рейсів</h3>
            <form action="/flights/search" method="GET">
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="departure" class="form-label">Місто відправлення</label>
                        <select name="departure" id="departure" class="form-select" required>
                            <option value="">Оберіть місто</option>
                            <option value="KBP">Київ (KBP)</option>
                            <option value="LWO">Львів (LWO)</option>
                            <option value="ODS">Одеса (ODS)</option>
                        </select>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label for="arrival" class="form-label">Місто прибуття</label>
                        <select name="arrival" id="arrival" class="form-select" required>
                            <option value="">Оберіть місто</option>
                            <option value="KBP">Київ (KBP)</option>
                            <option value="LWO">Львів (LWO)</option>
                            <option value="ODS">Одеса (ODS)</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date" class="form-label">Дата</label>
                        <input type="date" class="form-control" id="date" name="date" required 
                               min="<?= date('Y-m-d') ?>" 
                               value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Знайти рейси
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Останні рейси -->
    <?php if (!empty($latestFlights)): ?>
        <h3 class="mb-4">Найближчі рейси</h3>
        <div class="row">
            <?php foreach ($latestFlights as $flight): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">
                                    Рейс <?= htmlspecialchars($flight['flight_number']) ?>
                                </h5>
                                <span class="badge bg-<?= $flight['status'] === 'scheduled' ? 'success' : 
                                                      ($flight['status'] === 'delayed' ? 'warning' : 'danger') ?>">
                                    <?= translateStatus($flight['status']) ?>
                                </span>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-5">
                                    <div class="h4 mb-0">
                                        <?= date('H:i', strtotime($flight['departure_time'])) ?>
                                    </div>
                                    <div>
                                        <?= htmlspecialchars($flight['departure_city']) ?>
                                        (<?= htmlspecialchars($flight['departure_airport']) ?>)
                                    </div>
                                </div>
                                <div class="col-2 text-center">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <div class="col-5">
                                    <div class="h4 mb-0">
                                        <?= date('H:i', strtotime($flight['arrival_time'])) ?>
                                    </div>
                                    <div>
                                        <?= htmlspecialchars($flight['arrival_city']) ?>
                                        (<?= htmlspecialchars($flight['arrival_airport']) ?>)
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Вартість від</small>
                                    <div class="h5 mb-0"><?= number_format($flight['price'], 2) ?> грн</div>
                                </div>
                                <a href="/booking/create?flight=<?= $flight['flight_number'] ?>" 
                                   class="btn btn-primary">
                                    Забронювати
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Бронювання користувача -->
    <?php if ($isLoggedIn && !empty($userBookings)): ?>
        <h3 class="mb-4">Мої останні бронювання</h3>
        <div class="row">
            <?php foreach ($userBookings as $booking): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">
                                    Рейс <?= htmlspecialchars($booking['flight_number']) ?>
                                </h5>
                                <span class="badge bg-<?= $booking['status'] === 'pending' ? 'warning' : 
                                                      ($booking['status'] === 'confirmed' ? 'success' : 'danger') ?>">
                                    <?= translateStatus($booking['status']) ?>
                                </span>
                            </div>

                            <div class="booking-details">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Дата вильоту</small>
                                        <div><?= date('d.m.Y', strtotime($booking['departure_time'])) ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Пасажири</small>
                                        <div><?= $booking['passengers_count'] ?> особи</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="/booking/details/<?= $booking['id'] ?>" class="btn btn-outline-primary">
                                    Деталі
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/footer.php'; ?> 
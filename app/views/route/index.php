<?php require_once APP_PATH . '/views/includes/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Пошук маршрутів</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/route" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="from_city" class="form-label">Місто відправлення</label>
                            <select name="cities[]" class="form-select" required>
                                <option value="">Оберіть місто</option>
                                <?php foreach ($cities as $city => $coords): ?>
                                    <option value="<?php echo htmlspecialchars($city); ?>"
                                            <?php echo in_array($city, $selectedCities) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($city); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="to_city" class="form-label">Місто прибуття</label>
                            <select name="cities[]" class="form-select" required>
                                <option value="">Оберіть місто</option>
                                <?php foreach ($cities as $city => $coords): ?>
                                    <option value="<?php echo htmlspecialchars($city); ?>"
                                            <?php echo in_array($city, $selectedCities) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($city); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Пошук
                            </button>
                        </div>
                    </div>
                </form>

                <?php if (!empty($route)): ?>
                    <div class="alert alert-success">
                        <h6>Знайдений маршрут:</h6>
                        <p class="mb-0">
                            <?php echo implode(' → ', array_map('htmlspecialchars', $route)); ?>
                            (<?php echo number_format($totalDistance, 2); ?> км)
                        </p>
                    </div>

                    <?php if (!empty($flights)): ?>
                        <h6 class="mt-4">Доступні рейси:</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Номер рейсу</th>
                                        <th>Відправлення</th>
                                        <th>Ціна</th>
                                        <th>Місця</th>
                                        <th>Дії</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($flights as $flight): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($flight['flight_number']); ?></td>
                                            <td><?php echo date('d.m.Y H:i', strtotime($flight['departure_time'])); ?></td>
                                            <td><?php echo number_format($flight['price'], 2); ?> грн</td>
                                            <td><?php echo $flight['available_seats']; ?></td>
                                            <td>
                                                <form method="POST" action="/route" style="display: inline;">
                                                    <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                                                    <button type="submit" name="add_flight" class="btn btn-sm btn-success">
                                                        <i class="bi bi-plus-circle"></i> Додати
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php elseif (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Вибрані рейси</h5>
            </div>
            <div class="card-body">
                <?php if (empty($selectedFlights)): ?>
                    <p class="text-muted mb-0">Ви ще не вибрали жодного рейсу</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Рейс</th>
                                    <th>Маршрут</th>
                                    <th>Ціна</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($selectedFlights as $flight): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($flight['flight_number']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($flight['from_city']); ?> →
                                            <?php echo htmlspecialchars($flight['to_city']); ?>
                                        </td>
                                        <td><?php echo number_format($flight['price'], 2); ?> грн</td>
                                        <td>
                                            <form method="POST" action="/route" style="display: inline;">
                                                <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                                                <button type="submit" name="remove_flight" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Загальна вартість:</strong></td>
                                    <td colspan="2"><strong><?php echo number_format($totalPrice, 2); ?> грн</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="/booking" class="btn btn-primary">
                            <i class="bi bi-cart-check"></i> Оформити бронювання
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/includes/footer.php'; ?> 
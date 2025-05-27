<?php require_once APP_PATH . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-perforated"></i> <?php echo $title; ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle-fill"></i> <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($route): ?>
                        <div class="mb-4">
                            <h5 class="card-subtitle mb-3 text-primary">
                                <i class="bi bi-geo-alt"></i> Маршрут:
                            </h5>
                            <div class="d-flex align-items-center">
                                <span class="h5 mb-0"><?php echo htmlspecialchars($route['from_city']); ?></span>
                                <i class="bi bi-arrow-right mx-3"></i>
                                <span class="h5 mb-0"><?php echo htmlspecialchars($route['to_city']); ?></span>
                            </div>
                            <div class="text-muted mt-2">
                                <i class="bi bi-rulers"></i> Відстань: <?php echo number_format($route['distance'], 2); ?> км
                            </div>
                        </div>

                        <?php if (!empty($flights)): ?>
                            <?php if ($selectedFlight): ?>
                                <div class="alert alert-info mb-4">
                                    <h5 class="alert-heading">
                                        <i class="bi bi-info-circle"></i> Обраний рейс:
                                    </h5>
                                    <p class="mb-0">
                                        Рейс <?php echo $selectedFlight['flight_number']; ?><br>
                                        Дата та час: <?php echo date('d.m.Y H:i', strtotime($selectedFlight['departure_time'])); ?><br>
                                        Ціна: <?php echo number_format($selectedFlight['price'], 0); ?> грн<br>
                                        Вільних місць: <?php echo $selectedFlight['available_seats']; ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="/flights/book/<?php echo $route['id']; ?>" class="needs-validation" novalidate>
                                <div class="mb-4">
                                    <label for="flight_id" class="form-label fw-bold">Оберіть рейс:</label>
                                    <select name="flight_id" id="flight_id" class="form-select form-select-lg mb-3" required>
                                        <option value="">Оберіть дату та час вильоту...</option>
                                        <?php foreach ($flights as $flight): ?>
                                            <option value="<?php echo $flight['id']; ?>" 
                                                <?php echo ($selectedFlight && $selectedFlight['id'] == $flight['id']) ? 'selected' : ''; ?>>
                                                <?php echo date('d.m.Y H:i', strtotime($flight['departure_time'])); ?> - 
                                                <?php echo number_format($flight['price'], 0); ?> грн
                                                (<?php echo $flight['available_seats']; ?> місць)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Будь ласка, оберіть рейс
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="passenger_name" class="form-label fw-bold">Ім'я та прізвище пасажира:</label>
                                    <input type="text" class="form-control form-control-lg" id="passenger_name" 
                                           name="passenger_name" required>
                                    <div class="invalid-feedback">
                                        Будь ласка, введіть ім'я та прізвище пасажира
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="passenger_email" class="form-label fw-bold">Email:</label>
                                    <input type="email" class="form-control form-control-lg" id="passenger_email" 
                                           name="passenger_email" required>
                                    <div class="invalid-feedback">
                                        Будь ласка, введіть коректну email адресу
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-cart-check"></i> Забронювати квиток
                                    </button>
                                    <a href="/route" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Повернутися до пошуку
                                    </a>
                                </div>
                            </form>

                            <script>
                            // Включаем валидацию форм Bootstrap
                            (function () {
                                'use strict'
                                var forms = document.querySelectorAll('.needs-validation')
                                Array.prototype.slice.call(forms)
                                    .forEach(function (form) {
                                        form.addEventListener('submit', function (event) {
                                            if (!form.checkValidity()) {
                                                event.preventDefault()
                                                event.stopPropagation()
                                            }
                                            form.classList.add('was-validated')
                                        }, false)
                                    })

                                // Обновляем информацию о выбранном рейсе при изменении
                                document.getElementById('flight_id').addEventListener('change', function() {
                                    this.form.submit();
                                });
                            })()
                            </script>
                        <?php else: ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-circle"></i>
                                На жаль, на даний момент немає доступних рейсів за цим маршрутом.
                            </div>
                            <div class="d-grid">
                                <a href="/route" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Повернутися до пошуку
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/includes/footer.php'; ?> 
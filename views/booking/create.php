<?php require_once 'views/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Бронювання рейсу</h2>

                    <form action="/booking/store" method="POST">
                        <input type="hidden" name="flight_number" value="<?= htmlspecialchars($flight_number) ?>">
                        
                        <!-- Інформація про рейс -->
                        <div class="flight-info mb-4">
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <div class="flight-time h4">12:00</div>
                                    <div>Київ (KBP)</div>
                                </div>
                                <div class="col-2 text-center">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <div class="col-5">
                                    <div class="flight-time h4">14:00</div>
                                    <div>Львів (LWO)</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Форма бронювання -->
                        <div class="mb-3">
                            <label for="passengers" class="form-label">Кількість пасажирів</label>
                            <select name="passengers" id="passengers" class="form-select" required>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= $passengers == $i ? 'selected' : '' ?>>
                                        <?= $i ?> <?= $i == 1 ? 'пасажир' : 'пасажири' ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <!-- Інформація про пасажирів -->
                        <div id="passengers-info">
                            <h4 class="mb-3">Інформація про пасажирів</h4>
                            
                            <?php for($i = 1; $i <= $passengers; $i++): ?>
                                <div class="passenger-form mb-4">
                                    <h5 class="mb-3">Пасажир <?= $i ?></h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name_<?= $i ?>" class="form-label">Ім'я</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="first_name_<?= $i ?>" 
                                                   name="passengers[<?= $i ?>][first_name]" 
                                                   required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="last_name_<?= $i ?>" class="form-label">Прізвище</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="last_name_<?= $i ?>" 
                                                   name="passengers[<?= $i ?>][last_name]" 
                                                   required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="passport_<?= $i ?>" class="form-label">Номер паспорта</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="passport_<?= $i ?>" 
                                                   name="passengers[<?= $i ?>][passport]" 
                                                   required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="birth_date_<?= $i ?>" class="form-label">Дата народження</label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   id="birth_date_<?= $i ?>" 
                                                   name="passengers[<?= $i ?>][birth_date]" 
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <hr class="my-4">

                        <!-- Контактна інформація -->
                        <h4 class="mb-3">Контактна інформація</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="contact[email]" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="tel" class="form-control" id="phone" name="contact[phone]" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Підсумок -->
                        <div class="booking-summary mb-4">
                            <h4>Підсумок замовлення</h4>
                            <div class="d-flex justify-content-between">
                                <span>Вартість за пасажира:</span>
                                <span>2000 грн</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Кількість пасажирів:</span>
                                <span><?= $passengers ?></span>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <strong>Загальна вартість:</strong>
                                <strong><?= number_format(2000 * $passengers, 2) ?> грн</strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Підтвердити бронювання
                            </button>
                            <a href="/flights" class="btn btn-outline-secondary">
                                Повернутися до пошуку
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('passengers').addEventListener('change', function() {
    // В майбутньому тут буде динамічне оновлення форми
    location.href = `/booking/create?flight=<?= htmlspecialchars($flight_number) ?>&passengers=${this.value}`;
});
</script>

<?php require_once 'views/footer.php'; ?> 
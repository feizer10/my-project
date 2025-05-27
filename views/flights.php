<?php 
require_once 'views/header.php';
require_once 'includes/translations.php';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доступні рейси - Система бронювання авіаквитків</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Авіаквитки</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="/flights">Рейси</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/booking">Бронювання</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title mb-4"><?= t('search_flights') ?></h3>
                <form action="/flights/search" method="GET">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="departure" class="form-label"><?= t('departure_city') ?></label>
                            <select name="departure" id="departure" class="form-select" required>
                                <option value=""><?= t('select_city') ?></option>
                                <?php foreach ($airports as $airport): ?>
                                    <option value="<?= htmlspecialchars($airport['code']) ?>"
                                        <?= isset($_GET['departure']) && $_GET['departure'] === $airport['code'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($airport['city']) ?> (<?= htmlspecialchars($airport['code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="arrival" class="form-label"><?= t('arrival_city') ?></label>
                            <select name="arrival" id="arrival" class="form-select" required>
                                <option value=""><?= t('select_city') ?></option>
                                <?php foreach ($airports as $airport): ?>
                                    <option value="<?= htmlspecialchars($airport['code']) ?>"
                                        <?= isset($_GET['arrival']) && $_GET['arrival'] === $airport['code'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($airport['city']) ?> (<?= htmlspecialchars($airport['code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="date" class="form-label"><?= t('date') ?></label>
                            <input type="date" class="form-control" id="date" name="date" required 
                                   min="<?= date('Y-m-d') ?>" 
                                   value="<?= isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>
                            <?= t('search_flights') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <h2>Доступні рейси</h2>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Номер рейсу</th>
                        <th>Відправлення</th>
                        <th>Прибуття</th>
                        <th>Дата і час вильоту</th>
                        <th>Дата і час прибуття</th>
                        <th>Базова ціна</th>
                        <th>Статус</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($flights)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Рейсів не знайдено</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($flights as $flight): ?>
                            <tr>
                                <td><?= htmlspecialchars($flight['flight_number']) ?></td>
                                <td>
                                    <?= htmlspecialchars($flight['departure_city']) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($flight['departure_airport']) ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($flight['arrival_city']) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($flight['arrival_airport']) ?></small>
                                </td>
                                <td><?= date('d.m.Y H:i', strtotime($flight['departure_time'])) ?></td>
                                <td><?= date('d.m.Y H:i', strtotime($flight['arrival_time'])) ?></td>
                                <td><?= number_format($flight['base_price'], 2) ?> грн</td>
                                <td>
                                    <span class="badge bg-<?= $flight['status'] === 'scheduled' ? 'success' : 
                                        ($flight['status'] === 'delayed' ? 'warning' : 
                                        ($flight['status'] === 'cancelled' ? 'danger' : 'info')) ?>">
                                        <?= htmlspecialchars(ucfirst($flight['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/booking/<?= $flight['id'] ?>?passengers=<?= isset($_GET['passengers']) ? htmlspecialchars($_GET['passengers']) : '1' ?>" 
                                       class="btn btn-primary btn-sm">Забронювати</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/uk.js"></script>
    <script>
        flatpickr("#date", {
            locale: "uk",
            dateFormat: "Y-m-d",
            minDate: "today"
        });
    </script>
</body>
</html>

<?php require_once 'views/footer.php'; ?> 
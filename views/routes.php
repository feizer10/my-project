<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пошук маршрутів - Система бронювання авіаквитків</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .route-card {
            transition: transform 0.2s;
        }
        .route-card:hover {
            transform: translateY(-5px);
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">Авіаквитки</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/flights">Рейси</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/routes">Маршрути</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/booking">Бронювання</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Пошук складних маршрутів</h4>
                <form action="/routes" method="POST">
                    <div class="mb-4">
                        <label for="cities" class="form-label">Виберіть міста для вашого маршруту</label>
                        <select name="cities[]" id="cities" class="form-select" multiple required>
                            <?php foreach ($this->cities as $city => $connections): ?>
                                <option value="<?= htmlspecialchars($city) ?>" 
                                    <?= in_array($city, $selectedCities ?? []) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Виберіть два або більше міст у порядку відвідування</div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-4">Знайти маршрути</button>
                </form>
            </div>
        </div>

        <?php if (!empty($routes)): ?>
            <h3 class="mb-4">Знайдені маршрути</h3>
            <div class="row">
                <?php foreach ($routes as $route): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card route-card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <?= htmlspecialchars($route['from']) ?> → 
                                    <?= htmlspecialchars($route['to']) ?>
                                </h5>
                                <div class="card-text mt-3">
                                    <div class="mb-2">
                                        <strong>Маршрут:</strong><br>
                                        <span class="text-muted">
                                            <?= implode(' → ', array_map('htmlspecialchars', $route['path'])) ?>
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Кількість пересадок:</strong><br>
                                        <span class="text-muted"><?= $route['stops'] ?></span>
                                    </div>
                                    <div>
                                        <strong>Приблизний час у дорозі:</strong><br>
                                        <span class="text-muted">
                                            <?= number_format($route['estimated_time'], 1) ?> годин
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cities').select2({
                theme: 'bootstrap-5',
                placeholder: 'Виберіть міста',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html> 
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система бронювання авіаквитків</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/assets/images/airplane.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 40px;
        }
        .search-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .feature-card {
            border: none;
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #0d6efd;
        }
        .popular-route-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .popular-route-card:hover {
            transform: translateY(-5px);
        }
    </style>
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
                        <a class="nav-link" href="/flights">Рейси</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/routes">Маршрути</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/booking">Бронювання</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="display-4 mb-4">Знайдіть свій ідеальний рейс</h1>
                    <p class="lead mb-4">Зручний пошук та бронювання авіаквитків за найкращими цінами</p>
                </div>
                <div class="col-md-6">
                    <div class="search-box">
                        <form action="/flights/search" method="GET">
                            <div class="mb-3">
                                <label for="from" class="form-label">Звідки</label>
                                <select name="from" id="from" class="form-select" required>
                                    <?php foreach ($airports as $airport): ?>
                                        <option value="<?= $airport['id'] ?>"><?= htmlspecialchars($airport['city']) ?> (<?= htmlspecialchars($airport['code']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="to" class="form-label">Куди</label>
                                <select name="to" id="to" class="form-select" required>
                                    <?php foreach ($airports as $airport): ?>
                                        <option value="<?= $airport['id'] ?>"><?= htmlspecialchars($airport['city']) ?> (<?= htmlspecialchars($airport['code']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Дата вильоту</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="passengers" class="form-label">Пасажири</label>
                                    <select name="passengers" id="passengers" class="form-select">
                                        <?php for($i = 1; $i <= 9; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> <?= $i == 1 ? 'пасажир' : 'пасажири' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Знайти рейси</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <h2 class="text-center mb-4">Чому обирають нас</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <div class="feature-icon">✈️</div>
                        <h5 class="card-title">Широкий вибір рейсів</h5>
                        <p class="card-text">Понад 1000 напрямків по всьому світу</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <div class="feature-icon">💰</div>
                        <h5 class="card-title">Найкращі ціни</h5>
                        <p class="card-text">Гарантія найнижчої ціни на авіаквитки</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <div class="feature-icon">🎫</div>
                        <h5 class="card-title">Швидке бронювання</h5>
                        <p class="card-text">Бронюйте квитки за кілька хвилин</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <h2 class="text-center mb-4">Популярні напрямки</h2>
        <div class="row">
            <?php foreach ($popular_routes as $route): ?>
                <div class="col-md-4 mb-4">
                    <div class="card popular-route-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($route['from_city']) ?> → 
                                <?= htmlspecialchars($route['to_city']) ?>
                            </h5>
                            <p class="card-text">
                                <small class="text-muted">Від <?= number_format($route['min_price'], 2) ?> грн</small>
                            </p>
                            <a href="/flights/search?from=<?= $route['from_id'] ?>&to=<?= $route['to_id'] ?>" 
                               class="btn btn-outline-primary">Знайти рейси</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#from, #to').select2({
                theme: 'bootstrap-5',
                placeholder: 'Виберіть місто'
            });
        });
    </script>
</body>
</html> 
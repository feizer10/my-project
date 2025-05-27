<?php require_once 'includes/translations.php'; ?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('flight_booking_system') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            color: rgba(255,255,255,.8) !important;
        }
        .nav-link:hover {
            color: #fff !important;
        }
        .flight-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .flight-card:hover {
            transform: translateY(-5px);
        }
        .flight-time {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
        }
        .search-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .day-header {
            color: #0d6efd;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
        }
        .badge {
            padding: 0.5em 1em;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-plane-departure me-2"></i>
                FlightBooking
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/flights">
                            <i class="fas fa-search me-1"></i>
                            <?= t('search_flights') ?>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/booking">
                                <i class="fas fa-ticket me-1"></i>
                                <?= t('my_bookings') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile">
                                <i class="fas fa-user me-1"></i>
                                <?= t('profile') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                <?= t('logout') ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                <?= t('login') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">
                                <i class="fas fa-user-plus me-1"></i>
                                <?= t('register') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html> 
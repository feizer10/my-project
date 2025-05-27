<?php require_once 'views/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Пошук авіаквитків</h2>
                    
                    <form action="/flights/search" method="get">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="from" class="form-label">Звідки</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-plane-departure"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="from" 
                                           name="from" 
                                           placeholder="Введіть місто або код аеропорту"
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="to" class="form-label">Куди</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-plane-arrival"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="to" 
                                           name="to" 
                                           placeholder="Введіть місто або код аеропорту"
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="date" class="form-label">Дата вильоту</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date" 
                                           name="date" 
                                           value="<?= date('Y-m-d') ?>"
                                           min="<?= date('Y-m-d') ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="passengers" class="form-label">Кількість пасажирів</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="passengers" 
                                           name="passengers" 
                                           value="1"
                                           min="1"
                                           max="9"
                                           required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>
                                Знайти рейси
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Популярні напрямки</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="/flights/search?from=KBP&to=LHR" class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-plane-departure me-3 text-primary"></i>
                                    <div>
                                        <div class="fw-bold">Київ (KBP) → Лондон (LHR)</div>
                                        <small class="text-muted">Щоденні рейси</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="/flights/search?from=KBP&to=CDG" class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-plane-departure me-3 text-primary"></i>
                                    <div>
                                        <div class="fw-bold">Київ (KBP) → Париж (CDG)</div>
                                        <small class="text-muted">Щоденні рейси</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?> 
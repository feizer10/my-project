    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>FlightBooking</h5>
                    <p class="text-muted">
                        <?= t('footer_description') ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <h5><?= t('useful_links') ?></h5>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-decoration-none"><?= t('about') ?></a></li>
                        <li><a href="/contact" class="text-decoration-none"><?= t('contact') ?></a></li>
                        <li><a href="/help" class="text-decoration-none"><?= t('help') ?></a></li>
                        <li><a href="/terms" class="text-decoration-none"><?= t('terms') ?></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5><?= t('contact') ?></h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> +380 44 123 45 67</li>
                        <li><i class="fas fa-envelope me-2"></i> info@flightbooking.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> <?= t('kyiv_ukraine') ?></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> FlightBooking. <?= t('all_rights_reserved') ?></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Додаємо активний клас до поточного пункту меню
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html> 
            </main>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?> - Tous droits réservés</p>
                <p class="footer-version">v1.0</p>
            </footer>
        </div>
    </div>

    <?php
    // Vérifier si la session va expirer
    use App\Middlewares\SessionMiddleware;
    
    $willExpire = SessionMiddleware::willExpireSoon();
    $remainingTime = SessionMiddleware::getRemainingTime();
    ?>
    
    <?php if ($willExpire): ?>
        <div class="session-warning" id="sessionWarning">
            <i class="fas fa-clock"></i>
            <div>
                <div>⚠️ Votre session expire dans</div>
                <div class="countdown" id="sessionCountdown"><?= $remainingTime ?> secondes</div>
            </div>
        </div>
        <script>
            let remaining = <?= $remainingTime ?>;
            const countdownElement = document.getElementById('sessionCountdown');
            const warningElement = document.getElementById('sessionWarning');
            
            if (countdownElement) {
                const timer = setInterval(function() {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(timer);
                        warningElement.innerHTML = '<i class="fas fa-exclamation-circle"></i> Session expirée, redirection...';
                        setTimeout(function() {
                            window.location.href = '<?= BASE_URL ?>home/index?timeout=1';
                        }, 2000);
                    } else {
                        countdownElement.textContent = remaining + ' secondes';
                    }
                }, 1000);
            }
        </script>
    <?php endif; ?>

    <script src="<?= BASE_URL ?>js/script.js"></script>
</body>
</html>
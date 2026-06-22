    </main>

    <!-- Footer public -->
    <footer class="public-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4><?= SITE_NAME ?></h4>
                    <p>Préservant et partageant le patrimoine artistique depuis <?= date('Y') ?>.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Liens rapides</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>public/oeuvre">Œuvres</a></li>
                        <li><a href="<?= BASE_URL ?>public/auteur">Auteurs</a></li>
                        <li><a href="<?= BASE_URL ?>public/exposition">Expositions</a></li>
                        <li><a href="<?= BASE_URL ?>public/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Horaires</h4>
                    <p>Lundi - Vendredi : 10h - 18h</p>
                    <p>Samedi - Dimanche : 11h - 19h</p>
                    <p>Fermé le mardi</p>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <p><i class="fas fa-map-marker-alt"></i> 1 Rue du Musée, 75001 Paris</p>
                    <p><i class="fas fa-phone"></i> +33 1 23 45 67 89</p>
                    <p><i class="fas fa-envelope"></i> contact@museenational.fr</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?> - Tous droits réservés</p>
                <p class="footer-version">v2.0</p>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>js/public.js"></script>
</body>
</html>
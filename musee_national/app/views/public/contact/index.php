<div class="container">
    <div class="hero">
        <h1><i class="fas fa-envelope"></i> Contactez-nous</h1>
        <p>Une question ? Une suggestion ? N'hésitez pas à nous contacter.</p>
    </div>

    <div class="contact-grid">
        <!-- Formulaire -->
        <div class="contact-form-card">
            <h3>Envoyez-nous un message</h3>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($success)): ?>
                <form method="post" action="<?= BASE_URL ?>public/contact/send">
                    <div class="form-group">
                        <label>Nom complet *</label>
                        <input type="text" name="nom" class="form-control" required value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Sujet *</label>
                        <input type="text" name="sujet" class="form-control" required value="<?= htmlspecialchars($old['sujet'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" class="form-control" rows="5" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Coordonnées -->
        <div>
            <div class="contact-info-card">
                <h3>Nos coordonnées</h3>
                <p><i class="fas fa-map-marker-alt"></i> Musée National de Gitega, Gitega, Burundi</p>
                <p><i class="fas fa-phone"></i> +257 66642122</p>
                <p><i class="fas fa-envelope"></i> museenational@gitega.bi</p>
                <p><i class="fas fa-clock"></i> Lun-Ven: 9h-17h<br>Sam-Dim: 10h-16h</p>
            </div>

            <!-- Carte -->
            <div class="map-card">
                <h3>Nous trouver</h3>
                <div class="map-container">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=29.87%2C-3.46%2C29.99%2C-3.39&layer=mapnik&marker=-3.4264%2C29.9308" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
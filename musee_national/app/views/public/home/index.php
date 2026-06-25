<div class="container">
    <!-- Hero -->
    <div class="hero">
        <h1><i class="fas fa-landmark"></i> Bienvenue au Musée National</h1>
        <p>Découvrez notre collection exceptionnelle d'œuvres d'art à travers les époques et les civilisations.</p>
        <a href="<?= BASE_URL ?>public/oeuvre" class="btn btn-primary btn-hero">
            <i class="fas fa-search"></i> Explorer la collection
        </a>
    </div>

    <!-- Expositions en cours -->
    <?php if (!empty($expositions)): ?>
    <section style="margin-bottom: 50px;">
        <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Expositions en cours</h2>
        <p class="section-subtitle">Découvrez nos expositions du moment</p>
        <div class="card-grid">
            <?php foreach ($expositions as $exposition): ?>
                <div class="card">
                    <div class="card-image">
                        <i class="fas fa-calendar-alt" style="font-size: 40px;"></i>
                    </div>
                    <div class="card-body">
                        <h3><a href="<?= BASE_URL ?>public/exposition/show/<?= $exposition->id ?>">
                            <?= htmlspecialchars($exposition->titre) ?>
                        </a></h3>
                        <p><?= htmlspecialchars(substr($exposition->description ?? '', 0, 100)) ?>...</p>
                        <div class="meta">
                            <span><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($exposition->date_debut)) ?> - <?= date('d/m/Y', strtotime($exposition->date_fin)) ?></span>
                            <span class="badge badge-en-cours">En cours</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Œuvres phares -->
    <?php if (!empty($oeuvres)): ?>
    <section>
        <h2 class="section-title"><i class="fas fa-paint-brush"></i> Œuvres phares</h2>
        <p class="section-subtitle">Découvrez quelques-unes de nos œuvres les plus remarquables</p>
        <div class="card-grid">
            <?php foreach ($oeuvres as $oeuvre): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if (!empty($oeuvre->photo)): ?>
                            <img src="<?= BASE_URL . $oeuvre->photo ?>" alt="<?= htmlspecialchars($oeuvre->titre) ?>">
                        <?php else: ?>
                            <i class="fas fa-paint-brush"></i>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3><a href="<?= BASE_URL ?>public/oeuvre/show/<?= $oeuvre->id ?>">
                            <?= htmlspecialchars($oeuvre->titre) ?>
                        </a></h3>
                        <p><?= htmlspecialchars(substr($oeuvre->description ?? '', 0, 80)) ?>...</p>
                        <div class="meta">
                            <span>
                                <i class="fas fa-user"></i> <?= htmlspecialchars($oeuvre->auteur_nom ?? 'Anonyme') ?>
                            </span>
                            <span class="badge badge-exposé">Exposé</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= BASE_URL ?>public/oeuvre" class="btn btn-primary">Voir toutes les œuvres</a>
        </div>
    </section>
    <?php endif; ?>
</div>
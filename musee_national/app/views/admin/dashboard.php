<h2>Tableau de bord - <?= htmlspecialchars($nom) ?></h2>
<p>Bienvenue dans l'espace d'administration du Musée National.</p>

<!-- ===== ALERTES ===== -->
<?php if (!empty($alerts)): ?>
    <div style="margin: 20px 0;">
        <?php foreach ($alerts as $alert): ?>
            <div class="alert alert-<?= $alert['type'] ?>" style="margin-bottom: 10px;">
                <i class="fas <?= $alert['icon'] ?>"></i>
                <?= $alert['message'] ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        ✅ Tout est en ordre ! Aucune alerte à signaler.
    </div>
<?php endif; ?>

<!-- ===== KPI CARDS ===== -->
<div class="kpi-grid">
    <div class="kpi-card" style="border-top-color: #4CAF50;">
        <div class="kpi-value"><?= $stats['total_oeuvres'] ?></div>
        <div class="kpi-label">Œuvres</div>
    </div>
    <div class="kpi-card" style="border-top-color: #2196F3;">
        <div class="kpi-value"><?= $stats['total_auteurs'] ?></div>
        <div class="kpi-label">Auteurs</div>
    </div>
    <div class="kpi-card" style="border-top-color: #FF9800;">
        <div class="kpi-value"><?= $stats['total_expositions'] ?></div>
        <div class="kpi-label">Expositions</div>
    </div>
    <div class="kpi-card" style="border-top-color: #9C27B0;">
        <div class="kpi-value"><?= $stats['total_prets'] ?></div>
        <div class="kpi-label">Prêts</div>
    </div>
</div>

<!-- ===== DEUXIÈME LIGNE DE KPI ===== -->
<div class="kpi-grid">
    <div class="kpi-card" style="border-top-color: #f44336;">
        <div class="kpi-value"><?= $stats['prets_retard'] ?></div>
        <div class="kpi-label">Prêts en retard</div>
    </div>
    <div class="kpi-card" style="border-top-color: #E91E63;">
        <div class="kpi-value"><?= $stats['restaurations_en_cours'] ?></div>
        <div class="kpi-label">Restaurations en cours</div>
    </div>
    <div class="kpi-card" style="border-top-color: #00BCD4;">
        <div class="kpi-value"><?= number_format($stats['cout_restaurations'], 2) ?> fbu</div>
        <div class="kpi-label">Coût total restaurations</div>
    </div>
    <div class="kpi-card" style="border-top-color: #8BC34A;">
        <div class="kpi-value"><?= $stats['expositions_en_cours'] ?></div>
        <div class="kpi-label">Expositions en cours</div>
    </div>
</div>

<!-- ===== GRAPHIQUES ===== -->
<div class="chart-grid">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie"></i> Répartition par statut</h3>
        </div>
        <canvas id="chartStatut"></canvas>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-bar"></i> Œuvres par catégorie</h3>
        </div>
        <canvas id="chartCategorie"></canvas>
    </div>
</div>

<div class="chart-grid">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-trophy"></i> Top 5 auteurs</h3>
        </div>
        <canvas id="chartAuteurs"></canvas>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-exchange-alt"></i> Mouvements</h3>
        </div>
        <canvas id="chartMouvements"></canvas>
    </div>
</div>

<!-- ===== SECTION GESTION ===== -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-cog"></i> Gestion</h3>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
        <a href="<?= BASE_URL ?>oeuvre" class="btn btn-primary" style="justify-content: center;">
            <i class="fas fa-paint-brush"></i> Œuvres
        </a>
        <a href="<?= BASE_URL ?>auteur" class="btn btn-primary" style="justify-content: center;">
            <i class="fas fa-user-astronaut"></i> Auteurs
        </a>
        <a href="<?= BASE_URL ?>categorie" class="btn btn-primary" style="justify-content: center;">
            <i class="fas fa-tags"></i> Catégories
        </a>
        <a href="<?= BASE_URL ?>exposition" class="btn btn-primary" style="justify-content: center;">
            <i class="fas fa-calendar-alt"></i> Expositions
        </a>
        <a href="<?= BASE_URL ?>pret" class="btn btn-primary" style="justify-content: center;">
            <i class="fas fa-handshake"></i> Prêts
        </a>
        <a href="<?= BASE_URL ?>restauration" class="btn btn-primary" style="justify-content: center;">
            <i class="fas fa-tools"></i> Restaurations
        </a>
        <a href="<?= BASE_URL ?>mouvement" class="btn btn-primary" style="justify-content: center;">
            <i class="fas fa-exchange-alt"></i> Mouvements
        </a>
    </div>
</div>

<!-- ===== LIENS RAPIDES ===== -->
<div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
    <a href="<?= BASE_URL ?>auth/logout" class="btn btn-danger">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
</div>

<!-- ===== CHART.JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Données pour les graphiques
    const statsStatut = <?= json_encode($stats['stats_statut']) ?>;
    const statsCategorie = <?= json_encode($stats['stats_categorie']) ?>;
    const statsAuteurs = <?= json_encode($stats['stats_auteurs_top']) ?>;
    const entrees = <?= $stats['mouvements_entrees'] ?? 0 ?>;
    const sorties = <?= $stats['mouvements_sorties'] ?? 0 ?>;

    // Couleurs professionnelles
    const colors = ['#4CAF50', '#FF9800', '#f44336', '#2196F3', '#9C27B0', '#00BCD4'];

    // 1. Graphique statut (camembert)
    const ctx1 = document.getElementById('chartStatut');
    if (ctx1 && statsStatut.length > 0) {
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: statsStatut.map(item => item.statut || 'Non défini'),
                datasets: [{
                    data: statsStatut.map(item => parseInt(item.total) || 0),
                    backgroundColor: colors.slice(0, statsStatut.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // 2. Graphique catégorie (barres)
    const ctx2 = document.getElementById('chartCategorie');
    if (ctx2 && statsCategorie.length > 0) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: statsCategorie.map(item => item.categorie || 'Non classé'),
                datasets: [{
                    label: 'Nombre d\'œuvres',
                    data: statsCategorie.map(item => parseInt(item.total) || 0),
                    backgroundColor: 'rgba(201, 168, 76, 0.7)',
                    borderColor: 'rgba(201, 168, 76, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    // 3. Graphique top auteurs (barres horizontales)
    const ctx3 = document.getElementById('chartAuteurs');
    if (ctx3 && statsAuteurs.length > 0) {
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: statsAuteurs.map(item => (item.prenom ? item.prenom + ' ' : '') + item.nom),
                datasets: [{
                    label: 'Nombre d\'œuvres',
                    data: statsAuteurs.map(item => parseInt(item.total) || 0),
                    backgroundColor: 'rgba(26, 42, 58, 0.7)',
                    borderColor: 'rgba(26, 42, 58, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    // 4. Graphique mouvements
    const ctx4 = document.getElementById('chartMouvements');
    if (ctx4) {
        new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: ['Entrées', 'Sorties'],
                datasets: [{
                    label: 'Nombre de mouvements',
                    data: [parseInt(entrees) || 0, parseInt(sorties) || 0],
                    backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(220, 53, 69, 0.7)'],
                    borderColor: ['rgba(40, 167, 69, 1)', 'rgba(220, 53, 69, 1)'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }
</script>
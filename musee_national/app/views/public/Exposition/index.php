<div class="container">
    <!-- Hero -->
    <div class="hero">
        <h1><i class="fas fa-calendar-alt"></i> Expositions</h1>
        <p>Découvrez nos expositions passées, présentes et futures</p>
    </div>

    <!-- Barre de recherche -->
    <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <form method="get" action="<?= BASE_URL ?>public/exposition" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher une exposition..." 
                   value="<?= htmlspecialchars($keyword ?? '') ?>" style="flex:1; min-width:200px; padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
            <select name="statut" class="form-control" style="padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
                <option value="">Tous les statuts</option>
                <?php foreach ($statuts as $s): ?>
                    <option value="<?= $s ?>" <?= ($statut ?? '') == $s ? 'selected' : '' ?>>
                        <?= ucfirst($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <?php if (!empty($keyword) || !empty($statut)): ?>
                <a href="<?= BASE_URL ?>public/exposition" class="btn btn-outline" style="border:1px solid #ddd;">Réinitialiser</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Résultats -->
    <div class="card-grid">
        <?php if (empty($expositions)): ?>
            <p style="text-align:center; color:#999; grid-column:1/-1; padding:40px;">
                <i class="fas fa-search" style="font-size:32px; display:block; margin-bottom:10px;"></i>
                Aucune exposition trouvée
            </p>
        <?php else: ?>
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
                            <span class="badge badge-<?= $exposition->statut === 'en cours' ? 'en-cours' : ($exposition->statut === 'prévue' ? 'prévue' : 'terminée') ?>">
                                <?= $exposition->statut ?>
                            </span>
                        </div>
                        <div style="margin-top: 8px; font-size: 13px; color: #888;">
                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($exposition->lieu ?? 'Lieu non spécifié') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
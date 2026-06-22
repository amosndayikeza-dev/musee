<div class="container">
    <!-- Hero -->
    <div class="hero">
        <h1><i class="fas fa-paint-brush"></i> Catalogue des œuvres</h1>
        <p>Découvrez la collection du Musée National</p>
    </div>

    <!-- Barre de recherche et filtres -->
    <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <form method="get" action="<?= BASE_URL ?>public/oeuvre" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                   value="<?= htmlspecialchars($keyword ?? '') ?>" style="flex:1; min-width:200px; padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
            <select name="statut" class="form-control" style="padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
                <option value="">Tous les statuts</option>
                <?php foreach ($statuts as $s): ?>
                    <option value="<?= $s ?>" <?= ($statut ?? '') == $s ? 'selected' : '' ?>>
                        <?= ucfirst($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="auteur_id" class="form-control" style="padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
                <option value="">Tous les auteurs</option>
                <?php foreach ($auteurs as $auteur): ?>
                    <option value="<?= $auteur->id ?>" <?= ($auteur_id ?? '') == $auteur->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($auteur->nom) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="categorie_id" class="form-control" style="padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= $categorie->id ?>" <?= ($categorie_id ?? '') == $categorie->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categorie->nom) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <?php if (!empty($keyword) || !empty($statut) || !empty($auteur_id) || !empty($categorie_id)): ?>
                <a href="<?= BASE_URL ?>public/oeuvre" class="btn btn-outline" style="border:1px solid #ddd;">Réinitialiser</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Résultats -->
    <div class="card-grid">
        <?php if (empty($oeuvres)): ?>
            <p style="text-align:center; color:#999; grid-column:1/-1; padding:40px;">
                <i class="fas fa-search" style="font-size:32px; display:block; margin-bottom:10px;"></i>
                Aucune œuvre trouvée
            </p>
        <?php else: ?>
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
                        <p><?= htmlspecialchars(substr($oeuvre->description ?? '', 0, 100)) ?>...</p>
                        <div class="meta">
                            <span>
                                <i class="fas fa-user"></i> <?= htmlspecialchars($oeuvre->auteur_nom ?? 'Anonyme') ?>
                            </span>
                            <span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>">
                                <?= $oeuvre->statut ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
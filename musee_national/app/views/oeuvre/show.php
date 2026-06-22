<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-paint-brush"></i> Détail de l'œuvre</h3>
        <div>
            <a href="<?= BASE_URL ?>admin/oeuvre/edit/<?= $oeuvre->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>admin/oeuvre" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <?php if (!empty($oeuvre->photo)): ?>
                    <img src="<?= BASE_URL . $oeuvre->photo ?>" alt="<?= htmlspecialchars($oeuvre->titre) ?>" style="max-width:100%; border-radius:8px;">
                <?php else: ?>
                    <div style="background:#e9ecef; height:300px; display:flex; align-items:center; justify-content:center; border-radius:8px; font-size:60px; color:#999;">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h2 style="color:#1a2a3a;"><?= htmlspecialchars($oeuvre->titre) ?></h2>
                <p><strong>Statut :</strong> 
                    <span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>">
                        <?= $oeuvre->statut ?>
                    </span>
                </p>
                <p><strong>Auteur :</strong> 
                    <?= htmlspecialchars($oeuvre->auteur_nom ?? 'Non défini') ?>
                    <?php if ($oeuvre->auteur_id): ?>
                        <a href="<?= BASE_URL ?>admin/auteur/show/<?= $oeuvre->auteur_id ?>" style="color:#c9a84c;">(Voir)</a>
                    <?php endif; ?>
                </p>
                <p><strong>Catégorie :</strong> <?= htmlspecialchars($oeuvre->categorie_nom ?? 'Non défini') ?></p>
                <p><strong>Date de création :</strong> <?= $oeuvre->date_creation ?? 'Non renseignée' ?></p>
                <p><strong>Technique :</strong> <?= htmlspecialchars($oeuvre->technique ?? 'Non renseignée') ?></p>
                <p><strong>Dimensions :</strong> <?= htmlspecialchars($oeuvre->dimensions ?? 'Non renseignées') ?></p>
                <div style="margin-top:15px; padding-top:15px; border-top:1px solid #eee;">
                    <h4>Description</h4>
                    <p><?= nl2br(htmlspecialchars($oeuvre->description ?? 'Aucune description')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div>
            <?php if (!empty($oeuvre->photo)): ?>
                <img src="<?= BASE_URL . $oeuvre->photo ?>" alt="<?= htmlspecialchars($oeuvre->titre) ?>" style="width:100%; border-radius:8px;">
            <?php else: ?>
                <div style="background: #e9ecef; height: 400px; display: flex; align-items: center; justify-content: center; border-radius:8px; font-size: 60px; color: #999;">
                    <i class="fas fa-paint-brush"></i>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>" style="margin-bottom: 15px;">
                <?= $oeuvre->statut ?>
            </span>
            <h1 style="font-size: 32px; color: #1a2a3a; margin-bottom: 15px;"><?= htmlspecialchars($oeuvre->titre) ?></h1>
            
            <div style="margin-bottom: 20px;">
                <p><strong><i class="fas fa-user"></i> Auteur :</strong> 
                    <?= htmlspecialchars($oeuvre->auteur_nom ?? 'Anonyme') ?>
                    <?php if ($oeuvre->auteur_nom): ?>
                        <a href="<?= BASE_URL ?>public/auteur/show/<?= $oeuvre->auteur_id ?>" style="color: #c9a84c; font-size: 13px;">(Voir le profil)</a>
                    <?php endif; ?>
                </p>
                <p><strong><i class="fas fa-tags"></i> Catégorie :</strong> 
                    <?= htmlspecialchars($oeuvre->categorie_nom ?? 'Non classée') ?>
                </p>
                <p><strong><i class="fas fa-calendar"></i> Date de création :</strong> 
                    <?= $oeuvre->date_creation ?? 'Non renseignée' ?>
                </p>
                <p><strong><i class="fas fa-palette"></i> Technique :</strong> 
                    <?= htmlspecialchars($oeuvre->technique ?? 'Non renseignée') ?>
                </p>
                <p><strong><i class="fas fa-ruler-combined"></i> Dimensions :</strong> 
                    <?= htmlspecialchars($oeuvre->dimensions ?? 'Non renseignées') ?>
                </p>
            </div>
            
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                <h3>Description</h3>
                <p style="line-height: 1.8;"><?= nl2br(htmlspecialchars($oeuvre->description ?? 'Aucune description disponible')) ?></p>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="<?= BASE_URL ?>public/oeuvre" class="btn btn-outline" style="border:1px solid #ddd;">
                    <i class="fas fa-arrow-left"></i> Retour au catalogue
                </a>
            </div>
        </div>
    </div>
</div>
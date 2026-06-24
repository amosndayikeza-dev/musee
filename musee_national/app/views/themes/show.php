<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-palette"></i> Détail du thème</h3>
        <div>
            <a href="<?= BASE_URL ?>admin/themes/edit/<?= $theme->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>admin/themes" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>Nom :</strong> <?= htmlspecialchars($theme->nom) ?></p>
                <p><strong>Statut :</strong> 
                    <?php if ($theme->actif == 1): ?>
                        <span class="badge badge-success">Actif</span>
                    <?php else: ?>
                        <span class="badge badge-secondary">Inactif</span>
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <p><strong>Couleur primaire :</strong> 
                    <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_primaire ?>; border-radius:4px; vertical-align:middle;"></span>
                    <?= $theme->couleur_primaire ?>
                </p>
                <p><strong>Couleur secondaire :</strong> 
                    <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_secondaire ?>; border-radius:4px; vertical-align:middle;"></span>
                    <?= $theme->couleur_secondaire ?>
                </p>
                <p><strong>Couleur de fond :</strong> 
                    <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_fond ?>; border-radius:4px; border:1px solid #ddd; vertical-align:middle;"></span>
                    <?= $theme->couleur_fond ?>
                </p>
                <p><strong>Couleur de texte :</strong> 
                    <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_texte ?>; border-radius:4px; vertical-align:middle;"></span>
                    <?= $theme->couleur_texte ?>
                </p>
            </div>
        </div>

        <!-- Aperçu du thème -->
        <div style="margin-top:30px; padding:20px; background:<?= $theme->couleur_fond ?>; border-radius:8px; border:1px solid #ddd;">
            <h4 style="color:<?= $theme->couleur_primaire ?>;">Aperçu du thème</h4>
            <p style="color:<?= $theme->couleur_texte ?>;">Ceci est un aperçu de l'application avec les couleurs du thème.</p>
            <button style="background:<?= $theme->couleur_primaire ?>; color:#fff; border:none; padding:8px 16px; border-radius:4px;">
                Bouton primaire
            </button>
            <button style="background:<?= $theme->couleur_secondaire ?>; color:#fff; border:none; padding:8px 16px; border-radius:4px; margin-left:10px;">
                Bouton secondaire
            </button>
        </div>
    </div>
</div>
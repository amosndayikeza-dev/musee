<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-palette"></i> Gestion des thèmes</h3>
        <div class="table-actions">
            <a href="<?= BASE_URL ?>admin/themes/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter un thème
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin:15px;">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Couleur primaire</th>
                    <th>Couleur secondaire</th>
                    <th>Couleur fond</th>
                    <th>Couleur texte</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($themes as $theme): ?>
                <tr>
                    <td><?= htmlspecialchars($theme->nom) ?></td>
                    <td>
                        <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_primaire ?>; border-radius:4px;"></span>
                        <?= $theme->couleur_primaire ?>
                    </td>
                    <td>
                        <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_secondaire ?>; border-radius:4px;"></span>
                        <?= $theme->couleur_secondaire ?>
                    </td>
                    <td>
                        <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_fond ?>; border-radius:4px; border:1px solid #ddd;"></span>
                        <?= $theme->couleur_fond ?>
                    </td>
                    <td>
                        <span style="display:inline-block; width:20px; height:20px; background:<?= $theme->couleur_texte ?>; border-radius:4px;"></span>
                        <?= $theme->couleur_texte ?>
                    </td>
                    <td>
                        <?php if ($theme->actif == 1): ?>
                            <span class="badge badge-success">Actif</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Inactif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="actions">
                            <?php if ($theme->actif != 1): ?>
                                <form method="post" action="<?= BASE_URL ?>admin/themes/activate/<?= $theme->id ?>" style="display:inline;">
                                    <button type="submit" class="btn-icon" style="color:#28a745;" title="Activer">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>admin/themes/edit/<?= $theme->id ?>" class="btn-icon edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($theme->actif != 1): ?>
                                <form method="post" action="<?= BASE_URL ?>admin/themes/delete/<?= $theme->id ?>" style="display:inline;">
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer ce thème ?')" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
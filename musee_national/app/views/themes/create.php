<div class="form-card">
    <div class="form-title">
        <i class="fas fa-palette"></i> Ajouter un thème
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>admin/themes/store">
        <div class="form-row">
            <div class="form-group">
                <label>Nom du thème <span class="required">*</span></label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Actif ?</label>
                <select name="actif" class="form-control">
                    <option value="0" <?= (isset($old['actif']) && $old['actif'] == 0) ? 'selected' : '' ?>>Inactif</option>
                    <option value="1" <?= (isset($old['actif']) && $old['actif'] == 1) ? 'selected' : '' ?>>Actif</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Couleur primaire</label>
                <input type="color" name="couleur_primaire" class="form-control" value="<?= $old['couleur_primaire'] ?? '#1a2a3a' ?>">
            </div>
            <div class="form-group">
                <label>Couleur secondaire</label>
                <input type="color" name="couleur_secondaire" class="form-control" value="<?= $old['couleur_secondaire'] ?? '#c9a84c' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Couleur de fond</label>
                <input type="color" name="couleur_fond" class="form-control" value="<?= $old['couleur_fond'] ?? '#f4f6f9' ?>">
            </div>
            <div class="form-group">
                <label>Couleur de texte</label>
                <input type="color" name="couleur_texte" class="form-control" value="<?= $old['couleur_texte'] ?? '#333333' ?>">
            </div>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>admin/themes" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
<div class="form-card">
    <div class="form-title">
        <i class="fas fa-tag"></i> Ajouter une catégorie
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>categorie/store">
        <div class="form-group form-row-full">
            <label>Nom <span class="required">*</span></label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
        </div>

        <div class="form-group form-row-full">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>categorie/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
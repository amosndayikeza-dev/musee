<div class="form-card">
    <div class="form-title">
        <i class="fas fa-calendar-plus"></i> Ajouter une exposition
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>admin/exposition/store">
        <div class="form-group form-row-full">
            <label>Titre <span class="required">*</span></label>
            <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($old['titre'] ?? '') ?>" required>
        </div>

        <div class="form-group form-row-full">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date début <span class="required">*</span></label>
                <input type="date" name="date_debut" class="form-control" value="<?= $old['date_debut'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label>Date fin <span class="required">*</span></label>
                <input type="date" name="date_fin" class="form-control" value="<?= $old['date_fin'] ?? '' ?>" required>
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Lieu</label>
            <input type="text" name="lieu" class="form-control" value="<?= htmlspecialchars($old['lieu'] ?? '') ?>" placeholder="Ex: Salle 1, Musée National, ...">
        </div>

        <div class="form-group form-row-full">
            <label>Statut</label>
            <select name="statut" class="form-control">
                <option value="prévue" <?= (isset($old['statut']) && $old['statut'] === 'prévue') ? 'selected' : '' ?>>Prévue</option>
                <option value="en cours" <?= (isset($old['statut']) && $old['statut'] === 'en cours') ? 'selected' : '' ?>>En cours</option>
                <option value="terminée" <?= (isset($old['statut']) && $old['statut'] === 'terminée') ? 'selected' : '' ?>>Terminée</option>
            </select>
        </div>

        <div class="form-group form-row-full">
            <label>Œuvres associées</label>
            <select name="oeuvres[]" class="form-control" multiple style="height:150px;">
                <?php foreach ($oeuvres as $oeuvre): ?>
                    <option value="<?= $oeuvre->id ?>" <?= (isset($old['oeuvres']) && in_array($oeuvre->id, $old['oeuvres'])) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($oeuvre->titre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small style="color:#888;">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs œuvres</small>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>admin/exposition/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
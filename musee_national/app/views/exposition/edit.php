<div class="form-card">
    <div class="form-title">
        <i class="fas fa-calendar-edit"></i> Modifier l'exposition : <?= htmlspecialchars($exposition->titre) ?>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>admin/exposition/update/<?= $exposition->id ?>">
        <div class="form-group form-row-full">
            <label>Titre <span class="required">*</span></label>
            <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($exposition->titre) ?>" required>
        </div>

        <div class="form-group form-row-full">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($exposition->description ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date début <span class="required">*</span></label>
                <input type="date" name="date_debut" class="form-control" value="<?= $exposition->date_debut ?>" required>
            </div>
            <div class="form-group">
                <label>Date fin <span class="required">*</span></label>
                <input type="date" name="date_fin" class="form-control" value="<?= $exposition->date_fin ?>" required>
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Lieu</label>
            <input type="text" name="lieu" class="form-control" value="<?= htmlspecialchars($exposition->lieu ?? '') ?>" placeholder="Ex: Salle 1, Musée National, ...">
        </div>

        <div class="form-group form-row-full">
            <label>Statut</label>
            <select name="statut" class="form-control">
                <option value="prévue" <?= $exposition->statut === 'prévue' ? 'selected' : '' ?>>Prévue</option>
                <option value="en cours" <?= $exposition->statut === 'en cours' ? 'selected' : '' ?>>En cours</option>
                <option value="terminée" <?= $exposition->statut === 'terminée' ? 'selected' : '' ?>>Terminée</option>
            </select>
        </div>

        <div class="form-group form-row-full">
            <label>Œuvres associées</label>
            <select name="oeuvres[]" class="form-control" multiple style="height:150px;">
                <?php foreach ($toutesOeuvres as $oeuvre): ?>
                    <option value="<?= $oeuvre->id ?>" <?= in_array($oeuvre->id, $oeuvresIds) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($oeuvre->titre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small style="color:#888;">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs œuvres</small>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>admin/exposition/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
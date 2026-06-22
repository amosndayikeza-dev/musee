<div class="form-card">
    <div class="form-title">
        <i class="fas fa-edit"></i> Modifier la restauration
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>restauration/update/<?= $restauration->id ?>">
        <div class="form-group form-row-full">
            <label>Œuvre <span class="required">*</span></label>
            <select name="oeuvre_id" class="form-control" required>
                <option value="">Sélectionner une œuvre</option>
                <?php foreach ($oeuvres as $oeuvre): ?>
                    <option value="<?= $oeuvre->id ?>" <?= $restauration->oeuvre_id == $oeuvre->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($oeuvre->titre) ?>
                        (<?= $oeuvre->statut ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date début <span class="required">*</span></label>
                <input type="date" name="date_debut" class="form-control" value="<?= $restauration->date_debut ?>" required>
            </div>
            <div class="form-group">
                <label>Date fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= $restauration->date_fin ?? '' ?>">
                <small style="color:#888;">Laissez vide si la restauration est en cours</small>
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Responsable</label>
            <input type="text" name="responsable" class="form-control" value="<?= htmlspecialchars($restauration->responsable ?? '') ?>" placeholder="Nom du restaurateur ou de l'atelier">
        </div>

        <div class="form-group form-row-full">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($restauration->description ?? '') ?></textarea>
        </div>

        <div class="form-group form-row-full">
            <label>Coût (€)</label>
            <input type="number" name="cout" class="form-control" step="0.01" value="<?= $restauration->cout ?? '' ?>" placeholder="0.00">
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>restauration/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
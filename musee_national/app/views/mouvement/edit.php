<div class="form-card">
    <div class="form-title">
        <i class="fas fa-edit"></i> Modifier le mouvement
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>mouvement/update/<?= $mouvement->id ?>">
        <div class="form-group form-row-full">
            <label>Œuvre <span class="required">*</span></label>
            <select name="oeuvre_id" class="form-control" required>
                <option value="">Sélectionner une œuvre</option>
                <?php foreach ($oeuvres as $oeuvre): ?>
                    <option value="<?= $oeuvre->id ?>" <?= $mouvement->oeuvre_id == $oeuvre->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($oeuvre->titre) ?>
                        (<?= $oeuvre->statut ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Type <span class="required">*</span></label>
                <select name="type" class="form-control" required>
                    <option value="entrée" <?= $mouvement->type === 'entrée' ? 'selected' : '' ?>>Entrée</option>
                    <option value="sortie" <?= $mouvement->type === 'sortie' ? 'selected' : '' ?>>Sortie</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date <span class="required">*</span></label>
                <input type="date" name="date" class="form-control" value="<?= $mouvement->date ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Provenance</label>
                <input type="text" name="provenance" class="form-control" value="<?= htmlspecialchars($mouvement->provenance ?? '') ?>" placeholder="D'où vient l'œuvre ?">
                <small style="color:#888;">Obligatoire pour une entrée</small>
            </div>
            <div class="form-group">
                <label>Destination</label>
                <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($mouvement->destination ?? '') ?>" placeholder="Où va l'œuvre ?">
                <small style="color:#888;">Obligatoire pour une sortie</small>
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Responsable</label>
            <input type="text" name="responsable" class="form-control" value="<?= htmlspecialchars($mouvement->responsable ?? '') ?>" placeholder="Nom de la personne responsable">
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>mouvement/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
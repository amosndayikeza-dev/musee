<div class="form-card">
    <div class="form-title">
        <i class="fas fa-handshake"></i> Ajouter un prêt
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>pret/store">
        <div class="form-group form-row-full">
            <label>Œuvre <span class="required">*</span></label>
            <select name="oeuvre_id" class="form-control" required>
                <option value="">Sélectionner une œuvre</option>
                <?php foreach ($oeuvres as $oeuvre): ?>
                    <option value="<?= $oeuvre->id ?>" <?= (isset($old['oeuvre_id']) && $old['oeuvre_id'] == $oeuvre->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($oeuvre->titre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group form-row-full">
            <label>Emprunteur <span class="required">*</span></label>
            <input type="text" name="emprunteur" class="form-control" value="<?= htmlspecialchars($old['emprunteur'] ?? '') ?>" required placeholder="Nom de la personne ou de l'institution">
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
            <label>Statut</label>
            <select name="statut" class="form-control">
                <option value="en cours" <?= (isset($old['statut']) && $old['statut'] === 'en cours') ? 'selected' : '' ?>>En cours</option>
                <option value="retourné" <?= (isset($old['statut']) && $old['statut'] === 'retourné') ? 'selected' : '' ?>>Retourné</option>
            </select>
        </div>

        <div class="form-group form-row-full">
            <label>Observations</label>
            <textarea name="observations" class="form-control" rows="3"><?= htmlspecialchars($old['observations'] ?? '') ?></textarea>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>pret/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
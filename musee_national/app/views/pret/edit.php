<div class="form-card">
    <div class="form-title">
        <i class="fas fa-edit"></i> Modifier le prêt
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>pret/update/<?= $pret->id ?>">
        <div class="form-group form-row-full">
            <label>Œuvre <span class="required">*</span></label>
            <select name="oeuvre_id" class="form-control" required>
                <option value="">Sélectionner une œuvre</option>
                <?php foreach ($oeuvres as $oeuvre): ?>
                    <option value="<?= $oeuvre->id ?>" <?= $pret->oeuvre_id == $oeuvre->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($oeuvre->titre) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group form-row-full">
            <label>Emprunteur <span class="required">*</span></label>
            <input type="text" name="emprunteur" class="form-control" value="<?= htmlspecialchars($pret->emprunteur) ?>" required placeholder="Nom de la personne ou de l'institution">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date début <span class="required">*</span></label>
                <input type="date" name="date_debut" class="form-control" value="<?= $pret->date_debut ?>" required>
            </div>
            <div class="form-group">
                <label>Date fin <span class="required">*</span></label>
                <input type="date" name="date_fin" class="form-control" value="<?= $pret->date_fin ?>" required>
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Statut</label>
            <select name="statut" class="form-control">
                <option value="en cours" <?= $pret->statut === 'en cours' ? 'selected' : '' ?>>En cours</option>
                <option value="retourné" <?= $pret->statut === 'retourné' ? 'selected' : '' ?>>Retourné</option>
            </select>
        </div>

        <div class="form-group form-row-full">
            <label>Observations</label>
            <textarea name="observations" class="form-control" rows="3"><?= htmlspecialchars($pret->observations ?? '') ?></textarea>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>pret/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
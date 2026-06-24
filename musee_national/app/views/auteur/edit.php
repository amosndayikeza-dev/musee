<div class="form-card">
    <div class="form-title">
        <i class="fas fa-user-edit"></i> Modifier l'auteur : <?= htmlspecialchars($auteur->nom) ?>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- AJOUT DE enctype="multipart/form-data" -->
    <form method="post" action="<?= BASE_URL ?>admin/auteur/update/<?= $auteur->id ?>" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Nom <span class="required">*</span></label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($auteur->nom) ?>" required>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($auteur->prenom ?? '') ?>">
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Biographie</label>
            <textarea name="biographie" class="form-control" rows="4"><?= htmlspecialchars($auteur->biographie ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" class="form-control" value="<?= $auteur->date_naissance ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Date de décès</label>
                <input type="date" name="date_deces" class="form-control" value="<?= $auteur->date_deces ?? '' ?>">
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Nationalité</label>
            <input type="text" name="nationalite" class="form-control" value="<?= htmlspecialchars($auteur->nationalite ?? '') ?>" placeholder="Ex: Française, Italienne, ...">
        </div>

        <!-- CORRECTION : label "Photo de l'auteur" -->
        <div class="form-group form-row-full">
            <label>Photo de l'auteur</label>
            <?php if (!empty($auteur->photo)): ?>
                <div style="margin-bottom:10px;">
                    <img src="<?= BASE_URL . $auteur->photo ?>" style="max-width:200px; border-radius:8px;">
                </div>
            <?php endif; ?>
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small style="color:#888;">Formats : JPG, PNG, GIF, WEBP (max 2 Mo)</small>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>admin/auteur/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
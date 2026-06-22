<div class="form-card">
    <div class="form-title">
        <i class="fas fa-user-edit"></i> Modifier mon profil
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>profil/update" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Nom <span class="required">*</span></label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user->nom) ?>" required>
            </div>
            <div class="form-group">
                <label>Prénom <span class="required">*</span></label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user->prenom ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->email) ?>" required>
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($user->telephone ?? '') ?>" placeholder="06 12 34 56 78">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" class="form-control" value="<?= $user->date_naissance ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Genre</label>
                <select name="genre" class="form-control">
                    <option value="">Non spécifié</option>
                    <option value="homme" <?= ($user->genre ?? '') === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="femme" <?= ($user->genre ?? '') === 'femme' ? 'selected' : '' ?>>Femme</option>
                    <option value="autre" <?= ($user->genre ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Adresse</label>
            <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($user->adresse ?? '') ?>" placeholder="Numéro et rue">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($user->ville ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Code postal</label>
                <input type="text" name="code_postal" class="form-control" value="<?= htmlspecialchars($user->code_postal ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Pays</label>
                <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($user->pays ?? '') ?>" placeholder="France">
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Biographie</label>
            <textarea name="biographie" class="form-control" rows="4"><?= htmlspecialchars($user->biographie ?? '') ?></textarea>
        </div>

        <div class="form-group form-row-full">
            <label>Photo de profil</label>
            <?php if (!empty($user->photo)): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?= BASE_URL . $user->photo ?>" alt="Photo actuelle" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                </div>
            <?php endif; ?>
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small style="color:#888;">Formats acceptés : JPG, PNG, GIF, WEBP (max 2 Mo)</small>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>profil/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
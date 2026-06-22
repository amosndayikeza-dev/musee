<div class="form-card" style="max-width: 600px;">
    <div class="form-title">
        <i class="fas fa-key"></i> Changer mon mot de passe
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>profil/updatePassword">
        <div class="form-group form-row-full">
            <label>Mot de passe actuel <span class="required">*</span></label>
            <input type="password" name="current_password" class="form-control" required placeholder="Entrez votre mot de passe actuel">
        </div>

        <div class="form-group form-row-full">
            <label>Nouveau mot de passe <span class="required">*</span></label>
            <input type="password" name="new_password" class="form-control" required placeholder="Minimum 6 caractères">
        </div>

        <div class="form-group form-row-full">
            <label>Confirmer le nouveau mot de passe <span class="required">*</span></label>
            <input type="password" name="confirm_password" class="form-control" required placeholder="Confirmez votre nouveau mot de passe">
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Changer le mot de passe
                </button>
                <a href="<?= BASE_URL ?>profil/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
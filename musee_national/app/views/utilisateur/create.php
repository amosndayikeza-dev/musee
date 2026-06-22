<div class="form-card">
    <div class="form-title">
        <i class="fas fa-user-plus"></i> Ajouter un utilisateur
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>utilisateur/store">
        <div class="form-row">
            <div class="form-group">
                <label>Nom <span class="required">*</span></label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Email <span class="required">*</span></label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>

        <div class="form-group form-row-full">
            <label>Mot de passe <span class="required">*</span></label>
            <input type="password" name="mot_de_passe" class="form-control" required placeholder="Minimum 6 caractères">
            <small style="color:#888;">Le mot de passe doit contenir au moins 6 caractères</small>
        </div>

        <div class="form-group form-row-full">
            <label>Rôle</label>
            <select name="role" class="form-control">
                <option value="visiteur" <?= (isset($old['role']) && $old['role'] === 'visiteur') ? 'selected' : '' ?>>Visiteur</option>
                <option value="conservateur" <?= (isset($old['role']) && $old['role'] === 'conservateur') ? 'selected' : '' ?>>Conservateur</option>
                <option value="admin" <?= (isset($old['role']) && $old['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Créer
                </button>
                <a href="<?= BASE_URL ?>utilisateur/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
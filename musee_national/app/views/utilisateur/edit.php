<div class="form-card">
    <div class="form-title">
        <i class="fas fa-user-edit"></i> Modifier l'utilisateur : <?= htmlspecialchars($utilisateur->nom) ?>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>utilisateur/update/<?= $utilisateur->id ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nom <span class="required">*</span></label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($utilisateur->nom) ?>" required>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($utilisateur->prenom ?? '') ?>">
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Email <span class="required">*</span></label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utilisateur->email) ?>" required>
        </div>

        <div class="form-group form-row-full">
            <label>Nouveau mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control" placeholder="Laisser vide pour ne pas changer">
            <small style="color:#888;">Minimum 6 caractères. Laissez vide pour conserver le mot de passe actuel.</small>
        </div>

        <?php if ($utilisateur->id != $_SESSION['user_id']): ?>
            <div class="form-group form-row-full">
                <label>Rôle</label>
                <select name="role" class="form-control">
                    <option value="visiteur" <?= $utilisateur->role === 'visiteur' ? 'selected' : '' ?>>Visiteur</option>
                    <option value="conservateur" <?= $utilisateur->role === 'conservateur' ? 'selected' : '' ?>>Conservateur</option>
                    <option value="admin" <?= $utilisateur->role === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                </select>
            </div>
        <?php else: ?>
            <div class="form-group form-row-full">
                <label>Rôle</label>
                <input type="text" class="form-control" value="<?= ucfirst($utilisateur->role) ?>" disabled style="background:#f5f5f5;">
                <small style="color:#888;">Vous ne pouvez pas modifier votre propre rôle</small>
            </div>
        <?php endif; ?>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>utilisateur/index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
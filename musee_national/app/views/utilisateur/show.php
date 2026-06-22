<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user"></i> Détail de l'utilisateur</h3>
        <div>
            <?php if ($utilisateur->id != $_SESSION['user_id']): ?>
                <a href="<?= BASE_URL ?>utilisateur/edit/<?= $utilisateur->id ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>utilisateur/index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>ID :</strong> <?= $utilisateur->id ?></p>
                <p><strong>Nom :</strong> <?= htmlspecialchars($utilisateur->nom) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($utilisateur->prenom ?? 'Non renseigné') ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($utilisateur->email) ?></p>
            </div>
            <div>
                <p><strong>Rôle :</strong> 
                    <span class="badge badge-<?= $utilisateur->role === 'admin' ? 'danger' : ($utilisateur->role === 'conservateur' ? 'warning' : 'secondary') ?>">
                        <?= ucfirst($utilisateur->role) ?>
                    </span>
                </p>
                <p><strong>Date création :</strong> <?= date('d/m/Y H:i', strtotime($utilisateur->date_creation)) ?></p>
                <p><strong>Dernier accès :</strong> <?= $utilisateur->dernier_acces ? date('d/m/Y H:i', strtotime($utilisateur->dernier_acces)) : 'Jamais' ?></p>
                <?php if ($utilisateur->id == $_SESSION['user_id']): ?>
                    <p><span class="badge badge-primary" style="background:#1a2a3a;">C'est vous</span></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
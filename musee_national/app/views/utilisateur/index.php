<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-users"></i> Gestion des Utilisateurs</h3>
        <div class="table-actions">
            <form method="get" action="<?= BASE_URL ?>utilisateur/index" style="display:flex; gap:5px;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:200px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword)): ?>
                    <a href="<?= BASE_URL ?>utilisateur/index" class="btn btn-secondary btn-sm">✕</a>
                <?php endif; ?>
            </form>
            
            <a href="<?= BASE_URL ?>utilisateur/create" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Ajouter
            </a>
            <a href="<?= BASE_URL ?>utilisateur/exportPdf" class="btn btn-gold">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="<?= BASE_URL ?>admin/utilisateur/exportExcel" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin:15px;">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="margin:15px;">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date création</th>
                    <th>Dernier accès</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($utilisateurs)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= htmlspecialchars($user->nom) ?></td>
                        <td><?= htmlspecialchars($user->prenom ?? '') ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td>
                            <span class="badge badge-<?= $user->role === 'admin' ? 'danger' : ($user->role === 'conservateur' ? 'warning' : 'secondary') ?>">
                                <?= ucfirst($user->role) ?>
                            </span>
                            <?php if ($user->id == $_SESSION['user_id']): ?>
                                <span class="badge badge-primary" style="background:#1a2a3a;">Vous</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($user->date_creation)) ?></td>
                        <td><?= $user->dernier_acces ? date('d/m/Y H:i', strtotime($user->dernier_acces)) : 'Jamais' ?></td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>utilisateur/show/<?= $user->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                 <a href="<?= BASE_URL ?>admin/audit?table=utilisateur&record_id=<?= $user->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user->id != $_SESSION['user_id']): ?>
                                    <a href="<?= BASE_URL ?>utilisateur/edit/<?= $user->id ?>" class="btn-icon edit" title="Modifier">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    <form method="post" action="<?= BASE_URL ?>utilisateur/delete/<?= $user->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cet utilisateur ?')" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:#999; font-size:12px;">(Vous)</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
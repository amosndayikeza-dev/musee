<div class="container">
    <h2><i class="fas fa-user-circle"></i> Mon profil</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <div class="profil-grid">
        <!-- Photo de profil -->
        <div class="profil-avatar">
            <?php if (!empty($user->photo)): ?>
                <img src="<?= BASE_URL . $user->photo ?>" alt="Photo de profil" class="avatar-img">
            <?php else: ?>
                <div class="avatar-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            
            <div class="avatar-actions">
                <a href="<?= BASE_URL ?>profil/edit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier mon profil
                </a>
                <a href="<?= BASE_URL ?>profil/password" class="btn btn-outline">
                    <i class="fas fa-key"></i> Changer mot de passe
                </a>
            </div>
        </div>
        
        <!-- Informations -->
        <div class="profil-info">
            <h3>Informations personnelles</h3>
            <table class="profil-table">
                <tr>
                    <td class="label">Nom complet</td>
                    <td><?= htmlspecialchars($user->prenom . ' ' . $user->nom) ?></td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td><?= htmlspecialchars($user->email) ?></td>
                </tr>
                <tr>
                    <td class="label">Rôle</td>
                    <td>
                        <span class="badge badge-<?= $user->role === 'admin' ? 'danger' : ($user->role === 'conservateur' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst($user->role) ?>
                        </span>
                    </td>
                </tr>
                <?php if (!empty($user->telephone)): ?>
                    <tr>
                        <td class="label">Téléphone</td>
                        <td><?= htmlspecialchars($user->telephone) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($user->date_naissance)): ?>
                    <tr>
                        <td class="label">Date de naissance</td>
                        <td><?= date('d/m/Y', strtotime($user->date_naissance)) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($user->adresse)): ?>
                    <tr>
                        <td class="label">Adresse</td>
                        <td>
                            <?= htmlspecialchars($user->adresse) ?>
                            <?php if (!empty($user->ville)): ?>, <?= htmlspecialchars($user->ville) ?><?php endif; ?>
                            <?php if (!empty($user->code_postal)): ?> (<?= htmlspecialchars($user->code_postal) ?>)<?php endif; ?>
                            <?php if (!empty($user->pays)): ?>, <?= htmlspecialchars($user->pays) ?><?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($user->biographie)): ?>
                    <tr>
                        <td class="label" style="vertical-align:top;">Biographie</td>
                        <td><?= nl2br(htmlspecialchars($user->biographie)) ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="label">Date d'inscription</td>
                    <td><?= date('d/m/Y H:i', strtotime($user->date_creation)) ?></td>
                </tr>
                <tr>
                    <td class="label">Dernière connexion</td>
                    <td><?= $user->dernier_acces ? date('d/m/Y H:i', strtotime($user->dernier_acces)) : 'Jamais' ?></td>
                </tr>
            </table>
            
            <!-- Statistiques -->
            <h3 style="margin-top: 30px;">Statistiques</h3>
            <div class="profil-stats">
                <div class="stat-item">
                    <div class="stat-value"><?= $stats['connexions'] ?? 0 ?></div>
                    <div class="stat-label">Connexions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= $nbActions ?? 0 ?></div>
                    <div class="stat-label">Actions effectuées</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= date_diff(new DateTime($user->date_creation), new DateTime())->days ?></div>
                    <div class="stat-label">Jours d'ancienneté</div>
                </div>
            </div>
            
            <!-- Historique des connexions -->
            <?php if (!empty($connexions)): ?>
                <h3 style="margin-top: 30px;">Dernières connexions</h3>
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>IP</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($connexions as $connexion): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($connexion->date_connexion)) ?></td>
                                    <td><?= htmlspecialchars($connexion->ip_adresse) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $connexion->statut === 'succès' ? 'success' : ($connexion->statut === 'déconnexion' ? 'secondary' : 'danger') ?>">
                                            <?= $connexion->statut ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">
    <h2><i class="fas fa-user-circle"></i> Mon profil</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 30px;">
        <!-- Photo de profil -->
        <div style="text-align: center;">
            <?php if (!empty($user->photo)): ?>
                <img src="<?= BASE_URL . $user->photo ?>" alt="Photo de profil" 
                     style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; border: 3px solid #c9a84c;">
            <?php else: ?>
                <div style="width: 200px; height: 200px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 80px; color: #999; margin: 0 auto;">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 15px;">
                <a href="<?= BASE_URL ?>profil/edit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier mon profil
                </a>
            </div>
            <div style="margin-top: 10px;">
                <a href="<?= BASE_URL ?>profil/password" class="btn btn-outline" style="border:1px solid #ddd;">
                    <i class="fas fa-key"></i> Changer mot de passe
                </a>
            </div>
        </div>
        
        <!-- Informations -->
        <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
            <h3 style="margin-top: 0;">Informations personnelles</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0; font-weight: 600; width: 150px;">Nom complet</td>
                    <td style="padding: 8px 0;"><?= htmlspecialchars($user->prenom . ' ' . $user->nom) ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: 600;">Email</td>
                    <td style="padding: 8px 0;"><?= htmlspecialchars($user->email) ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: 600;">Rôle</td>
                    <td style="padding: 8px 0;">
                        <span class="badge badge-<?= $user->role === 'admin' ? 'danger' : ($user->role === 'conservateur' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst($user->role) ?>
                        </span>
                    </td>
                </tr>
                <?php if (!empty($user->telephone)): ?>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Téléphone</td>
                        <td style="padding: 8px 0;"><?= htmlspecialchars($user->telephone) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($user->date_naissance)): ?>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Date de naissance</td>
                        <td style="padding: 8px 0;"><?= date('d/m/Y', strtotime($user->date_naissance)) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($user->adresse)): ?>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600;">Adresse</td>
                        <td style="padding: 8px 0;">
                            <?= htmlspecialchars($user->adresse) ?>
                            <?php if (!empty($user->ville)): ?>, <?= htmlspecialchars($user->ville) ?><?php endif; ?>
                            <?php if (!empty($user->code_postal)): ?> (<?= htmlspecialchars($user->code_postal) ?>)<?php endif; ?>
                            <?php if (!empty($user->pays)): ?>, <?= htmlspecialchars($user->pays) ?><?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($user->biographie)): ?>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 600; vertical-align: top;">Biographie</td>
                        <td style="padding: 8px 0;"><?= nl2br(htmlspecialchars($user->biographie)) ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td style="padding: 8px 0; font-weight: 600;">Date d'inscription</td>
                    <td style="padding: 8px 0;"><?= date('d/m/Y H:i', strtotime($user->date_creation)) ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: 600;">Dernière connexion</td>
                    <td style="padding: 8px 0;"><?= $user->dernier_acces ? date('d/m/Y H:i', strtotime($user->dernier_acces)) : 'Jamais' ?></td>
                </tr>
            </table>
            
            <!-- Statistiques -->
            <h3 style="margin-top: 30px;">Statistiques</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; font-weight: 700; color: #1a2a3a;"><?= $stats['connexions'] ?? 0 ?></div>
                    <div style="font-size: 13px; color: #888;">Connexions</div>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; font-weight: 700; color: #1a2a3a;">
                        <?php 
                            $nbActions = 0;
                            // Compter les actions dans l'audit
                            if (isset($user->id)) {
                                // À implémenter avec le service d'audit
                            }
                            echo $nbActions;
                        ?>
                    </div>
                    <div style="font-size: 13px; color: #888;">Actions effectuées</div>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 24px; font-weight: 700; color: #1a2a3a;">
                        <?= date_diff(new DateTime($user->date_creation), new DateTime())->days ?>
                    </div>
                    <div style="font-size: 13px; color: #888;">Jours d'ancienneté</div>
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
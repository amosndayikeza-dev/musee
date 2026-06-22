<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-history"></i> Historique des connexions</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>IP</th>
                    <th>Statut</th>
                    <th>Navigateur</th>
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
                    <td><?= htmlspecialchars(substr($connexion->user_agent, 0, 50)) ?>...</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
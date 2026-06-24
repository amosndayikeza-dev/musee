<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-bell"></i> Mes notifications</h3>
        <div class="table-actions">
            <?php if ($unreadCount > 0): ?>
                <form method="post" action="<?= BASE_URL ?>notification/markAllAsRead" style="display:inline;">
                    <button type="submit" class="btn btn-gold btn-sm">Tout marquer comme lu</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Titre</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($notifications)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-bell-slash" style="font-size:24px; display:block;"></i>
                            Aucune notification
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                        <tr style="<?= $notif->est_lu ? '' : 'font-weight:bold; background:#f0f8ff;' ?>">
                            <td><span class="badge badge-secondary"><?= $notif->type ?></span></td>
                            <td><?= htmlspecialchars($notif->titre) ?></td>
                            <td><?= htmlspecialchars($notif->message) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($notif->date_creation)) ?></td>
                            <td>
                                <?php if ($notif->est_lu): ?>
                                    <span class="badge badge-secondary">Lu</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Non lu</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$notif->est_lu): ?>
                                    <form method="post" action="<?= BASE_URL ?>notification/markAsRead/<?= $notif->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon" style="color:#28a745;" title="Marquer comme lu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($notif->lien): ?>
                                    <a href="<?= BASE_URL . $notif->lien ?>" class="btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
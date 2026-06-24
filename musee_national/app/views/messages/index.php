<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-envelope"></i> Messages de contact</h3>
        <div class="table-actions">
            <span class="badge" style="background: #dc3545; color: #fff; padding: 5px 12px; border-radius: 20px;">
                <?= $unread ?? 0 ?> non lus
            </span>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin:15px;">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                    <tr><td colspan="7" style="text-align:center; padding:30px; color:#999;">Aucun message reçu.</td></tr>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <tr style="<?= $msg->est_lu ? '' : 'font-weight:bold; background:#f0f8ff;' ?>">
                            <td><?= $msg->id ?></td>
                            <td><?= htmlspecialchars($msg->nom) ?></td>
                            <td><?= htmlspecialchars($msg->email) ?></td>
                            <td><?= htmlspecialchars($msg->sujet) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($msg->date_envoi)) ?></td>
                            <td>
                                <?php if (!$msg->est_lu): ?>
                                    <span class="badge badge-danger">Non lu</span>
                                <?php elseif ($msg->repondu): ?>
                                    <span class="badge badge-success">Répondu</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Lu</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?= BASE_URL ?>admin/messages/show/<?= $msg->id ?>" class="btn-icon view" title="Voir"><i class="fas fa-eye"></i></a>
                                    <?php if (!$msg->est_lu): ?>
                                        <form method="post" action="<?= BASE_URL ?>admin/messages/markAsRead/<?= $msg->id ?>" style="display:inline;">
                                            <button type="submit" class="btn-icon" style="color:#28a745;" title="Marquer comme lu"><i class="fas fa-check"></i></button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (!$msg->repondu): ?>
                                        <form method="post" action="<?= BASE_URL ?>admin/messages/markAsReplied/<?= $msg->id ?>" style="display:inline;">
                                            <button type="submit" class="btn-icon" style="color:#17a2b8;" title="Marquer comme répondu"><i class="fas fa-reply"></i></button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/messages/delete/<?= $msg->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer ce message ?')" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
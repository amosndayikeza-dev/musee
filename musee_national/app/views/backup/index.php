<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-database"></i> Sauvegardes</h3>
        <div class="table-actions">
            <form method="post" action="<?= BASE_URL ?>admin/backup/create" style="display:inline;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-plus"></i> Créer une sauvegarde
                </button>
            </form>
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
                    <th>Fichier</th>
                    <th>Date</th>
                    <th>Taille</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($backups)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-database" style="font-size:24px; display:block; margin-bottom:10px;"></i>
                            Aucune sauvegarde disponible
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($backups as $backup): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($backup['filename']) ?></strong></td>
                            <td><?= $backup['date'] ?></td>
                            <td><?= number_format($backup['size'] / 1024, 2) ?> KB</td>
                            <td>
                                <div class="actions">
                                    <form method="post" action="<?= BASE_URL ?>admin/backup/restore" style="display:inline;">
                                        <input type="hidden" name="filename" value="<?= htmlspecialchars($backup['filename']) ?>">
                                        <button type="submit" class="btn-icon" style="color:#28a745;" onclick="return confirm('Restaurer cette sauvegarde ?')" title="Restaurer">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </form>
                                    <form method="post" action="<?= BASE_URL ?>admin/backup/delete" style="display:inline;">
                                        <input type="hidden" name="filename" value="<?= htmlspecialchars($backup['filename']) ?>">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cette sauvegarde ?')" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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
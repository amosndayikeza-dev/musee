<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-history"></i> Journal d'audit</h3>
        <div class="table-actions">
            <form method="get" action="<?= BASE_URL ?>admin/audit" style="display:flex; gap:10px; flex-wrap:wrap;">
                <input type="text" name="action" class="form-control" placeholder="Action..." value="<?= htmlspecialchars($_GET['action'] ?? '') ?>" style="width:150px;">
                <input type="text" name="table" class="form-control" placeholder="Table..." value="<?= htmlspecialchars($_GET['table'] ?? '') ?>" style="width:150px;">
                <input type="date" name="date_debut" class="form-control" value="<?= $_GET['date_debut'] ?? '' ?>" style="width:150px;">
                <input type="date" name="date_fin" class="form-control" value="<?= $_GET['date_fin'] ?? '' ?>" style="width:150px;">
                <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                <a href="<?= BASE_URL ?>admin/audit" class="btn btn-secondary btn-sm">Réinitialiser</a>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Enregistrement</th>
                    <th>Anciennes valeurs</th>
                    <th>Nouvelles valeurs</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucun log trouvé
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log->id ?></td>
                            <td><?= htmlspecialchars($log->email) ?></td>
                            <td><span class="badge badge-<?= $log->action === 'INSERT' ? 'success' : ($log->action === 'UPDATE' ? 'warning' : 'danger') ?>">
                                <?= $log->action ?>
                            </span></td>
                            <td><?= $log->table_cible ?? '-' ?></td>
                            <td><?= $log->enregistrement_id ?? '-' ?></td>
                            <td style="max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                <?= $log->anciennes_valeurs ? substr(htmlspecialchars($log->anciennes_valeurs), 0, 50) . '...' : '-' ?>
                            </td>
                            <td style="max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                <?= $log->nouvelles_valeurs ? substr(htmlspecialchars($log->nouvelles_valeurs), 0, 50) . '...' : '-' ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($log->date_action)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
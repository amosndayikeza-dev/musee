<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-trash-alt"></i> Corbeille - Catégories</h3>
        <div class="table-actions">
            <?php if (!empty($items)): ?>
                <form method="post" action="<?= BASE_URL ?>admin/categorie/emptyTrash" style="display:inline;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Vider définitivement la corbeille ?')">
                        <i class="fas fa-eraser"></i> Vider la corbeille
                    </button>
                </form>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>admin/categorie" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
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
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Nombre d'œuvres</th>
                    <th>Supprimé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-trash-alt" style="font-size:24px; display:block;"></i>
                            La corbeille est vide
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= htmlspecialchars($item->nom) ?></td>
                        <td><?= htmlspecialchars($item->description ?? '') ?></td>
                        <td><?= $item->nb_oeuvres ?? 0 ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($item->deleted_at)) ?></td>
                        <td>
                            <div class="actions">
                                <form method="post" action="<?= BASE_URL ?>admin/categorie/restore/<?= $item->id ?>" style="display:inline;">
                                    <button type="submit" class="btn-icon" style="color:#28a745;" title="Restaurer">
                                        <i class="fas fa-undo-alt"></i>
                                    </button>
                                </form>
                                <form method="post" action="<?= BASE_URL ?>admin/categorie/forceDelete/<?= $item->id ?>" style="display:inline;">
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer définitivement cette catégorie ?')" title="Supprimer définitivement">
                                        <i class="fas fa-times"></i>
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
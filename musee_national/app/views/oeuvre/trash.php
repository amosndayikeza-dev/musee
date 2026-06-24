<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-trash-alt"></i> Corbeille - Œuvres</h3>
        <div class="table-actions">
            <?php if (!empty($oeuvres)): ?>
                <form method="post" action="<?= BASE_URL ?>admin/oeuvre/emptyTrash" style="display:inline;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Vider définitivement la corbeille ?')">
                        <i class="fas fa-eraser"></i> Vider la corbeille
                    </button>
                </form>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>admin/oeuvre" class="btn btn-secondary">
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
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Supprimé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($oeuvres)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-trash-alt" style="font-size:24px; display:block;"></i>
                            La corbeille est vide
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($oeuvres as $oeuvre): ?>
                    <tr>
                        <td><?= $oeuvre->id ?></td>
                        <td><?= htmlspecialchars($oeuvre->titre) ?></td>
                        <td><?= htmlspecialchars($oeuvre->auteur_nom ?? 'Non défini') ?></td>
                        <td><?= htmlspecialchars($oeuvre->categorie_nom ?? 'Non défini') ?></td>
                        <td>
                            <span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>">
                                <?= $oeuvre->statut ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($oeuvre->deleted_at)) ?></td>
                        <td>
                            <div class="actions">
                                <form method="post" action="<?= BASE_URL ?>admin/oeuvre/restore/<?= $oeuvre->id ?>" style="display:inline;">
                                    <button type="submit" class="btn-icon" style="color:#28a745;" title="Restaurer">
                                        <i class="fas fa-undo-alt"></i>
                                    </button>
                                </form>
                                <form method="post" action="<?= BASE_URL ?>admin/oeuvre/forceDelete/<?= $oeuvre->id ?>" style="display:inline;">
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer définitivement cette œuvre ?')" title="Supprimer définitivement">
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
<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-calendar-alt"></i> Gestion des Expositions</h3>
        <div class="table-actions">
            <!-- Formulaire de recherche -->
            <form method="get" action="<?= BASE_URL ?>admin/exposition/index" style="display:flex; gap:5px; flex-wrap:wrap;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:180px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword) || !empty($statut) || !empty($date_debut) || !empty($date_fin)): ?>
                    <a href="<?= BASE_URL ?>admin/exposition/index" class="btn btn-secondary btn-sm">✕ Réinitialiser</a>
                <?php endif; ?>
            </form>
            
            <a href="<?= BASE_URL ?>admin/exposition/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter
            </a>
            <a href="<?= BASE_URL ?>admin/exposition/exportPdf" class="btn btn-gold">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div style="padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
        <form method="get" action="<?= BASE_URL ?>admin/exposition/index" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; color:#666;">Statut</label>
                <select name="statut" class="form-control" style="width:150px; padding:6px 10px;">
                    <option value="">Tous</option>
                    <?php foreach ($statuts as $s): ?>
                        <option value="<?= $s ?>" <?= ($statut ?? '') == $s ? 'selected' : '' ?>>
                            <?= ucfirst($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; color:#666;">Date début</label>
                <input type="date" name="date_debut" class="form-control" style="width:150px; padding:6px 10px;" 
                       value="<?= $date_debut ?? '' ?>">
            </div>
            
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; color:#666;">Date fin</label>
                <input type="date" name="date_fin" class="form-control" style="width:150px; padding:6px 10px;" 
                       value="<?= $date_fin ?? '' ?>">
            </div>
            
            <div>
                <button type="submit" class="btn btn-gold btn-sm">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Messages -->
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
                    <th>Titre</th>
                    <th>Lieu</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($expositions)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucune exposition trouvée
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($expositions as $exposition): ?>
                    <tr>
                        <td><?= $exposition->id ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>admin/exposition/show/<?= $exposition->id ?>" style="color:#1a2a3a; font-weight:500;">
                                <?= htmlspecialchars($exposition->titre) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($exposition->lieu ?? '') ?></td>
                        <td><?= date('d/m/Y', strtotime($exposition->date_debut)) ?></td>
                        <td><?= date('d/m/Y', strtotime($exposition->date_fin)) ?></td>
                        <td>
                            <span class="badge badge-<?= $exposition->statut === 'en cours' ? 'en-cours' : ($exposition->statut === 'prévue' ? 'prévue' : 'terminée') ?>">
                                <?= $exposition->statut ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>admin/exposition/show/<?= $exposition->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/exposition/edit/<?= $exposition->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/exposition/delete/<?= $exposition->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cette exposition ?')" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Compteur de résultats -->
    <div style="padding: 10px 20px; border-top: 1px solid #e9ecef; color:#888; font-size:13px;">
        <?php if (!empty($expositions)): ?>
            <?= count($expositions) ?> exposition(s) trouvée(s)
            <?php if (!empty($keyword)): ?>
                pour "<strong><?= htmlspecialchars($keyword) ?></strong>"
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
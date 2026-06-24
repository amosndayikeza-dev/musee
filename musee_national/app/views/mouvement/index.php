<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-exchange-alt"></i> Gestion des Mouvements</h3>
        <div class="table-actions">
            <!-- Formulaire de recherche -->
            <form method="get" action="<?= BASE_URL ?>mouvement/index" style="display:flex; gap:5px; flex-wrap:wrap;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:180px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword) || !empty($type) || !empty($oeuvre_id) || !empty($date_debut) || !empty($date_fin)): ?>
                    <a href="<?= BASE_URL ?>mouvement/index" class="btn btn-secondary btn-sm">✕ Réinitialiser</a>
                <?php endif; ?>
            </form>
            
            <a href="<?= BASE_URL ?>mouvement/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter
            </a>
            <a href="<?= BASE_URL ?>mouvement/exportPdf" class="btn btn-gold">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="<?= BASE_URL ?>admin/mouvement/exportExcel" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div style="padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
        <form method="get" action="<?= BASE_URL ?>mouvement/index" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; color:#666;">Œuvre</label>
                <select name="oeuvre_id" class="form-control" style="width:150px; padding:6px 10px;">
                    <option value="">Toutes</option>
                    <?php foreach ($oeuvres as $oeuvre): ?>
                        <option value="<?= $oeuvre->id ?>" <?= ($oeuvre_id ?? '') == $oeuvre->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($oeuvre->titre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; color:#666;">Type</label>
                <select name="type" class="form-control" style="width:140px; padding:6px 10px;">
                    <option value="">Tous</option>
                    <?php foreach ($types as $t): ?>
                        <option value="<?= $t ?>" <?= ($type ?? '') == $t ? 'selected' : '' ?>>
                            <?= ucfirst($t) ?>
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

    <!-- Résultats -->
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Œuvre</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Provenance</th>
                    <th>Destination</th>
                    <th>Responsable</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mouvements)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucun mouvement trouvé
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mouvements as $mouvement): ?>
                    <tr>
                        <td><?= $mouvement->id ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>oeuvre/show/<?= $mouvement->oeuvre_id ?>" style="color:#1a2a3a;">
                                <?= htmlspecialchars($mouvement->oeuvre_titre) ?>
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-<?= $mouvement->type === 'entrée' ? 'success' : 'danger' ?>">
                                <i class="fas fa-<?= $mouvement->type === 'entrée' ? 'arrow-down' : 'arrow-up' ?>"></i>
                                <?= ucfirst($mouvement->type) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($mouvement->date)) ?></td>
                        <td><?= htmlspecialchars($mouvement->provenance ?? '-') ?></td>
                        <td><?= htmlspecialchars($mouvement->destination ?? '-') ?></td>
                        <td><?= htmlspecialchars($mouvement->responsable ?? '-') ?></td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>admin/mouvement/show/<?= $mouvement->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/mouvement/edit/<?= $mouvement->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/audit?table=mouvement&record_id=<?= $mouvement->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-history"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/mouvement/delete/<?= $mouvement->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer ce mouvement ?')" title="Supprimer">
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
        <?php if (!empty($mouvements)): ?>
            <?= count($mouvements) ?> mouvement(s) trouvé(s)
            <?php if (!empty($keyword)): ?>
                pour "<strong><?= htmlspecialchars($keyword) ?></strong>"
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
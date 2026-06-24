<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-tools"></i> Gestion des Restaurations</h3>
        <div class="table-actions">
            <!-- Formulaire de recherche -->
            <form method="get" action="<?= BASE_URL ?>restauration/index" style="display:flex; gap:5px; flex-wrap:wrap;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:180px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword) || !empty($statut) || !empty($oeuvre_id) || !empty($date_debut) || !empty($date_fin)): ?>
                    <a href="<?= BASE_URL ?>restauration/index" class="btn btn-secondary btn-sm">✕ Réinitialiser</a>
                <?php endif; ?>
            </form>
            
            <a href="<?= BASE_URL ?>restauration/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter
            </a>
            <a href="<?= BASE_URL ?>restauration/exportPdf" class="btn btn-gold">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="<?= BASE_URL ?>admin/restauration/exportExcel" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div style="padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
        <form method="get" action="<?= BASE_URL ?>restauration/index" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
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
                <label style="font-size:12px; color:#666;">Statut</label>
                <select name="statut" class="form-control" style="width:140px; padding:6px 10px;">
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

    <!-- Résultats -->
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Œuvre</th>
                    <th>Responsable</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Coût (€)</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($restaurations)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucune restauration trouvée
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($restaurations as $restauration): ?>
                        <?php 
                            $estTerminee = !empty($restauration->date_fin) && strtotime($restauration->date_fin) <= time();
                            $estEnCours = !$estTerminee;
                        ?>
                    <tr>
                        <td><?= $restauration->id ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>oeuvre/show/<?= $restauration->oeuvre_id ?>" style="color:#1a2a3a;">
                                <?= htmlspecialchars($restauration->oeuvre_titre) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($restauration->responsable ?? 'Non défini') ?></td>
                        <td><?= date('d/m/Y', strtotime($restauration->date_debut)) ?></td>
                        <td>
                            <?php if ($restauration->date_fin): ?>
                                <?= date('d/m/Y', strtotime($restauration->date_fin)) ?>
                            <?php else: ?>
                                <span class="badge badge-en-cours">En cours</span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($restauration->cout ?? 0, 2) ?></td>
                        <td>
                            <span class="badge badge-<?= $estTerminee ? 'retourné' : 'en-cours' ?>">
                                <?= $estTerminee ? 'Terminée' : 'En cours' ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>admin/restauration/show/<?= $restauration->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/restauration/edit/<?= $restauration->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/audit?table=restauration&record_id=<?= $restauration->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-history"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/restauration/delete/<?= $restauration->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cette restauration ?')" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php if (empty($restauration->date_fin)): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/restauration/complete/<?= $restauration->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon" style="color:#28a745;" onclick="return confirm('Marquer comme terminée ?')" title="Terminer">
                                            <i class="fas fa-check"></i>
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
        <?php if (!empty($restaurations)): ?>
            <?= count($restaurations) ?> restauration(s) trouvée(s)
            <?php if (!empty($keyword)): ?>
                pour "<strong><?= htmlspecialchars($keyword) ?></strong>"
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
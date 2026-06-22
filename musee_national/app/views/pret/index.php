<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-handshake"></i> Gestion des Prêts</h3>
        <div class="table-actions">
            <!-- Formulaire de recherche -->
            <form method="get" action="<?= BASE_URL ?>pret/index" style="display:flex; gap:5px; flex-wrap:wrap;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:180px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword) || !empty($statut) || !empty($oeuvre_id) || !empty($date_debut) || !empty($date_fin)): ?>
                    <a href="<?= BASE_URL ?>pret/index" class="btn btn-secondary btn-sm">✕ Réinitialiser</a>
                <?php endif; ?>
            </form>
            
            <a href="<?= BASE_URL ?>pret/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter
            </a>
            <a href="<?= BASE_URL ?>pret/exportPdf" class="btn btn-gold">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="<?= BASE_URL ?>admin/pret/exportExcel" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div style="padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
        <form method="get" action="<?= BASE_URL ?>pret/index" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
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
                    <th>Emprunteur</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prets)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucun prêt trouvé
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($prets as $pret): ?>
                    <tr>
                        <td><?= $pret->id ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>oeuvre/show/<?= $pret->oeuvre_id ?>" style="color:#1a2a3a;">
                                <?= htmlspecialchars($pret->oeuvre_titre) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($pret->emprunteur) ?></td>
                        <td><?= date('d/m/Y', strtotime($pret->date_debut)) ?></td>
                        <td>
                            <?= date('d/m/Y', strtotime($pret->date_fin)) ?>
                            <?php if ($pret->statut === 'en cours' && strtotime($pret->date_fin) < time()): ?>
                                <span class="badge badge-danger" style="background:#dc3545; color:white; margin-left:5px;">⚠️ Retard</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= $pret->statut === 'en cours' ? 'en-cours' : 'retourné' ?>">
                                <?= $pret->statut === 'en cours' ? 'En cours' : 'Retourné' ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>admin/pret/show/<?= $pret->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/pret/edit/<?= $pret->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/pret/delete/<?= $pret->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer ce prêt ?')" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($pret->statut === 'en cours'): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/pret/return/<?= $pret->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon" style="color:#28a745;" onclick="return confirm('Marquer comme retourné ?')" title="Retourner">
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
        <?php if (!empty($prets)): ?>
            <?= count($prets) ?> prêt(s) trouvé(s)
            <?php if (!empty($keyword)): ?>
                pour "<strong><?= htmlspecialchars($keyword) ?></strong>"
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
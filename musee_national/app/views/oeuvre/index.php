<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-paint-brush"></i> Gestion des Œuvres</h3>
        <div class="table-actions">
            <!-- Formulaire de recherche -->
            <form method="get" action="<?= BASE_URL ?>admin/oeuvre" style="display:flex; gap:5px; flex-wrap:wrap;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:180px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword) || !empty($auteur_id) || !empty($categorie_id) || !empty($statut) || !empty($date_debut) || !empty($date_fin)): ?>
                    <a href="<?= BASE_URL ?>admin/oeuvre" class="btn btn-secondary btn-sm">✕ Réinitialiser</a>
                <?php endif; ?>
            </form>
            
            <!-- Boutons d'action -->
            <a href="<?= BASE_URL ?>admin/oeuvre/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter
            </a>
            <!-- Construire la chaîne de requête avec les filtres actuels -->
            <?php
            $queryParams = [];
            if (!empty($keyword)) $queryParams['keyword'] = $keyword;
            if (!empty($auteur_id)) $queryParams['auteur_id'] = $auteur_id;
            if (!empty($categorie_id)) $queryParams['categorie_id'] = $categorie_id;
            if (!empty($statut)) $queryParams['statut'] = $statut;
            if (!empty($date_debut)) $queryParams['date_debut'] = $date_debut;
            if (!empty($date_fin)) $queryParams['date_fin'] = $date_fin;
            $queryString = http_build_query($queryParams);
            ?>

            <a href="<?= BASE_URL ?>admin/oeuvre/exportPdf?<?= $queryString ?>" class="btn btn-gold">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="<?= BASE_URL ?>admin/oeuvre/exportExcel?<?= $queryString ?>" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div style="padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
        <form method="get" action="<?= BASE_URL ?>admin/oeuvre" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; color:#666;">Auteur</label>
                <select name="auteur_id" class="form-control" style="width:150px; padding:6px 10px;">
                    <option value="">Tous</option>
                    <?php foreach ($auteurs as $auteur): ?>
                        <option value="<?= $auteur->id ?>" <?= ($auteur_id ?? '') == $auteur->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($auteur->nom . ' ' . ($auteur->prenom ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; color:#666;">Catégorie</label>
                <select name="categorie_id" class="form-control" style="width:150px; padding:6px 10px;">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= $categorie->id ?>" <?= ($categorie_id ?? '') == $categorie->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categorie->nom) ?>
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
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($oeuvres)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block; margin-bottom:10px;"></i>
                            Aucune œuvre trouvée
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($oeuvres as $oeuvre): ?>
                    <tr>
                        <td><?= $oeuvre->id ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>admin/oeuvre/show/<?= $oeuvre->id ?>" style="color:#1a2a3a; font-weight:500;">
                                <?= htmlspecialchars($oeuvre->titre) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($oeuvre->auteur_nom ?? 'Non défini') ?></td>
                        <td><?= htmlspecialchars($oeuvre->categorie_nom ?? 'Non défini') ?></td>
                        <td>
                            <span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>">
                                <?= $oeuvre->statut ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>admin/oeuvre/show/<?= $oeuvre->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/oeuvre/edit/<?= $oeuvre->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-history"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <!-- Archiver (visible uniquement pour les œuvres non archivées) -->
                                    <?php if ($oeuvre->archive != 1): ?>
                                        <form method="post" action="<?= BASE_URL ?>admin/oeuvre/archive/<?= $oeuvre->id ?>" style="display:inline;">
                                            <button type="submit" class="btn-icon" style="color:#ff9800;" onclick="return confirm('Archiver cette œuvre ?')" title="Archiver">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" action="<?= BASE_URL ?>admin/oeuvre/unarchive/<?= $oeuvre->id ?>" style="display:inline;">
                                            <button type="submit" class="btn-icon" style="color:#28a745;" onclick="return confirm('Restaurer cette œuvre ?')" title="Restaurer">
                                                <i class="fas fa-undo-alt"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/oeuvre/delete/<?= $oeuvre->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cette œuvre ?')" title="Supprimer">
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
        <?php if (!empty($oeuvres)): ?>
            <?= count($oeuvres) ?> œuvre(s) trouvée(s)
        <?php endif; ?>
    </div>
</div>
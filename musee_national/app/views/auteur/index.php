<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-user-astronaut"></i> Gestion des Auteurs</h3>
        <div class="table-actions">
            <!-- Barre de recherche -->
            <form method="get" action="<?= BASE_URL ?>admin/auteur/index" style="display:flex; gap:5px; flex-wrap:wrap;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:180px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword) || !empty($nationalite)): ?>
                    <a href="<?= BASE_URL ?>admin/auteur/index" class="btn btn-secondary btn-sm">✕ Réinitialiser</a>
                <?php endif; ?>
            </form>
            
            <!-- Filtre par nationalité -->
            <form method="get" action="<?= BASE_URL ?>admin/auteur/index" style="display:flex; gap:5px; flex-wrap:wrap;">
                <select name="nationalite" class="form-control" style="width:150px; padding:6px 10px;">
                    <option value="">Toutes les nationalités</option>
                    <?php foreach ($nationalites as $n): ?>
                        <option value="<?= htmlspecialchars($n->nationalite) ?>" 
                                <?= ($nationalite ?? '') == $n->nationalite ? 'selected' : '' ?>>
                            <?= htmlspecialchars($n->nationalite) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
            </form>
            
            <a href="<?= BASE_URL ?>admin/auteur/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter
            </a>
           <!-- Construire la chaîne de requête -->
        <?php
            $queryParams = [];
            if (!empty($keyword)) $queryParams['keyword'] = $keyword;
            if (!empty($nationalite)) $queryParams['nationalite'] = $nationalite;
            $queryString = http_build_query($queryParams);
        ?>

        <!-- Boutons d'export avec les filtres -->
        <a href="<?= BASE_URL ?>admin/auteur/exportPdf?<?= $queryString ?>" class="btn btn-gold">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a href="<?= BASE_URL ?>admin/auteur/exportExcel?<?= $queryString ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Excel
        </a>
        </div>
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
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Nationalité</th>
                    <th>Naissance</th>
                    <th>Décès</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($auteurs)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucun auteur trouvé
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($auteurs as $auteur): ?>
                    <tr>
                        <td><strong><?= $auteur->matricule ?></strong></td>
                        <td>
                            <a href="<?= BASE_URL ?>admin/auteur/show/<?= $auteur->id ?>" style="color:#1a2a3a; font-weight:500;">
                                <?= htmlspecialchars($auteur->nom) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($auteur->prenom ?? '') ?></td>
                        <td><?= htmlspecialchars($auteur->nationalite ?? '') ?></td>
                        <td><?= $auteur->date_naissance ?? '-' ?></td>
                        <td><?= $auteur->date_deces ?? '-' ?></td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>admin/auteur/show/<?= $auteur->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/auteur/edit/<?= $auteur->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Nouveau bouton Historique -->
                                <a href="<?= BASE_URL ?>admin/audit?table=auteur&record_id=<?= $auteur->id ?>" class="btn-icon" style="color:#6c757d;" title="Historique des modifications">
                                    <i class="fas fa-history"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/auteur/delete/<?= $auteur->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cet auteur ?')" title="Supprimer">
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
</div>
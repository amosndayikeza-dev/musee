<div class="table-container">
    <div class="table-header">
        <h3><i class="fas fa-tags"></i> Gestion des Catégories</h3>
        <div class="table-actions">
            <!-- Barre de recherche -->
            <form method="get" action="<?= BASE_URL ?>categorie/index" style="display:flex; gap:5px;">
                <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:200px;">
                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                <?php if (!empty($keyword)): ?>
                    <a href="<?= BASE_URL ?>categorie/index" class="btn btn-secondary btn-sm">✕</a>
                <?php endif; ?>
            </form>
            
            <a href="<?= BASE_URL ?>categorie/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter
            </a>
            <a href="<?= BASE_URL ?>categorie/exportPdf" class="btn btn-gold">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="<?= BASE_URL ?>admin/categorie/exportExcel" class="btn btn-success">
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
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Nombre d'œuvres</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:#999;">
                            <i class="fas fa-search" style="font-size:24px; display:block;"></i>
                            Aucune catégorie trouvée
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $categorie): ?>
                    <tr>
                        <td><?= $categorie->id ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>categorie/show/<?= $categorie->id ?>" style="color:#1a2a3a; font-weight:500;">
                                <?= htmlspecialchars($categorie->nom) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($categorie->description ?? '') ?></td>
                        <td>
                            <span class="badge badge-<?= ($categorie->nb_oeuvres ?? 0) > 0 ? 'exposé' : 'en-réserve' ?>">
                                <?= $categorie->nb_oeuvres ?? 0 ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>admin/categorie/show/<?= $categorie->id ?>" class="btn-icon view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/categorie/edit/<?= $categorie->id ?>" class="btn-icon edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <form method="post" action="<?= BASE_URL ?>admin/categorie/delete/<?= $categorie->id ?>" style="display:inline;">
                                        <button type="submit" class="btn-icon delete" onclick="return confirm('Supprimer cette catégorie ?')" title="Supprimer">
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
        <?php if (!empty($categories)): ?>
            <?= count($categories) ?> catégorie(s) trouvée(s)
            <?php if (!empty($keyword)): ?>
                pour "<strong><?= htmlspecialchars($keyword) ?></strong>"
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
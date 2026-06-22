<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-tags"></i> Détail de la catégorie</h3>
        <div>
            <a href="<?= BASE_URL ?>categorie/edit/<?= $categorie->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>categorie/index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>ID :</strong> <?= $categorie->id ?></p>
                <p><strong>Nom :</strong> <?= htmlspecialchars($categorie->nom) ?></p>
                <p><strong>Description :</strong></p>
                <p style="background:#f8f9fa; padding:10px; border-radius:4px;"><?= nl2br(htmlspecialchars($categorie->description ?? 'Aucune description')) ?></p>
            </div>
        </div>

        <!-- Œuvres de la catégorie -->
        <div style="margin-top:30px;">
            <h4><i class="fas fa-paint-brush"></i> Œuvres de cette catégorie</h4>
            <?php if (empty($oeuvres)): ?>
                <p style="color:#999;">Aucune œuvre associée à cette catégorie.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size:13px;">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($oeuvres as $oeuvre): ?>
                                <?php 
                                    // Récupérer le nom de l'auteur
                                    $auteurModel = new \App\Models\AuteurModel();
                                    $auteur = $auteurModel->getById($oeuvre->auteur_id);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($oeuvre->titre) ?></td>
                                    <td><?= htmlspecialchars($auteur->nom ?? 'Non défini') ?></td>
                                    <td><span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>"><?= $oeuvre->statut ?></span></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>oeuvre/show/<?= $oeuvre->id ?>" class="btn-icon view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-astronaut"></i> Détail de l'auteur</h3>
        <div>
            <a href="<?= BASE_URL ?>admin/auteur/edit/<?= $auteur->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>admin/auteur/index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>Matricule :</strong> <?= $auteur->matricule ?></p>
                <p><strong>Nom :</strong> <?= htmlspecialchars($auteur->nom) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($auteur->prenom ?? 'Non renseigné') ?></p>
                <p><strong>Nationalité :</strong> <?= htmlspecialchars($auteur->nationalite ?? 'Non renseignée') ?></p>
            </div>
            <div>
                <p><strong>Date de naissance :</strong> <?= $auteur->date_naissance ?? 'Non renseignée' ?></p>
                <p><strong>Date de décès :</strong> <?= $auteur->date_deces ?? 'Non renseignée' ?></p>
                <p><strong>Biographie :</strong></p>
                <p style="background:#f8f9fa; padding:10px; border-radius:4px;"><?= nl2br(htmlspecialchars($auteur->biographie ?? 'Aucune biographie')) ?></p>
            </div>
        </div>

        <!-- Œuvres de l'auteur -->
        <div style="margin-top:30px;">
            <h4><i class="fas fa-paint-brush"></i> Œuvres de cet auteur</h4>
            <?php if (empty($oeuvres)): ?>
                <p style="color:#999;">Aucune œuvre associée à cet auteur.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size:13px;">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Technique</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($oeuvres as $oeuvre): ?>
                                <tr>
                                    <td><?= htmlspecialchars($oeuvre->titre) ?></td>
                                    <td><?= htmlspecialchars($oeuvre->technique ?? '') ?></td>
                                    <td><span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>"><?= $oeuvre->statut ?></span></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>admin/oeuvre/show/<?= $oeuvre->id ?>" class="btn-icon view">
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
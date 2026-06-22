<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-calendar-alt"></i> Détail de l'exposition</h3>
        <div>
            <a href="<?= BASE_URL ?>admin/exposition/edit/<?= $exposition->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>admin/exposition/index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>Titre :</strong> <?= htmlspecialchars($exposition->titre) ?></p>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($exposition->lieu ?? 'Non renseigné') ?></p>
                <p><strong>Statut :</strong> 
                    <span class="badge badge-<?= $exposition->statut === 'en cours' ? 'en-cours' : ($exposition->statut === 'prévue' ? 'prévue' : 'terminée') ?>">
                        <?= $exposition->statut ?>
                    </span>
                </p>
            </div>
            <div>
                <p><strong>Date début :</strong> <?= date('d/m/Y', strtotime($exposition->date_debut)) ?></p>
                <p><strong>Date fin :</strong> <?= date('d/m/Y', strtotime($exposition->date_fin)) ?></p>
                <p><strong>Durée :</strong> 
                    <?php 
                        $debut = new DateTime($exposition->date_debut);
                        $fin = new DateTime($exposition->date_fin);
                        $interval = $debut->diff($fin);
                        echo $interval->days . ' jours';
                    ?>
                </p>
            </div>
        </div>

        <div style="margin-top:20px;">
            <p><strong>Description :</strong></p>
            <p style="background:#f8f9fa; padding:10px; border-radius:4px;"><?= nl2br(htmlspecialchars($exposition->description ?? 'Aucune description')) ?></p>
        </div>

        <!-- Œuvres de l'exposition -->
        <div style="margin-top:30px;">
            <h4><i class="fas fa-paint-brush"></i> Œuvres exposées</h4>
            <?php if (empty($oeuvres)): ?>
                <p style="color:#999;">Aucune œuvre associée à cette exposition.</p>
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
                                <tr>
                                    <td><?= htmlspecialchars($oeuvre->titre) ?></td>
                                    <td><?= htmlspecialchars($oeuvre->auteur_nom ?? 'Non défini') ?></td>
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
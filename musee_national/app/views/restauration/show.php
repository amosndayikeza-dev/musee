<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-tools"></i> Détail de la restauration</h3>
        <div>
            <a href="<?= BASE_URL ?>restauration/edit/<?= $restauration->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>restauration/index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>ID :</strong> <?= $restauration->id ?></p>
                <p><strong>Œuvre :</strong> 
                    <a href="<?= BASE_URL ?>oeuvre/show/<?= $restauration->oeuvre_id ?>" style="color:#1a2a3a;">
                        <?= htmlspecialchars($restauration->oeuvre_titre) ?>
                    </a>
                </p>
                <p><strong>Statut de l'œuvre :</strong> 
                    <span class="badge badge-<?= str_replace(' ', '-', $restauration->oeuvre_statut) ?>">
                        <?= $restauration->oeuvre_statut ?>
                    </span>
                </p>
                <p><strong>Responsable :</strong> <?= htmlspecialchars($restauration->responsable ?? 'Non défini') ?></p>
            </div>
            <div>
                <p><strong>Date début :</strong> <?= date('d/m/Y', strtotime($restauration->date_debut)) ?></p>
                <p><strong>Date fin :</strong> 
                    <?php if ($restauration->date_fin): ?>
                        <?= date('d/m/Y', strtotime($restauration->date_fin)) ?>
                    <?php else: ?>
                        <span class="badge badge-en-cours">En cours</span>
                    <?php endif; ?>
                </p>
                <p><strong>Coût :</strong> <?= number_format($restauration->cout ?? 0, 2) ?> €</p>
                <p><strong>Durée :</strong> 
                    <?php 
                        $debut = new DateTime($restauration->date_debut);
                        $fin = $restauration->date_fin ? new DateTime($restauration->date_fin) : new DateTime();
                        $interval = $debut->diff($fin);
                        echo $interval->days . ' jours';
                    ?>
                </p>
            </div>
        </div>

        <?php if ($restauration->description): ?>
            <div style="margin-top:20px;">
                <p><strong>Description :</strong></p>
                <p style="background:#f8f9fa; padding:10px; border-radius:4px;"><?= nl2br(htmlspecialchars($restauration->description)) ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($restauration->date_fin)): ?>
            <div style="margin-top:20px; padding:15px; background:#fff3cd; border-radius:4px; border:1px solid #ffeaa7;">
                <p><strong>⚠️ Cette restauration est en cours</strong></p>
                <form method="post" action="<?= BASE_URL ?>restauration/complete/<?= $restauration->id ?>" style="display:inline;">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Marquer cette restauration comme terminée ?')">
                        <i class="fas fa-check"></i> Marquer comme terminée
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
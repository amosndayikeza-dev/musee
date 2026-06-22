<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-exchange-alt"></i> Détail du mouvement</h3>
        <div>
            <a href="<?= BASE_URL ?>mouvement/edit/<?= $mouvement->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>mouvement/index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>ID :</strong> <?= $mouvement->id ?></p>
                <p><strong>Œuvre :</strong> 
                    <a href="<?= BASE_URL ?>oeuvre/show/<?= $mouvement->oeuvre_id ?>" style="color:#1a2a3a;">
                        <?= htmlspecialchars($mouvement->oeuvre_titre) ?>
                    </a>
                </p>
                <p><strong>Type :</strong> 
                    <span class="badge badge-<?= $mouvement->type === 'entrée' ? 'success' : 'danger' ?>">
                        <i class="fas fa-<?= $mouvement->type === 'entrée' ? 'arrow-down' : 'arrow-up' ?>"></i>
                        <?= ucfirst($mouvement->type) ?>
                    </span>
                </p>
                <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($mouvement->date)) ?></p>
            </div>
            <div>
                <p><strong>Provenance :</strong> <?= htmlspecialchars($mouvement->provenance ?? 'Non renseignée') ?></p>
                <p><strong>Destination :</strong> <?= htmlspecialchars($mouvement->destination ?? 'Non renseignée') ?></p>
                <p><strong>Responsable :</strong> <?= htmlspecialchars($mouvement->responsable ?? 'Non renseigné') ?></p>
                <p><strong>Statut de l'œuvre :</strong> 
                    <span class="badge badge-<?= str_replace(' ', '-', $mouvement->oeuvre_statut) ?>">
                        <?= $mouvement->oeuvre_statut ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Information contextuelle -->
        <div style="margin-top:20px; padding:15px; background:#f8f9fa; border-radius:4px; border:1px solid #e9ecef;">
            <h4 style="margin:0 0 10px 0;">Informations contextuelles</h4>
            <?php if ($mouvement->type === 'entrée'): ?>
                <p><i class="fas fa-info-circle"></i> Cette œuvre est entrée au musée en provenance de : <strong><?= htmlspecialchars($mouvement->provenance ?? 'Non renseignée') ?></strong></p>
            <?php else: ?>
                <p><i class="fas fa-info-circle"></i> Cette œuvre est sortie du musée à destination de : <strong><?= htmlspecialchars($mouvement->destination ?? 'Non renseignée') ?></strong></p>
            <?php endif; ?>
        </div>
    </div>
</div>
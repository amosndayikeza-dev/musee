<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-handshake"></i> Détail du prêt</h3>
        <div>
            <a href="<?= BASE_URL ?>pret/edit/<?= $pret->id ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?= BASE_URL ?>pret/index" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p><strong>ID :</strong> <?= $pret->id ?></p>
                <p><strong>Œuvre :</strong> 
                    <a href="<?= BASE_URL ?>oeuvre/show/<?= $pret->oeuvre_id ?>" style="color:#1a2a3a;">
                        <?= htmlspecialchars($pret->oeuvre_titre) ?>
                    </a>
                </p>
                <p><strong>Emprunteur :</strong> <?= htmlspecialchars($pret->emprunteur) ?></p>
            </div>
            <div>
                <p><strong>Date début :</strong> <?= date('d/m/Y', strtotime($pret->date_debut)) ?></p>
                <p><strong>Date fin :</strong> <?= date('d/m/Y', strtotime($pret->date_fin)) ?></p>
                <p><strong>Statut :</strong> 
                    <span class="badge badge-<?= $pret->statut === 'en cours' ? 'en-cours' : 'retourné' ?>">
                        <?= $pret->statut === 'en cours' ? 'En cours' : 'Retourné' ?>
                    </span>
                </p>
                <p><strong>Durée :</strong> 
                    <?php 
                        $debut = new DateTime($pret->date_debut);
                        $fin = new DateTime($pret->date_fin);
                        $interval = $debut->diff($fin);
                        echo $interval->days . ' jours';
                    ?>
                </p>
            </div>
        </div>

        <?php if ($pret->observations): ?>
            <div style="margin-top:20px;">
                <p><strong>Observations :</strong></p>
                <p style="background:#f8f9fa; padding:10px; border-radius:4px;"><?= nl2br(htmlspecialchars($pret->observations)) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($pret->statut === 'en cours'): ?>
            <div style="margin-top:20px; padding:15px; background:#fff3cd; border-radius:4px; border:1px solid #ffeaa7;">
                <p><strong>⚠️ Ce prêt est en cours</strong></p>
                <?php if (strtotime($pret->date_fin) < time()): ?>
                    <p style="color:#dc3545;"><strong>🚨 Prêt en retard !</strong></p>
                <?php endif; ?>
                <form method="post" action="<?= BASE_URL ?>pret/return/<?= $pret->id ?>" style="display:inline;">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Marquer ce prêt comme retourné ?')">
                        <i class="fas fa-check"></i> Marquer comme retourné
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
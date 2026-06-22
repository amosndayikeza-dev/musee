<div class="container">
    <div style="background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <div>
                <span class="badge badge-<?= $exposition->statut === 'en cours' ? 'en-cours' : ($exposition->statut === 'prévue' ? 'prévue' : 'terminée') ?>" style="margin-bottom: 15px;">
                    <?= $exposition->statut ?>
                </span>
                <h1 style="font-size: 32px; color: #1a2a3a; margin-bottom: 15px;"><?= htmlspecialchars($exposition->titre) ?></h1>
                
                <div style="margin-bottom: 20px;">
                    <p><strong><i class="fas fa-map-marker-alt"></i> Lieu :</strong> <?= htmlspecialchars($exposition->lieu ?? 'Non spécifié') ?></p>
                    <p><strong><i class="fas fa-calendar"></i> Date de début :</strong> <?= date('d/m/Y', strtotime($exposition->date_debut)) ?></p>
                    <p><strong><i class="fas fa-calendar"></i> Date de fin :</strong> <?= date('d/m/Y', strtotime($exposition->date_fin)) ?></p>
                    <p><strong><i class="fas fa-clock"></i> Durée :</strong> 
                        <?php 
                            $debut = new DateTime($exposition->date_debut);
                            $fin = new DateTime($exposition->date_fin);
                            $interval = $debut->diff($fin);
                            echo $interval->days . ' jours';
                        ?>
                    </p>
                </div>
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <h3>Description</h3>
                    <p style="line-height: 1.8;"><?= nl2br(htmlspecialchars($exposition->description ?? 'Aucune description disponible')) ?></p>
                </div>
            </div>
            <div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <h3 style="color: #1a2a3a; margin-bottom: 15px;">
                        <i class="fas fa-paint-brush"></i> Œuvres exposées
                    </h3>
                    <?php if (empty($oeuvres)): ?>
                        <p style="color:#999;">Aucune œuvre associée à cette exposition.</p>
                    <?php else: ?>
                        <div style="display: grid; gap: 15px;">
                            <?php foreach ($oeuvres as $oeuvre): ?>
                                <div style="background: #fff; padding: 12px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; gap: 15px; align-items: center;">
                                    <?php if (!empty($oeuvre->photo)): ?>
                                        <img src="<?= BASE_URL . $oeuvre->photo ?>" alt="<?= htmlspecialchars($oeuvre->titre) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 4px; color: #999;">
                                            <i class="fas fa-paint-brush"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?= BASE_URL ?>public/oeuvre/show/<?= $oeuvre->id ?>" style="color: #1a2a3a; text-decoration: none; font-weight: 500;">
                                            <?= htmlspecialchars($oeuvre->titre) ?>
                                        </a>
                                        <div style="font-size: 13px; color: #888;">
                                            <?= htmlspecialchars($oeuvre->auteur_nom ?? 'Anonyme') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <a href="<?= BASE_URL ?>public/exposition" class="btn btn-outline" style="border:1px solid #ddd;">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
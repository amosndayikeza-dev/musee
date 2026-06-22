<div class="container">
    <div style="background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px;">
            <div style="text-align: center;">
                <div style="background: #e9ecef; width: 100%; max-width: 300px; height: 300px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 80px; color: #999; margin: 0 auto;">
                    <i class="fas fa-user-astronaut"></i>
                </div>
                <h2 style="margin-top: 20px; color: #1a2a3a;">
                    <?= htmlspecialchars($auteur->nom) ?>
                    <?php if (!empty($auteur->prenom)): ?>
                        <?= htmlspecialchars($auteur->prenom) ?>
                    <?php endif; ?>
                </h2>
                <p><strong>Matricule :</strong> <?= $auteur->matricule ?></p>
                <p><strong>Nationalité :</strong> <?= htmlspecialchars($auteur->nationalite ?? 'Non renseignée') ?></p>
                <p><strong>Dates :</strong> 
                    <?= $auteur->date_naissance ? date('d/m/Y', strtotime($auteur->date_naissance)) : '?' ?>
                    -
                    <?= $auteur->date_deces ? date('d/m/Y', strtotime($auteur->date_deces)) : 'Présent' ?>
                </p>
            </div>
            <div>
                <h3 style="color: #1a2a3a; margin-bottom: 15px;">Biographie</h3>
                <p style="line-height: 1.8; background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <?= nl2br(htmlspecialchars($auteur->biographie ?? 'Aucune biographie disponible')) ?>
                </p>
            </div>
        </div>

        <!-- Œuvres de l'auteur -->
        <div style="margin-top: 40px;">
            <h3 style="color: #1a2a3a; margin-bottom: 20px;">
                <i class="fas fa-paint-brush"></i> Œuvres de <?= htmlspecialchars($auteur->nom) ?>
            </h3>
            <?php if (empty($oeuvres)): ?>
                <p style="color:#999;">Aucune œuvre associée à cet auteur.</p>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                    <?php foreach ($oeuvres as $oeuvre): ?>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <?php if (!empty($oeuvre->photo)): ?>
                                <img src="<?= BASE_URL . $oeuvre->photo ?>" alt="<?= htmlspecialchars($oeuvre->titre) ?>" style="width:100%; height:150px; object-fit:cover; border-radius:4px;">
                            <?php else: ?>
                                <div style="background: #e9ecef; height:150px; display:flex; align-items:center; justify-content:center; border-radius:4px; font-size:30px; color:#999;">
                                    <i class="fas fa-paint-brush"></i>
                                </div>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>public/oeuvre/show/<?= $oeuvre->id ?>" style="color: #1a2a3a; text-decoration: none; font-weight: 500; display: block; margin-top: 10px;">
                                <?= htmlspecialchars($oeuvre->titre) ?>
                            </a>
                            <span class="badge badge-<?= str_replace(' ', '-', $oeuvre->statut) ?>" style="font-size: 11px; margin-top: 5px;">
                                <?= $oeuvre->statut ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 30px;">
            <a href="<?= BASE_URL ?>public/auteur" class="btn btn-outline" style="border:1px solid #ddd;">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<div class="container">
    <!-- Hero -->
    <div class="hero">
        <h1><i class="fas fa-user-astronaut"></i> Auteurs</h1>
        <p>Découvrez les artistes qui ont marqué l'histoire de l'art</p>
    </div>

    <!-- Barre de recherche -->
    <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <form method="get" action="<?= BASE_URL ?>public/auteur" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="keyword" class="form-control" placeholder="🔍 Rechercher un auteur..." 
                   value="<?= htmlspecialchars($keyword ?? '') ?>" style="flex:1; min-width:200px; padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
            <select name="nationalite" class="form-control" style="padding:10px 14px; border:1px solid #ddd; border-radius:6px;">
                <option value="">Toutes les nationalités</option>
                <?php foreach ($nationalites as $n): ?>
                    <option value="<?= htmlspecialchars($n->nationalite) ?>" <?= ($nationalite ?? '') == $n->nationalite ? 'selected' : '' ?>>
                        <?= htmlspecialchars($n->nationalite) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <?php if (!empty($keyword) || !empty($nationalite)): ?>
                <a href="<?= BASE_URL ?>public/auteur" class="btn btn-outline" style="border:1px solid #ddd;">Réinitialiser</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Résultats -->
    <div class="card-grid">
        <?php if (empty($auteurs)): ?>
            <p style="text-align:center; color:#999; grid-column:1/-1; padding:40px;">
                <i class="fas fa-search" style="font-size:32px; display:block; margin-bottom:10px;"></i>
                Aucun auteur trouvé
            </p>
        <?php else: ?>
            <?php foreach ($auteurs as $auteur): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if (!empty($auteur->photo)): ?>
                            <!-- CORRECTION : alt avec $auteur->nom au lieu de $exposition->titre -->
                            <img src="<?= BASE_URL . $auteur->photo ?>" alt="<?= htmlspecialchars($auteur->nom) ?>" style="width:100%; height:200px; object-fit:cover;">
                        <?php else: ?>
                            <!-- CORRECTION : icône auteur au lieu de calendrier -->
                            <i class="fas fa-user-astronaut" style="font-size:40px;"></i>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3><a href="<?= BASE_URL ?>public/auteur/show/<?= $auteur->id ?>">
                            <?= htmlspecialchars($auteur->nom) ?>
                            <?php if (!empty($auteur->prenom)): ?>
                                <?= htmlspecialchars($auteur->prenom) ?>
                            <?php endif; ?>
                        </a></h3>
                        <p><strong>Matricule :</strong> <?= $auteur->matricule ?></p>
                        <p><strong>Nationalité :</strong> <?= htmlspecialchars($auteur->nationalite ?? 'Non renseignée') ?></p>
                        <div class="meta">
                            <span>
                                <i class="fas fa-calendar"></i> 
                                <?= $auteur->date_naissance ? date('Y', strtotime($auteur->date_naissance)) : '?' ?>
                                -
                                <?= $auteur->date_deces ? date('Y', strtotime($auteur->date_deces)) : 'Présent' ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
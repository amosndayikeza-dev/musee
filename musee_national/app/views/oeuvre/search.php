<h2>Recherche avancée d'œuvres</h2>

<!-- Formulaire de recherche -->
<form method="get" action="<?= BASE_URL ?>oeuvre/search" style="margin-bottom: 30px;">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
        <div>
            <label>Mot-clé :</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword']) ?>" style="width: 100%; padding: 8px;">
        </div>
        <div>
            <label>Auteur :</label>
            <select name="auteur_id" style="width: 100%; padding: 8px;">
                <option value="">Tous</option>
                <?php foreach ($auteurs as $auteur): ?>
                <option value="<?= $auteur->id ?>" <?= ($filters['auteur_id'] == $auteur->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($auteur->nom . ' ' . $auteur->prenom) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Catégorie :</label>
            <select name="categorie_id" style="width: 100%; padding: 8px;">
                <option value="">Toutes</option>
                <?php foreach ($categories as $categorie): ?>
                <option value="<?= $categorie->id ?>" <?= ($filters['categorie_id'] == $categorie->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categorie->nom) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Statut :</label>
            <select name="statut" style="width: 100%; padding: 8px;">
                <option value="">Tous</option>
                <?php foreach ($statuts as $statut): ?>
                <option value="<?= $statut ?>" <?= ($filters['statut'] == $statut) ? 'selected' : '' ?>>
                    <?= ucfirst($statut) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Date de création (début) :</label>
            <input type="date" name="date_debut" value="<?= $filters['date_debut'] ?>" style="width: 100%; padding: 8px;">
        </div>
        <div>
            <label>Date de création (fin) :</label>
            <input type="date" name="date_fin" value="<?= $filters['date_fin'] ?>" style="width: 100%; padding: 8px;">
        </div>
    </div>
    <button type="submit" style="margin-top: 15px; padding: 10px 30px; background: #333; color: white; border: none; border-radius: 4px;">
        🔍 Rechercher
    </button>
    <a href="<?= BASE_URL ?>oeuvre/search" style="margin-left: 10px;">Réinitialiser</a>
</form>

<!-- Résultats -->
<h3>Résultats (<?= count($oeuvres) ?> œuvres trouvées)</h3>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background: #333; color: white;">
            <th style="padding: 10px;">Titre</th>
            <th style="padding: 10px;">Auteur</th>
            <th style="padding: 10px;">Catégorie</th>
            <th style="padding: 10px;">Statut</th>
            <th style="padding: 10px;">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($oeuvres)): ?>
        <tr><td colspan="5" style="text-align: center; padding: 20px;">Aucune œuvre trouvée.</td></tr>
    <?php else: ?>
        <?php foreach ($oeuvres as $oeuvre): ?>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                <a href="<?= BASE_URL ?>oeuvre/show/<?= $oeuvre->id ?>">
                    <?= htmlspecialchars($oeuvre->titre) ?>
                </a>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                <?= htmlspecialchars($oeuvre->auteur_nom ?? 'Non défini') ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                <?= htmlspecialchars($oeuvre->categorie_nom ?? 'Non défini') ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                <span style="background: <?= $oeuvre->statut === 'exposé' ? 'green' : ($oeuvre->statut === 'en restauration' ? 'orange' : 'gray') ?>; color: white; padding: 3px 8px; border-radius: 4px;">
                    <?= $oeuvre->statut ?>
                </span>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                <a href="<?= BASE_URL ?>oeuvre/edit/<?= $oeuvre->id ?>">✏️</a>
                <form method="post" action="<?= BASE_URL ?>oeuvre/delete/<?= $oeuvre->id ?>" style="display: inline;">
                    <button type="submit" onclick="return confirm('Supprimer ?')" style="background: none; border: none; color: red; cursor: pointer;">🗑️</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<p style="margin-top: 20px;"><a href="<?= BASE_URL ?>oeuvre/index">← Retour à la liste complète</a></p>
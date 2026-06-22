<h2>Résultats de recherche pour : "<?= htmlspecialchars($keyword) ?>"</h2>

<!-- Filtres par type -->
<div style="margin: 20px 0;">
    <strong>Filtrer par :</strong>
    <a href="<?= BASE_URL ?>search?q=<?= urlencode($keyword) ?>&type=all" style="margin: 0 10px; <?= $type === 'all' ? 'font-weight: bold; color: #333;' : '' ?>">Tous</a>
    <a href="<?= BASE_URL ?>search?q=<?= urlencode($keyword) ?>&type=oeuvre" style="margin: 0 10px; <?= $type === 'oeuvre' ? 'font-weight: bold; color: #333;' : '' ?>">Œuvres</a>
    <a href="<?= BASE_URL ?>search?q=<?= urlencode($keyword) ?>&type=auteur" style="margin: 0 10px; <?= $type === 'auteur' ? 'font-weight: bold; color: #333;' : '' ?>">Auteurs</a>
    <a href="<?= BASE_URL ?>search?q=<?= urlencode($keyword) ?>&type=exposition" style="margin: 0 10px; <?= $type === 'exposition' ? 'font-weight: bold; color: #333;' : '' ?>">Expositions</a>
    <a href="<?= BASE_URL ?>search?q=<?= urlencode($keyword) ?>&type=pret" style="margin: 0 10px; <?= $type === 'pret' ? 'font-weight: bold; color: #333;' : '' ?>">Prêts</a>
    <a href="<?= BASE_URL ?>search?q=<?= urlencode($keyword) ?>&type=categorie" style="margin: 0 10px; <?= $type === 'categorie' ? 'font-weight: bold; color: #333;' : '' ?>">Catégories</a>
</div>

<?php if (empty($results) || (is_array($results) && count(array_filter($results)) === 0)): ?>
    <p>Aucun résultat trouvé pour "<?= htmlspecialchars($keyword) ?>".</p>
<?php else: ?>
    <!-- Résultats par catégorie -->
    <?php if ($type === 'all' || $type === 'oeuvre'): ?>
        <?php if (!empty($results['oeuvres'])): ?>
            <div style="margin-bottom: 30px;">
                <h3>Œuvres (<?= count($results['oeuvres']) ?>)</h3>
                <ul>
                    <?php foreach ($results['oeuvres'] as $item): ?>
                        <li>
                            <a href="<?= BASE_URL ?>oeuvre/show/<?= $item->id ?>">
                                <?= htmlspecialchars($item->titre) ?>
                            </a>
                            <?php if (isset($item->auteur_nom)): ?>
                                - <?= htmlspecialchars($item->auteur_nom) ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($type === 'all' || $type === 'auteur'): ?>
        <?php if (!empty($results['auteurs'])): ?>
            <div style="margin-bottom: 30px;">
                <h3>Auteurs (<?= count($results['auteurs']) ?>)</h3>
                <ul>
                    <?php foreach ($results['auteurs'] as $item): ?>
                        <li>
                            <a href="<?= BASE_URL ?>auteur/show/<?= $item->id ?>">
                                <?= htmlspecialchars($item->nom . ' ' . $item->prenom) ?>
                            </a>
                            (<?= $item->matricule ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($type === 'all' || $type === 'exposition'): ?>
        <?php if (!empty($results['expositions'])): ?>
            <div style="margin-bottom: 30px;">
                <h3>Expositions (<?= count($results['expositions']) ?>)</h3>
                <ul>
                    <?php foreach ($results['expositions'] as $item): ?>
                        <li>
                            <a href="<?= BASE_URL ?>exposition/edit/<?= $item->id ?>">
                                <?= htmlspecialchars($item->titre) ?>
                            </a>
                            - du <?= $item->date_debut ?> au <?= $item->date_fin ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($type === 'all' || $type === 'pret'): ?>
        <?php if (!empty($results['prets'])): ?>
            <div style="margin-bottom: 30px;">
                <h3>Prêts (<?= count($results['prets']) ?>)</h3>
                <ul>
                    <?php foreach ($results['prets'] as $item): ?>
                        <li>
                            <a href="<?= BASE_URL ?>pret/edit/<?= $item->id ?>">
                                <?= htmlspecialchars($item->oeuvre_titre ?? 'Œuvre') ?>
                            </a>
                            - Emprunteur : <?= htmlspecialchars($item->emprunteur) ?>
                            (<?= $item->statut ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($type === 'all' || $type === 'categorie'): ?>
        <?php if (!empty($results['categories'])): ?>
            <div style="margin-bottom: 30px;">
                <h3>Catégories (<?= count($results['categories']) ?>)</h3>
                <ul>
                    <?php foreach ($results['categories'] as $item): ?>
                        <li>
                            <a href="<?= BASE_URL ?>categorie/edit/<?= $item->id ?>">
                                <?= htmlspecialchars($item->nom) ?>
                            </a>
                            - <?= htmlspecialchars($item->description) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<p style="margin-top: 30px;">
    <a href="<?= BASE_URL ?>admin/dashboard">← Retour au tableau de bord</a>
</p>
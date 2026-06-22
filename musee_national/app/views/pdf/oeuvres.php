<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 20px; margin: 0; }
        .header p { font-size: 14px; color: #666; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th { background: #333; color: white; padding: 8px; text-align: left; }
        table td { padding: 8px; border-bottom: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #999; }
        .statut-expose { color: green; }
        .statut-reserve { color: gray; }
        .statut-restauration { color: orange; }
        .statut-pret { color: blue; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Œuvres</h1>
        <p>Musée National - Généré le <?= date('d/m/Y à H:i') ?></p>
        <p>Total : <?= count($oeuvres) ?> œuvres</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Date création</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($oeuvres as $oeuvre): ?>
            <tr>
                <td><?= $oeuvre->id ?></td>
                <td><?= htmlspecialchars($oeuvre->titre) ?></td>
                <td><?= htmlspecialchars($oeuvre->auteur_nom ?? 'Non défini') ?></td>
                <td><?= htmlspecialchars($oeuvre->categorie_nom ?? 'Non défini') ?></td>
                <td>
                    <span class="statut-<?= str_replace('é', 'e', $oeuvre->statut) ?>">
                        <?= $oeuvre->statut ?>
                    </span>
                </td>
                <td><?= $oeuvre->date_creation ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré automatiquement - Musée National</p>
    </div>
</body>
</html>
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
        .statut-en-cours { color: blue; }
        .statut-retourne { color: green; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #999; }
        .retard { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Prêts</h1>
        <p>Musée National - Généré le <?= date('d/m/Y à H:i') ?></p>
        <p>Total : <?= count($prets) ?> prêts</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Œuvre</th>
                <th>Emprunteur</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prets as $pret): 
                $estEnRetard = ($pret->statut === 'en cours' && $pret->date_fin < date('Y-m-d'));
            ?>
            <tr>
                <td><?= $pret->id ?></td>
                <td><?= htmlspecialchars($pret->oeuvre_titre ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pret->emprunteur) ?></td>
                <td><?= $pret->date_debut ?></td>
                <td <?= $estEnRetard ? 'class="retard"' : '' ?>><?= $pret->date_fin ?></td>
                <td>
                    <span class="statut-<?= str_replace('é', 'e', $pret->statut) ?>">
                        <?= $pret->statut ?>
                        <?php if ($estEnRetard): ?> ⚠️ RETARD<?php endif; ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré automatiquement - Musée National</p>
    </div>
</body>
</html>
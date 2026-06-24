<div class="container">
    <h1><i class="fas fa-book"></i> Guide d'utilisation</h1>

    <!-- === Connexion === -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>🔐 Connexion</h2>
        <ol>
            <li>Accédez à la page de connexion : <code>/auth/login</code></li>
            <li>Saisissez votre adresse email</li>
            <li>Saisissez votre mot de passe</li>
            <li>Cliquez sur "Se connecter"</li>
        </ol>
        <p><strong>Comptes de test :</strong></p>
        <ul>
            <li><strong>Admin :</strong> admin@musee.com / admin123</li>
            <li><strong>Conservateur :</strong> conservateur@musee.com / conservateur123</li>
            <li><strong>Visiteur :</strong> visiteur@test.com / visiteur123</li>
        </ul>
        <p><em>⚠️ Après 5 tentatives de connexion échouées, le compte est verrouillé pendant 30 minutes.</em></p>
    </div>

    <!-- === Tableau de bord === -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>📊 Tableau de bord (Admin/Conservateur)</h2>
        <p>Le tableau de bord affiche les indicateurs clés :</p>
        <ul>
            <li>Nombre total d'œuvres, auteurs, catégories, expositions</li>
            <li>Nombre de prêts en cours et en retard</li>
            <li>Restaurations en cours</li>
            <li>Coût total des restaurations</li>
            <li>Graphiques : répartition par statut, par catégorie, top auteurs, mouvements</li>
        </ul>
        <p><em>Les graphiques sont interactifs (Chart.js).</em></p>
    </div>

    <!-- === Gestion des œuvres === -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>🖼️ Gestion des œuvres</h2>
        <h3>Ajouter une œuvre</h3>
        <ol>
            <li>Aller dans "Œuvres" → "Ajouter"</li>
            <li>Remplir le formulaire (titre, description, auteur, catégorie, statut, etc.)</li>
            <li>Optionnel : ajouter une photo</li>
            <li>Cliquer sur "Enregistrer"</li>
        </ol>
        <h3>Modifier une œuvre</h3>
        <ol>
            <li>Cliquer sur l'icône ✏️ dans la ligne correspondante</li>
            <li>Modifier les champs</li>
            <li>Cliquer sur "Mettre à jour"</li>
        </ol>
        <h3>Supprimer une œuvre</h3>
        <ol>
            <li>Cliquer sur l'icône 🗑️ (réservé à l'admin)</li>
            <li>Confirmer la suppression</li>
        </ol>
        <h3>Archiver/Restaurer une œuvre</h3>
        <ol>
            <li>Cliquer sur l'icône 📦 pour archiver</li>
            <li>Pour restaurer, cliquer sur l'icône ↩️</li>
        </ol>
        <h3>Exports</h3>
        <ul>
            <li>PDF : Export en PDF de la liste</li>
            <li>Excel : Export en Excel (XLSX)</li>
        </ul>
    </div>

    <!-- === Gestion des prêts === -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>🤝 Gestion des prêts</h2>
        <h3>Ajouter un prêt</h3>
        <ol>
            <li>Aller dans "Prêts" → "Ajouter"</li>
            <li>Sélectionner l'œuvre, l'emprunteur, les dates</li>
            <li>Enregistrer</li>
        </ol>
        <h3>Marquer un prêt comme retourné</h3>
        <ol>
            <li>Cliquer sur l'icône ✅ dans la ligne du prêt</li>
        </ol>
        <p><em>Les prêts en retard génèrent des notifications automatiques.</em></p>
    </div>

    <!-- === Gestion des restaurations === -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>🔧 Gestion des restaurations</h2>
        <h3>Ajouter une restauration</h3>
        <ol>
            <li>Aller dans "Restaurations" → "Ajouter"</li>
            <li>Remplir le formulaire</li>
            <li>Enregistrer</li>
        </ol>
        <h3>Marquer comme terminée</h3>
        <ol>
            <li>Cliquer sur l'icône ✅ dans la ligne</li>
        </ol>
    </div>

    <!-- === Audit === -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <h2>📝 Journal d'audit</h2>
        <p>L'audit enregistre toutes les actions effectuées (INSERT, UPDATE, DELETE) avec :</p>
        <ul>
            <li>Utilisateur concerné</li>
            <li>Action réalisée</li>
            <li>Table et enregistrement concerné</li>
            <li>Anciennes et nouvelles valeurs</li>
            <li>Date et heure</li>
        </ul>
        <p>Filtres disponibles : par action, par table, par plage de dates.</p>
    </div>

    <!-- === Sauvegardes === -->
    <div style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <h2>💾 Sauvegardes</h2>
        <h3>Créer une sauvegarde</h3>
        <ol>
            <li>Aller dans "Sauvegardes"</li>
            <li>Cliquer sur "Créer une sauvegarde"</li>
        </ol>
        <h3>Restaurer une sauvegarde</h3>
        <ol>
            <li>Cliquer sur l'icône ↩️ dans la ligne</li>
            <li>Confirmer la restauration</li>
        </ol>
        <p><em>Les sauvegardes sont stockées dans le dossier <code>backups/</code>.</em></p>
    </div>
</div>
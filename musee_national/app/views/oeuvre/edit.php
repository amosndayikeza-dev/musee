<div class="form-card">
    <div class="form-title">
        <i class="fas fa-edit"></i> Modifier l'œuvre : <?= htmlspecialchars($oeuvre->titre) ?>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>admin/oeuvre/update/<?= $oeuvre->id ?>" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Titre <span class="required">*</span></label>
                <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($oeuvre->titre) ?>" required>
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="exposé" <?= $oeuvre->statut === 'exposé' ? 'selected' : '' ?>>Exposé</option>
                    <option value="en réserve" <?= $oeuvre->statut === 'en réserve' ? 'selected' : '' ?>>En réserve</option>
                    <option value="en restauration" <?= $oeuvre->statut === 'en restauration' ? 'selected' : '' ?>>En restauration</option>
                    <option value="en prêt" <?= $oeuvre->statut === 'en prêt' ? 'selected' : '' ?>>En prêt</option>
                </select>
            </div>
        </div>

        <div class="form-group form-row-full">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($oeuvre->description ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date de création</label>
                <input type="date" name="date_creation" class="form-control" value="<?= $oeuvre->date_creation ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Technique</label>
                <input type="text" name="technique" class="form-control" value="<?= htmlspecialchars($oeuvre->technique ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Dimensions</label>
                <input type="text" name="dimensions" class="form-control" value="<?= htmlspecialchars($oeuvre->dimensions ?? '') ?>" placeholder="Ex: 73 x 92 cm">
            </div>
            <div class="form-group">
                <label>Auteur</label>
                <select name="auteur_id" class="form-control">
                    <option value="">Sélectionner un auteur</option>
                    <?php foreach ($auteurs as $auteur): ?>
                        <option value="<?= $auteur->id ?>" <?= ($oeuvre->auteur_id == $auteur->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($auteur->nom . ' ' . ($auteur->prenom ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Catégorie</label>
                <select name="categorie_id" class="form-control">
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= $categorie->id ?>" <?= ($oeuvre->categorie_id == $categorie->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categorie->nom) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Photo</label>
                <?php if (!empty($oeuvre->photo)): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?= BASE_URL . $oeuvre->photo ?>" alt="Photo actuelle" style="max-width: 100px; max-height: 100px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="photo" class="form-control" accept="image/*">
                <small style="color:#888;">Formats : JPG, PNG, GIF, WEBP (max 2 Mo)</small>
            </div>
        </div>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>admin/oeuvre" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
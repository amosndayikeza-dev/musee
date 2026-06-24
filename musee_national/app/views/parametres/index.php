<div class="form-card">
    <div class="form-title">
        <i class="fas fa-cogs"></i> Paramètres du système
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>admin/parametres/update">
        <?php foreach ($parametres as $param): ?>
            <div class="form-group form-row-full">
                <label for="param_<?= $param->cle ?>">
                    <?= ucfirst(str_replace('_', ' ', $param->cle)) ?>
                    <?php if ($param->description): ?>
                        <small style="color:#888; display:block; font-weight:normal; font-size:12px;">
                            <?= htmlspecialchars($param->description) ?>
                        </small>
                    <?php endif; ?>
                </label>

                <?php if ($param->type === 'boolean'): ?>
                    <select name="<?= $param->cle ?>" class="form-control" id="param_<?= $param->cle ?>">
                        <option value="1" <?= $param->valeur == 1 ? 'selected' : '' ?>>Oui</option>
                        <option value="0" <?= $param->valeur == 0 ? 'selected' : '' ?>>Non</option>
                    </select>

                <?php elseif ($param->type === 'email'): ?>
                    <input type="email" name="<?= $param->cle ?>" class="form-control" 
                           id="param_<?= $param->cle ?>" value="<?= htmlspecialchars($param->valeur) ?>">

                <?php elseif ($param->type === 'number'): ?>
                    <input type="number" name="<?= $param->cle ?>" class="form-control" 
                           id="param_<?= $param->cle ?>" value="<?= htmlspecialchars($param->valeur) ?>">

                <?php elseif ($param->type === 'textarea'): ?>
                    <textarea name="<?= $param->cle ?>" class="form-control" rows="3" 
                              id="param_<?= $param->cle ?>"><?= htmlspecialchars($param->valeur) ?></textarea>

                <?php else: ?>
                    <input type="text" name="<?= $param->cle ?>" class="form-control" 
                           id="param_<?= $param->cle ?>" value="<?= htmlspecialchars($param->valeur) ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="form-row" style="margin-top: 20px;">
            <div class="form-group" style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= BASE_URL ?>admin/dashboard" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
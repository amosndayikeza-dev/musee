<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser - Musée National</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Même style que forgot.php */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a2a3a 0%, #2c4058 100%);
            display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;
        }
        .reset-container { width: 100%; max-width: 440px; }
        .reset-card {
            background: #fff; padding: 40px 35px 30px; border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.5s ease;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .reset-header { text-align: center; margin-bottom: 30px; }
        .reset-header .icon { font-size: 48px; color: #c9a84c; }
        .reset-header h1 { font-size: 24px; color: #1a2a3a; margin: 10px 0 5px; }
        .reset-header p { color: #888; font-size: 14px; }
        .alert {
            padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert i { font-size: 18px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px; }
        .form-group input {
            width: 100%; padding: 12px 14px; border: 2px solid #e5e7eb; border-radius: 8px;
            font-size: 14px; font-family: inherit; background: #fafbfc;
        }
        .form-group input:focus { outline: none; border-color: #c9a84c; background: #fff; }
        .btn {
            width: 100%; padding: 13px; background: #1a2a3a; color: #fff; border: none;
            border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.3s;
        }
        .btn:hover { background: #2c4058; }
        .reset-footer { text-align: center; margin-top: 20px; padding-top: 18px; border-top: 1px solid #e9ecef; font-size: 13px; color: #888; }
        .reset-footer a { color: #1a2a3a; text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <div class="icon"><i class="fas fa-lock"></i></div>
                <h1>Nouveau mot de passe</h1>
                <p>Définissez votre nouveau mot de passe</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>reset/update">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Minimum 6 caractères" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
                </div>
                <button type="submit" class="btn">Réinitialiser</button>
            </form>

            <div class="reset-footer">
                <a href="<?= BASE_URL ?>auth/login"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
            </div>
        </div>
    </div>
</body>
</html>
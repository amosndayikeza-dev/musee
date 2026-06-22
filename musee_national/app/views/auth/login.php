<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?? 'Musée National' ?> - Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Conteneur principal */
        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }

        /* Carte de connexion */
        .login-box {
            background: #ffffff;
            padding: 40px 35px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        /* Logo / Titre */
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header .icon {
            font-size: 48px;
            color: #1a2a3a;
            margin-bottom: 10px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1a2a3a;
            margin: 0;
        }

        .login-header p {
            color: #888;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Messages d'erreur */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert i {
            color: #dc2626;
        }

        /* Champs du formulaire */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: inherit;
            background: #fafbfc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #c9a84c;
            background: #fff;
        }

        .form-group input.error {
            border-color: #dc2626;
        }

        /* Options (checkbox + mot de passe oublié) */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 18px 0 22px;
            font-size: 13px;
        }

        .form-options label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #555;
            cursor: pointer;
        }

        .form-options label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #c9a84c;
        }

        .form-options a {
            color: #c9a84c;
            text-decoration: none;
            font-weight: 500;
        }

        .form-options a:hover {
            text-decoration: underline;
        }

        /* Bouton de connexion */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: #1a2a3a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            font-family: inherit;
        }

        .btn-login:hover {
            background: #2c4058;
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Pied de page */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #e9ecef;
            font-size: 13px;
            color: #888;
        }

        .login-footer a {
            color: #1a2a3a;
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            color: #c9a84c;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-box {
                padding: 30px 20px 25px;
            }

            .login-header h1 {
                font-size: 20px;
            }

            .form-options {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-box">

            <!-- En-tête -->
            <div class="login-header">
                <div class="icon">
                    <i class="fas fa-landmark"></i>
                </div>
                <h1>Connexion</h1>
                <p>Espace d'administration</p>
            </div>

            <!-- Message d'erreur -->
            <?php if (isset($error)): ?>
                <div class="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <form method="post" action="<?= BASE_URL ?>auth/login">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           class="<?= isset($error) ? 'error' : '' ?>"
                           placeholder="admin@musee.com" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                           required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" 
                           class="<?= isset($error) ? 'error' : '' ?>"
                           placeholder="••••••••" required>
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember" <?= isset($_POST['remember']) ? 'checked' : '' ?>>
                        Se souvenir de moi
                    </label>
                    <a href="#">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>

            </form>

            <!-- Pied de page -->
            <div class="login-footer">
                <a href="<?= BASE_URL ?>home/index">
                    <i class="fas fa-arrow-left"></i> Retour au site
                </a>
            </div>

        </div>
    </div>

</body>
</html>
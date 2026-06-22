<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?? 'Musée National' ?> - Connexion</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1a2a3a 0%, #2c4058 50%, #1a2a3a 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 45px 40px 35px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* En-tête */
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: #1a2a3a;
            border-radius: 50%;
            margin-bottom: 15px;
            transition: transform 0.3s;
        }

        .login-header .logo-icon:hover {
            transform: scale(1.05);
        }

        .login-header .logo-icon i {
            font-size: 38px;
            color: #c9a84c;
        }

        .login-header h1 {
            font-size: 26px;
            color: #1a2a3a;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .login-header .subtitle {
            color: #888;
            font-size: 14px;
            margin-top: 6px;
            font-weight: 400;
        }

        .login-header .divider {
            width: 50px;
            height: 3px;
            background: #c9a84c;
            margin: 12px auto 0;
            border-radius: 2px;
        }

        /* Messages d'erreur */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-danger i {
            color: #dc2626;
            font-size: 18px;
        }

        /* Formulaire */
        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            font-size: 13px;
            color: #333;
            letter-spacing: 0.3px;
        }

        .form-group label .required {
            color: #dc2626;
            margin-left: 2px;
        }

        .input-group {
            position: relative;
        }

        .input-group .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            transition: color 0.3s;
        }

        .input-group .form-control {
            width: 100%;
            padding: 13px 14px 13px 46px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: #fafbfc;
            color: #1a2a3a;
            font-family: inherit;
        }

        .input-group .form-control:focus {
            outline: none;
            border-color: #c9a84c;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(201, 168, 76, 0.12);
        }

        .input-group .form-control.error {
            border-color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }

        .input-group .form-control::placeholder {
            color: #b0b0b0;
        }

        .input-group:focus-within .input-icon {
            color: #c9a84c;
        }

        /* Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 28px;
            font-size: 13px;
        }

        .form-options .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #555;
            cursor: pointer;
            font-weight: 400;
        }

        .form-options .checkbox-label input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: #c9a84c;
            cursor: pointer;
        }

        .form-options .forgot-link {
            color: #c9a84c;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .form-options .forgot-link:hover {
            color: #b8963e;
            text-decoration: underline;
        }

        /* Bouton */
        .btn-login {
            width: 100%;
            padding: 15px;
            background: #1a2a3a;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-family: inherit;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: #2c4058;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(26, 42, 58, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            font-size: 18px;
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Pied de page */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #e5e7eb;
        }

        .login-footer .footer-text {
            color: #888;
            font-size: 13px;
        }

        .login-footer .footer-text a {
            color: #1a2a3a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .login-footer .footer-text a:hover {
            color: #c9a84c;
        }

        .login-footer .footer-version {
            color: #ccc;
            font-size: 11px;
            margin-top: 8px;
        }

        .login-footer .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f3f4f6;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }

        .login-footer .security-badge i {
            color: #c9a84c;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px 25px;
            }

            .login-header h1 {
                font-size: 22px;
            }

            .login-header .logo-icon {
                width: 65px;
                height: 65px;
            }

            .login-header .logo-icon i {
                font-size: 30px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .btn-login {
                font-size: 15px;
                padding: 13px;
            }
        }

        @media (max-width: 380px) {
            .login-card {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card"></div>
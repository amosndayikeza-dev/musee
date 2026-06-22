<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succès - Musée National</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a2a3a 0%, #2c4058 100%);
            display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;
        }
        .success-container { width: 100%; max-width: 440px; }
        .success-card {
            background: #fff; padding: 40px 35px 30px; border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); text-align: center; animation: slideUp 0.5s ease;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .success-header .icon { font-size: 64px; color: #28a745; margin-bottom: 15px; }
        .success-header h1 { font-size: 24px; color: #1a2a3a; margin: 10px 0 5px; }
        .success-header p { color: #666; font-size: 16px; margin: 10px 0 20px; }
        .btn {
            display: inline-block; padding: 12px 30px; background: #1a2a3a; color: #fff; border: none;
            border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none;
        }
        .btn:hover { background: #2c4058; }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <h1>✅ Succès !</h1>
                <p><?= htmlspecialchars($message) ?></p>
                <a href="<?= BASE_URL ?>auth/login" class="btn">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
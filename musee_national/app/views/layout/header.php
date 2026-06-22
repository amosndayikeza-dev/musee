<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?? 'Musée National' ?> - <?= $pageTitle ?? 'Administration' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php
    // Vérifier si la session va expirer
    use App\Middlewares\SessionMiddleware;
    
    $willExpire = SessionMiddleware::willExpireSoon();
    $remainingTime = SessionMiddleware::getRemainingTime();
    ?>
    
    <?php if ($willExpire): ?>
    <style>
        .session-warning {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ff9800;
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.5s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .session-warning i { font-size: 20px; }
        .session-warning .countdown { font-weight: 700; font-size: 18px; }
    </style>
    <?php endif; ?>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar gauche -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fas fa-landmark"></i> Musée</h2>
                <span class="brand-sub">National</span>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="<?= BASE_URL ?>admin/dashboard" class="<?= ($_GET['url'] ?? '') === 'admin/dashboard' ? 'active' : '' ?>">
                            <i class="fas fa-chart-pie"></i> Tableau de bord
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/oeuvre" class="<?= strpos($_GET['url'] ?? '', 'admin/oeuvre') !== false ? 'active' : '' ?>">
                            <i class="fas fa-paint-brush"></i> Œuvres
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/auteur" class="<?= strpos($_GET['url'] ?? '', 'admin/auteur') !== false ? 'active' : '' ?>">
                            <i class="fas fa-user-astronaut"></i> Auteurs
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/categorie" class="<?= strpos($_GET['url'] ?? '', 'admin/categorie') !== false ? 'active' : '' ?>">
                            <i class="fas fa-tags"></i> Catégories
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/exposition" class="<?= strpos($_GET['url'] ?? '', 'admin/exposition') !== false ? 'active' : '' ?>">
                            <i class="fas fa-calendar-alt"></i> Expositions
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/pret" class="<?= strpos($_GET['url'] ?? '', 'admin/pret') !== false ? 'active' : '' ?>">
                            <i class="fas fa-handshake"></i> Prêts
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/restauration" class="<?= strpos($_GET['url'] ?? '', 'admin/restauration') !== false ? 'active' : '' ?>">
                            <i class="fas fa-tools"></i> Restaurations
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/mouvement" class="<?= strpos($_GET['url'] ?? '', 'admin/mouvement') !== false ? 'active' : '' ?>">
                            <i class="fas fa-exchange-alt"></i> Mouvements
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li>
                            <a href="<?= BASE_URL ?>admin/utilisateur" class="<?= strpos($_GET['url'] ?? '', 'admin/utilisateur') !== false ? 'active' : '' ?>">
                                <i class="fas fa-users"></i> Utilisateurs
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="<?= BASE_URL ?>profil/index" class="<?= strpos($_GET['url'] ?? '', 'profil') !== false ? 'active' : '' ?>">
                            <i class="fas fa-user-circle"></i> Mon profil
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= BASE_URL ?>auth/logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </aside>

        <!-- Contenu principal -->
        <div class="main-content">
            <!-- Header supérieur -->
            <header class="top-header">
                <div class="header-left">
                    <button id="sidebarToggle" class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="page-title"><?= $pageTitle ?? 'Administration' ?></span>
                </div>
                <div class="header-right">
                    <span class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <?= htmlspecialchars($_SESSION['nom'] ?? 'Utilisateur') ?>
                        <small>(<?= $_SESSION['role'] ?? 'visiteur' ?>)</small>
                    </span>
                </div>
            </header>

            <!-- Zone de contenu -->
            <main class="content">

            <?php
    use App\Services\NotificationService;

    $notificationService = new NotificationService();
    $unreadCount = 0;
    $notifications = [];

    if (isset($_SESSION['user_id'])) {
        $unreadCount = $notificationService->countUnread($_SESSION['user_id']);
        $notifications = $notificationService->getUnread($_SESSION['user_id'], 5);
    }
    ?>

    <div class="header-right">
        <!-- Notifications -->
        <div class="notifications-wrapper" style="position: relative; margin-right: 15px;">
            <button id="notificationToggle" style="background: none; border: none; color: #555; font-size: 20px; cursor: pointer; position: relative;">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: #fff; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold;">
                        <?= $unreadCount ?>
                    </span>
                <?php endif; ?>
            </button>
            <div id="notificationDropdown" style="display: none; position: absolute; right: 0; top: 35px; width: 350px; max-height: 400px; overflow-y: auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); z-index: 1000;">
                <div style="padding: 10px 15px; border-bottom: 1px solid #eee;">
                    <strong>Notifications</strong>
                    <?php if ($unreadCount > 0): ?>
                        <a href="<?= BASE_URL ?>notification/markAll" style="float: right; font-size: 12px; color: #c9a84c; text-decoration: none;">Tout marquer comme lu</a>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if (empty($notifications)): ?>
                        <p style="padding: 20px; text-align: center; color: #888;">Aucune notification</p>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div style="padding: 10px 15px; border-bottom: 1px solid #f5f5f5; <?= $notification->est_lu ? '' : 'background: #f8f9fa;' ?>">
                                <div style="font-weight: 500; font-size: 13px;"><?= htmlspecialchars($notification->titre) ?></div>
                                <div style="font-size: 12px; color: #666;"><?= htmlspecialchars($notification->message) ?></div>
                                <div style="font-size: 11px; color: #999; margin-top: 3px;">
                                    <?= date('d/m/Y H:i', strtotime($notification->date_creation)) ?>
                                    <?php if ($notification->lien): ?>
                                        <a href="<?= BASE_URL . $notification->lien ?>" style="color: #c9a84c; text-decoration: none;">Voir</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if ($unreadCount > 0): ?>
                    <div style="padding: 8px 15px; border-top: 1px solid #eee; text-align: center;">
                        <a href="<?= BASE_URL ?>notification/all" style="color: #1a2a3a; text-decoration: none; font-size: 13px;">Voir toutes les notifications</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <span class="user-info">
            <?php if (!empty($_SESSION['photo'])): ?>
                <img src="<?= BASE_URL . $_SESSION['photo'] ?>" alt="Photo de profil" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #c9a84c;">
            <?php else: ?>
                <i class="fas fa-user-circle" style="font-size: 28px; color: #c9a84c;"></i>
            <?php endif; ?>
            <?= htmlspecialchars($_SESSION['nom'] ?? 'Utilisateur') ?>
            <small>(<?= $_SESSION['role'] ?? 'visiteur' ?>)</small>
        </span>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('notificationToggle');
        const dropdown = document.getElementById('notificationDropdown');
        
        if (toggle && dropdown) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            
            document.addEventListener('click', function() {
                dropdown.style.display = 'none';
            });
            
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
    </script>
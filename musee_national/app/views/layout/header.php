<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?? 'Musée National' ?> - <?= $pageTitle ?? 'Administration' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/responsive.css">
    
    <?php
    // Récupérer le thème actif
    use App\Models\ThemeModel;
    $themeModel = new ThemeModel();
    $activeTheme = $themeModel->getActive();
    ?>
    <style>
    :root {
        --couleur-primaire: <?= $activeTheme ? $activeTheme->couleur_primaire : '#1a2a3a' ?>;
        --couleur-secondaire: <?= $activeTheme ? $activeTheme->couleur_secondaire : '#c9a84c' ?>;
        --couleur-fond: <?= $activeTheme ? $activeTheme->couleur_fond : '#f4f6f9' ?>;
        --couleur-texte: <?= $activeTheme ? $activeTheme->couleur_texte : '#333333' ?>;
        --couleur-blanc: #ffffff;
    }
</style>

<?php
// Définir le nombre de notifications non lues
use App\Services\NotificationService;
$notificationService = new NotificationService();
$unreadCount = 0;
if (isset($_SESSION['user_id'])) {
    $unreadCount = $notificationService->countUnread($_SESSION['user_id']);
}
?>

<style>
    /* === SURCHARGE DES COULEURS AVEC LES VARIABLES === */
    .sidebar {
        background: var(--couleur-primaire);
    }
    .sidebar-brand h2 {
        color: var(--couleur-secondaire);
    }
    .sidebar-nav ul li a.active {
        border-left-color: var(--couleur-secondaire);
        background: rgba(201, 168, 76, 0.15);
    }
    .sidebar-nav ul li a:hover {
        border-left-color: var(--couleur-secondaire);
    }
    .btn-gold {
        background: var(--couleur-secondaire);
        color: #fff;
    }
    .btn-gold:hover {
        background: var(--couleur-secondaire);
        filter: brightness(0.9);
    }
    .btn-primary {
        background: var(--couleur-primaire);
        color: #fff;
    }
    .btn-primary:hover {
        background: var(--couleur-primaire);
        filter: brightness(1.2);
    }
    .kpi-card {
        border-top-color: var(--couleur-secondaire);
    }
    .content {
        background: var(--couleur-fond);
    }
    .top-header {
        background: var(--couleur-blanc, #ffffff);
    }
    .badge-gold {
        background: var(--couleur-secondaire);
        color: #fff;
    }
    .pagination .page-item.active .page-link {
        background: var(--couleur-primaire);
        border-color: var(--couleur-primaire);
    }
    .form-control:focus {
        border-color: var(--couleur-secondaire);
        box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.12);
    }
    .btn-login {
        background: var(--couleur-primaire);
    }
    .btn-login:hover {
        background: var(--couleur-primaire);
        filter: brightness(0.8);
    }
    .session-warning {
        background: var(--couleur-secondaire);
    }
    /* Ajoutez ici d'autres sélecteurs si nécessaire */
</style>

<!-- BLOC DE SURCHARGE -->
<style>
    .sidebar { background: var(--couleur-primaire); }
    .sidebar-brand h2 { color: var(--couleur-secondaire); }
    .btn-gold { background: var(--couleur-secondaire); }
    .btn-primary { background: var(--couleur-primaire); }
    .kpi-card { border-top-color: var(--couleur-secondaire); }
    .content { background: var(--couleur-fond); }
    /* ... etc */
</style>
    <?php $unread = "" ;?>
    <!-- MAINTENANT charger style.css (il pourra utiliser les variables) -->
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
                    <li>
                        <a href="<?= BASE_URL ?>admin/audit" class="<?= strpos($_GET['url'] ?? '', 'admin/audit') !== false ? 'active' : '' ?>">
                            <i class="fas fa-clipboard-list"></i> Audit
                        </a>
                    </li>

                     <!-- ✅ MESSAGES : visible par admin ET conservateur -->
                    <li>
                        <a href="<?= BASE_URL ?>admin/messages">
                            <i class="fas fa-envelope"></i> Messages
                            <?php if ($unread > 0): ?>
                                <span class="badge badge-danger"><?= $unread ?></span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- ADMIN UNIQUEMENT -->
                    <li>
                        <a href="<?= BASE_URL ?>admin/themes" class="<?= strpos($_GET['url'] ?? '', 'admin/themes') !== false ? 'active' : '' ?>">
                            <i class="fas fa-palette"></i> Thèmes
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/parametres" class="<?= strpos($_GET['url'] ?? '', 'admin/parametres') !== false ? 'active' : '' ?>">
                            <i class="fas fa-cogs"></i> Paramètres
                        </a>
                    </li> 
                    <li>  
                        <a href="<?= BASE_URL ?>documentation/index" class="<?= strpos($_GET['url'] ?? '', 'documentation') !== false ? 'active' : '' ?>">
                            <i class="fas fa-book"></i> Documentation
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/corbeille" class="<?= strpos($_GET['url'] ?? '', 'admin/corbeille') !== false ? 'active' : '' ?>">
                            <i class="fas fa-trash-alt"></i> Corbeille
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>chat" class="<?= strpos($_GET['url'] ?? '', 'chat') !== false ? 'active' : '' ?>">
                            <i class="fas fa-comment-dots"></i> Chat
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>admin/backup" class="<?= strpos($_GET['url'] ?? '', 'admin/backup') !== false ? 'active' : '' ?>">
                            <i class="fas fa-database"></i> Sauvegardes
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>notification/index" class="<?= strpos($_GET['url'] ?? '', 'notification/index') !== false ? 'active' : '' ?>">
                            <i class="fas fa-bell"></i> Notifications
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge badge-danger"><?= $unreadCount ?></span>
                            <?php endif; ?>
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
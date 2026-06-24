<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?? 'Musée National' ?> - <?= $pageTitle ?? 'Accueil' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/public.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header public - Sans authentification -->
    <header class="public-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?= BASE_URL ?>">
                        <i class="fas fa-landmark"></i>
                        <span><?= SITE_NAME ?></span>
                    </a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="<?= BASE_URL ?>public/home" class="<?= ($_GET['url'] ?? '') === 'public/home' ? 'active' : '' ?>">Accueil</a></li>
                        <li><a href="<?= BASE_URL ?>public/oeuvre" class="<?= strpos($_GET['url'] ?? '', 'public/oeuvre') !== false ? 'active' : '' ?>">Œuvres</a></li>
                        <li><a href="<?= BASE_URL ?>public/auteur" class="<?= strpos($_GET['url'] ?? '', 'public/auteur') !== false ? 'active' : '' ?>">Auteurs</a></li>
                        <li><a href="<?= BASE_URL ?>public/exposition" class="<?= strpos($_GET['url'] ?? '', 'public/exposition') !== false ? 'active' : '' ?>">Expositions</a></li>
                        <?php /*if (isset($_SESSION['user_id'])): */?>
                            <li><a href="<?= BASE_URL ?>chat"> <!--<i class="fas fa-comment-dots"></i> Chat</a> --></li>
                        <?php /* endif; */?>
                        <li><a href="<?= BASE_URL ?>public/contact" class="<?= strpos($_GET['url'] ?? '', 'public/contact') !== false ? 'active' : '' ?>">Contact</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <!-- Lien vers l'administration (visible uniquement si connecté avec les droits) -->
                    <?php if (isset($_SESSION['user_id']) && in_array($_SESSION['role'], ['admin', 'conservateur'])): ?>
                        <a href="<?= BASE_URL ?>admin/dashboard" class="btn btn-admin">
                            <i class="fas fa-user-cog"></i> Administration
                        </a>
                    <?php endif; ?>
                </div>
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Menu mobile -->
    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="<?= BASE_URL ?>">Accueil</a></li>
            <li><a href="<?= BASE_URL ?>public/oeuvre">Œuvres</a></li>
            <li><a href="<?= BASE_URL ?>public/auteur">Auteurs</a></li>
            <li><a href="<?= BASE_URL ?>public/exposition">Expositions</a></li>
            <li><a href="<?= BASE_URL ?>public/contact">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= BASE_URL ?>admin/dashboard">Administration</a></li>
                <li><a href="<?= BASE_URL ?>auth/logout">Déconnexion</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <main class="public-main">
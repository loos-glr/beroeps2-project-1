<?php
/**
 * Gedeelde header + <head> (mobile first).
 *
 * Verwacht dat bootstrap.php al is geladen en dat $pageTitle is gezet.
 */
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) ($pageTitle ?? 'Stop de Ontkoking')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800;900&display=swap"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?= htmlspecialchars(asset('css/app.css')) ?>">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-inner">
                <a class="brand-logo" href="<?= htmlspecialchars(url('/')) ?>">
                    <svg viewBox="0 0 32 32" aria-hidden="true">
                        <circle cx="16" cy="16" r="14" fill="#FF6B35"></circle>
                        <path d="M11 13c0-2.2 2.2-4 5-4s5 1.8 5 4c0 3-3 5-5 8-2-3-5-5-5-8z" fill="#FFF8F0"></path>
                        <circle cx="16" cy="13" r="2" fill="#FF6B35"></circle>
                    </svg>
                    <span>Smaakmaatje</span>
                </a>

                <nav class="desktop-nav" aria-label="Hoofdnavigatie">
                    <a class="active-link" href="<?= htmlspecialchars(url('recipes')) ?>">Recepten</a>
                    <a href="<?= htmlspecialchars(url('recipes')) ?>">Diner</a>
                    <a href="#">Over Ons</a>
                    <a href="#">Account</a>
                </nav>

                <div class="header-actions">
                    <button type="button" class="icon-btn" title="Zoeken" aria-label="Zoeken">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                    <button type="button" class="avatar-btn" title="Account" aria-label="Account">S</button>
                </div>
            </div>
        </div>
    </header>
    <main class="site-main">

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
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?= htmlspecialchars(asset('css/app.css')) ?>">
</head>
<body>
    <header class="site-header">
        <a class="site-header__brand" href="<?= htmlspecialchars(url('/')) ?>">Smaakmaatje</a>
        <nav class="site-header__nav" aria-label="Hoofdnavigatie">
            <a href="<?= htmlspecialchars(url('/')) ?>">Home</a>
            <a href="<?= htmlspecialchars(url('recipes')) ?>">Recepten</a>
        </nav>
    </header>
    <main class="site-main">

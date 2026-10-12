<?php
/**
 * Homepagina.
 */
$categories = [
    ['icon' => '🥞', 'name' => 'Ontbijt'],
    ['icon' => '🥪', 'name' => 'Lunch'],
    ['icon' => '🍛', 'name' => 'Diner'],
    ['icon' => '🥗', 'name' => 'Voorgerecht'],
    ['icon' => '🍝', 'name' => 'Hoofdgerecht'],
    ['icon' => '🍨', 'name' => 'Nagerecht'],
];
?>
<div class="container">

    <!-- Hero -->
    <div class="home-hero">
        <div class="hero-graphic">
            <span class="hero-emoji">🍛</span>
        </div>
        <div class="hero-content">
            <span class="hero-tagline">🔥 Stop de Ontkoking</span>
            <h1 class="hero-title">Wat eten we vandaag? 🍳</h1>
            <p class="hero-desc">Geen stress na college. Kook binnen 15 minuten iets gezonds en goedkopers dan Thuisbezorgd!</p>
            <a class="hero-cta" href="<?= htmlspecialchars(url('recipes')) ?>">
                <span>Kook vandaag nog!</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </div>

    <!-- Zoekbalk -->
    <div class="search-bar-wrap">
        <input type="text" class="search-input" placeholder="Zoek op ingrediënt (bijv. eieren, pasta, rijst)...">
        <span class="search-icon-inside">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </span>
    </div>

    <!-- Categorieën -->
    <div class="section-header">
        <h2 class="section-title">Kies een Categorie 🍽️</h2>
        <a class="section-link" href="<?= htmlspecialchars(url('recipes')) ?>">Bekijk alles</a>
    </div>
    <div class="categories-grid">
        <?php foreach ($categories as $category): ?>
            <div class="category-card">
                <div class="category-icon"><?= $category['icon'] ?></div>
                <span class="category-name"><?= htmlspecialchars($category['name']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (isDevelopment()): ?>
        <section class="status" aria-label="Systeemstatus">
            <h2>Status (development)</h2>
            <?php
            try {
                db();
                $dbOk = true;
            } catch (PDOException $e) {
                $dbOk = false;
                $dbError = $e->getMessage();
            }
            ?>
            <?php if ($dbOk): ?>
                <p class="status__ok">✔ Databaseverbinding werkt.</p>
            <?php else: ?>
                <p class="status__error">✘ Databaseverbinding mislukt:</p>
                <pre><?= htmlspecialchars($dbError ?? '') ?></pre>
            <?php endif; ?>
        </section>
    <?php endif; ?>

</div>

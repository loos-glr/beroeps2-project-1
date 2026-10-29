<?php

/**
 * Homepagina (landingspagina) — issue #11.
 *
 * Laadt de categorieën en de uitgelichte recepten uit de database
 * (acceptatiecriterium: "Categorieën en uitgelichte recepten worden uit de
 * database geladen") en toont:
 *   - hero met pakkende slogan + CTA "Kook vandaag nog!";
 *   - zoekbalk (op ingrediënt / type gerecht) -> overzichtspagina;
 *   - categorie-tegels (de zes gerechttypes)  -> overzichtspagina;
 *   - uitgelichte / populaire recepten        -> overzichtspagina.
 *
 * De pagina blijft bruikbaar als de database onbereikbaar is: de secties
 * met data worden dan overgeslagen en we tonen een nette melding.
 */

$categories      = [];
$featuredRecipes = [];
$dbError         = null;

try {
    $pdo = db();

    // De zes gerechttypes voor de categorie-tegels.
    $categories = $pdo->query(
        'SELECT id, name, slug FROM categories ORDER BY id'
    )->fetchAll();

    // Uitgelichte recepten: de nieuwste recepten uit de database.
    // Let op: er is nog geen beoordelings-/likes-tabel, dus "uitgelicht"
    // betekent hier "meest recent toegevoegd". Zodra er wél een
    // populariteitsmaat is, kan alleen de ORDER BY aangepast worden.
    $featuredRecipes = $pdo->query(
        'SELECT r.id, r.title, r.image_url, r.prep_time, r.difficulty,
                c.name AS category_name, c.slug AS category_slug
         FROM recipes r
         INNER JOIN categories c ON c.id = r.category_id
         ORDER BY r.created_at DESC
         LIMIT 6'
    )->fetchAll();
} catch (PDOException $e) {
    // Database onbereikbaar: pagina blijft werken, alleen zonder data.
    $dbError = $e->getMessage();
}

/**
 * Emoji per categorie-slug.
 *
 * De style guide (docs/04-prototype.md §2) schrijft "iconen/emoji per
 * categorie" voor, maar de datadictionary heeft geen icoon-kolom. Daarom
 * mappen we de emoji hier op de slug; onbekende slugs krijgen een bord.
 *
 * @var array<string, string>
 */
$categoryIcons = [
    'ontbijt'      => '🥞',
    'lunch'        => '🥪',
    'diner'        => '🍛',
    'voorgerecht'  => '🥗',
    'hoofdgerecht' => '🍝',
    'nagerecht'    => '🍨',
];
?>
<div class="container">

    <!-- Hero: pakkende slogan + CTA "Kook vandaag nog!" -->
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

    <!-- Zoekbalk: zoekt op ingrediënt of type gerecht en stuurt de
         bezoeker naar de overzichtspagina (/recipes?q=...). -->
    <form class="search-bar-wrap" action="<?= htmlspecialchars(url('recipes')) ?>" method="get" role="search">
        <label class="visually-hidden" for="home-search">Zoek een recept</label>
        <input
            id="home-search"
            class="search-input"
            type="search"
            name="q"
            placeholder="Zoek op ingrediënt (bijv. eieren, pasta, rijst)..."
            autocomplete="off"
        >
        <button class="search-submit" type="submit" aria-label="Zoeken">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </form>

    <!-- Categorie-tegels (de zes gerechttypes, uit de database) -->
    <?php if ($categories !== []): ?>
        <div class="section-header">
            <h2 class="section-title">Kies een Categorie 🍽️</h2>
            <a class="section-link" href="<?= htmlspecialchars(url('recipes')) ?>">Bekijk alles</a>
        </div>
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
                <?php
                // Klikken op een categorie leidt naar de overzichtspagina
                // met de categorie als filter: /recipes?categorie=ontbijt
                $categoryUrl = url('recipes') . '?' . http_build_query(['categorie' => $category['slug']]);
                ?>
                <a class="category-card" href="<?= htmlspecialchars($categoryUrl) ?>">
                    <span class="category-icon" aria-hidden="true"><?= $categoryIcons[$category['slug']] ?? '🍽️' ?></span>
                    <span class="category-name"><?= htmlspecialchars((string) $category['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Uitgelichte recepten (uit de database) -->
    <?php if ($featuredRecipes !== []): ?>
        <div class="section-header">
            <h2 class="section-title">Uitgelicht 🔥</h2>
            <a class="section-link" href="<?= htmlspecialchars(url('recipes')) ?>">Bekijk alles</a>
        </div>
        <div class="featured-scroller">
            <?php foreach ($featuredRecipes as $recipe): ?>
                <?php
                // Kaart linkt naar de detailpagina van dit recept (issue #13).
                $linkUrl = url('recipes/' . $recipe['id']);
                $showDescription = false;
                require __DIR__ . '/partials/recipe-card.php';
                ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($dbError !== null): ?>
        <p class="notice">Recepten kunnen nu niet geladen worden. Probeer het later opnieuw.</p>
    <?php endif; ?>

    <?php if (isDevelopment()): ?>
        <section class="status" aria-label="Systeemstatus">
            <h2>Status (development)</h2>
            <?php if ($dbError === null): ?>
                <p class="status__ok">✔ Databaseverbinding werkt.</p>
                <p class="status__ok">
                    ✔ <?= count($categories) ?> categorieën en
                    <?= count($featuredRecipes) ?> uitgelichte recepten uit de database geladen.
                </p>
            <?php else: ?>
                <p class="status__error">✘ Databaseverbinding mislukt:</p>
                <pre><?= htmlspecialchars($dbError) ?></pre>
            <?php endif; ?>
        </section>
    <?php endif; ?>

</div>


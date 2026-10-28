<?php

/**
 * Receptenoverzicht — basisversie (leveringsglue voor issue #11).
 *
 * De homepagina linkt hier naartoe met:
 *   - ?categorie=<slug>  (klik op een categorie-tegel)
 *   - ?q=<zoekterm>      (zoekbalk: ingrediënt of type gerecht)
 *
 * Deze pagina leest die parameters uit en toont de bijbehorende recepten.
 * De volledige overzichtspagina (filterbalk op tijd/moeilijkheid, sorteren)
 * is issue #12; de uitgebreide zoekfunctie is issue #14. Alles wat hier
 * gebeurt gebruikt prepared statements (PDO), dus geen SQL-injectie.
 */

$searchQuery  = trim((string) ($_GET['q'] ?? ''));
$categorySlug = trim((string) ($_GET['categorie'] ?? ''));

$recipes      = [];
$categoryName = null;
$dbError      = null;

try {
    $pdo = db();

    // Naam van de gekozen categorie ophalen (voor de titel van de pagina).
    if ($categorySlug !== '') {
        $stmt = $pdo->prepare('SELECT name FROM categories WHERE slug = :slug');
        $stmt->execute([':slug' => $categorySlug]);
        $categoryName = $stmt->fetchColumn() ?: null;
    }

    // Basissquery; bij een zoekterm joinen we ook de ingrediënten erbij.
    if ($searchQuery !== '') {
        $sql = 'SELECT DISTINCT r.id, r.title, r.description, r.image_url,
                       r.prep_time, r.difficulty,
                       c.name AS category_name, c.slug AS category_slug
                FROM recipes r
                INNER JOIN categories c ON c.id = r.category_id
                LEFT JOIN recipe_ingredients ri ON ri.recipe_id = r.id
                LEFT JOIN ingredients i ON i.id = ri.ingredient_id';
    } else {
        $sql = 'SELECT r.id, r.title, r.description, r.image_url,
                       r.prep_time, r.difficulty,
                       c.name AS category_name, c.slug AS category_slug
                FROM recipes r
                INNER JOIN categories c ON c.id = r.category_id';
    }

    $conditions = [];
    $params     = [];

    if ($searchQuery !== '') {
        // Zoeken op (een deel van) de titel óf op een ingrediënt.
        $conditions[]           = '(r.title LIKE :qTitle OR i.name LIKE :qIngredient)';
        $params[':qTitle']      = '%' . $searchQuery . '%';
        $params[':qIngredient'] = '%' . $searchQuery . '%';
    }

    if ($categorySlug !== '') {
        $conditions[]    = 'c.slug = :slug';
        $params[':slug'] = $categorySlug;
    }

    if ($conditions !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY r.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll();
} catch (PDOException $e) {
    $dbError = $e->getMessage();
}

// Paginatitel bepalen op basis van de actieve filters.
if ($searchQuery !== '') {
    $pageHeading = 'Zoeken: "' . $searchQuery . '"';
} elseif ($categoryName !== null) {
    $pageHeading = $categoryName;
} elseif ($categorySlug !== '') {
    $pageHeading = 'Onbekende categorie';
} else {
    $pageHeading = 'Alle recepten';
}

$isFiltered = $searchQuery !== '' || $categorySlug !== '';
?>
<div class="container">

    <div class="page-head">
        <h1 class="page-title"><?= htmlspecialchars($pageHeading) ?></h1>
        <p class="page-subtitle">
            <?php if ($dbError !== null): ?>
                Recepten kunnen nu niet geladen worden.
            <?php elseif ($recipes === []): ?>
                Geen recepten gevonden.
            <?php else: ?>
                <?= count($recipes) ?> <?= count($recipes) === 1 ? 'recept' : 'recepten' ?> gevonden.
            <?php endif; ?>
        </p>
    </div>

    <!-- Zoekbalk (behoudt de actieve categoriefilter) -->
    <form class="search-bar-wrap" action="<?= htmlspecialchars(url('recipes')) ?>" method="get" role="search">
        <label class="visually-hidden" for="recipes-search">Zoek een recept</label>
        <?php if ($categorySlug !== ''): ?>
            <input type="hidden" name="categorie" value="<?= htmlspecialchars($categorySlug) ?>">
        <?php endif; ?>
        <input
            id="recipes-search"
            class="search-input"
            type="search"
            name="q"
            value="<?= htmlspecialchars($searchQuery) ?>"
            placeholder="Zoek op ingrediënt of gerecht..."
            autocomplete="off"
        >
        <button class="search-submit" type="submit" aria-label="Zoeken">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </form>

    <?php if ($isFiltered): ?>
        <a class="filter-chip" href="<?= htmlspecialchars(url('recipes')) ?>">✕ Wis filters</a>
    <?php endif; ?>

    <?php if ($recipes !== []): ?>
        <div class="recipe-grid">
            <?php foreach ($recipes as $recipe): ?>
                <?php
                // TODO(#13): detailpagina. Tot die bestaat linkt de kaart
                // naar het overzicht van de eigen categorie.
                $linkUrl = url('recipes') . '?' . http_build_query(['categorie' => $recipe['category_slug']]);
                $showDescription = true;
                require __DIR__ . '/partials/recipe-card.php';
                ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="page-card">
            <h2>Niets gevonden 🍳</h2>
            <p>
                <?php if ($dbError !== null): ?>
                    De recepten konden niet uit de database geladen worden. Probeer het later opnieuw.
                <?php else: ?>
                    Probeer een ander ingrediënt of kies een categorie op de homepagina.
                <?php endif; ?>
            </p>
            <a class="btn btn-primary" href="<?= htmlspecialchars(url('/')) ?>">Terug naar home</a>
        </div>
    <?php endif; ?>

</div>

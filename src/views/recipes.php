<?php

/**
 * Overzichtspagina per categorie — issue #12.
 *
 * De homepagina linkt hier naartoe met:
 *   - ?categorie=<slug>  (klik op een categorie-tegel)
 *   - ?q=<zoekterm>      (zoekbalk: ingrediënt of type gerecht)
 *
 * Daarnaast biedt deze pagina de filterbalk uit de wireframe
 * (docs/04-prototype.md §1): filteren op bereidingstijd en moeilijkheid.
 * De filters worden server-side toegepast met prepared statements (PDO),
 * dus geen SQL-injectie. De uitgebreide zoekfunctie is issue #14. Klikken
 * op een receptkaart opent de detailpagina (issue #13).
 */

$searchQuery      = trim((string) ($_GET['q'] ?? ''));
$categorySlug     = trim((string) ($_GET['categorie'] ?? ''));
$timeFilter       = trim((string) ($_GET['tijd'] ?? ''));
$difficultyFilter = trim((string) ($_GET['moeilijkheid'] ?? ''));

$recipes      = [];
$categoryName = null;
$dbError      = null;

/**
 * Filteropties voor bereidingstijd (label op de filterchip per ?tijd=-waarde).
 * Alleen deze sleutels worden geaccepteerd, zodat er nooit onbekende input
 * in de query terechtkomt.
 *
 * @var array<string, string>
 */
$timeRanges = [
    '15' => 'Tot 15 min',
    '30' => '15–30 min',
    '60' => '30–60 min',
    '61' => '60+ min',
];

/**
 * Hardcoded SQL-fragmenten per tijdsklasse (sleutels == $timeRanges).
 *
 * @var array<string, string>
 */
$timeConditions = [
    '15' => 'r.prep_time <= 15',
    '30' => 'r.prep_time > 15 AND r.prep_time <= 30',
    '60' => 'r.prep_time > 30 AND r.prep_time <= 60',
    '61' => 'r.prep_time > 60',
];

/**
 * Moeilijkheidsgraden voor de filterbalk (de ENUM-waarden uit het schema).
 *
 * @var array<string, string>
 */
$difficultyOptions = [
    'makkelijk' => 'Makkelijk',
    'gemiddeld' => 'Gemiddeld',
    'moeilijk'  => 'Moeilijk',
];

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

    // Filter op bereidingstijd (whitelist hierboven, dus altijd veilig).
    if (isset($timeConditions[$timeFilter])) {
        $conditions[] = $timeConditions[$timeFilter];
    }

    // Filter op moeilijkheid (alleen ENUM-waarden uit de whitelist).
    if (isset($difficultyOptions[$difficultyFilter])) {
        $conditions[]          = 'r.difficulty = :difficulty';
        $params[':difficulty'] = $difficultyFilter;
    }

    if ($conditions !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY r.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll();

    // Ingrediënten van de gevonden recepten in één query ophalen (geen
    // N+1), zodat de receptkaarten kunnen tonen wat erin zit.
    if ($recipes !== []) {
        $ids          = array_map(static fn (array $recipe): int => (int) $recipe['id'], $recipes);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo->prepare(
            'SELECT ri.recipe_id, i.name
             FROM recipe_ingredients ri
             INNER JOIN ingredients i ON i.id = ri.ingredient_id
             WHERE ri.recipe_id IN (' . $placeholders . ')
             ORDER BY ri.recipe_id, i.name'
        );
        $stmt->execute($ids);

        $ingredientsByRecipe = [];

        foreach ($stmt->fetchAll() as $row) {
            $ingredientsByRecipe[(int) $row['recipe_id']][] = (string) $row['name'];
        }

        foreach ($recipes as &$recipe) {
            $recipe['ingredients'] = $ingredientsByRecipe[(int) $recipe['id']] ?? [];
        }

        unset($recipe);
    }
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

$isFiltered = $searchQuery !== '' || $categorySlug !== ''
    || $timeFilter !== '' || $difficultyFilter !== '';

// Parameters die bij elke filterkeuze behouden moeten blijven, zodat de
// filterchips met elkaar én met categorie/zoekterm te combineren zijn.
$baseParams = [];

foreach ([
    'categorie'    => $categorySlug,
    'q'            => $searchQuery,
    'tijd'         => $timeFilter,
    'moeilijkheid' => $difficultyFilter,
] as $param => $value) {
    if ($value !== '') {
        $baseParams[$param] = $value;
    }
}

/**
 * URL voor een filterchip: zet de waarde aan, of (bij klik op de al
 * actieve chip) juist uit. De overige actieve filters blijven behouden.
 */
$filterUrl = static function (string $param, string $value, string $current) use ($baseParams): string {
    $params = $baseParams;

    if ($current === $value) {
        unset($params[$param]);
    } else {
        $params[$param] = $value;
    }

    return url('recipes') . '?' . http_build_query($params);
};
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

    <!-- Filterbalk: bereidingstijd + moeilijkheid (issue #12) -->
    <div class="filters-bar" aria-label="Filters">
        <div class="filter-group">
            <span class="filter-group__label">⏱ Tijd</span>
            <?php foreach ($timeRanges as $value => $label): ?>
                <a
                    class="filter-chip-btn<?= $timeFilter === $value ? ' is-active' : '' ?>"
                    href="<?= htmlspecialchars($filterUrl('tijd', $value, $timeFilter)) ?>"
                    <?= $timeFilter === $value ? 'aria-current="true"' : '' ?>
                ><?= htmlspecialchars($label) ?></a>
            <?php endforeach; ?>
        </div>

        <div class="filter-group">
            <span class="filter-group__label">🔥 Moeilijkheid</span>
            <?php foreach ($difficultyOptions as $value => $label): ?>
                <a
                    class="filter-chip-btn<?= $difficultyFilter === $value ? ' is-active' : '' ?>"
                    href="<?= htmlspecialchars($filterUrl('moeilijkheid', $value, $difficultyFilter)) ?>"
                    <?= $difficultyFilter === $value ? 'aria-current="true"' : '' ?>
                ><?= htmlspecialchars($label) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($isFiltered): ?>
        <a class="filter-chip" href="<?= htmlspecialchars(url('recipes')) ?>">✕ Wis filters</a>
    <?php endif; ?>

    <?php if ($recipes !== []): ?>
        <div class="recipe-grid">
            <?php foreach ($recipes as $recipe): ?>
                <?php
                // Kaart linkt naar de detailpagina van dit recept (issue #13).
                $linkUrl = url('recipes/' . $recipe['id']);
                $showDescription = true;
                $showIngredients = true;
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
                    Probeer een andere zoekterm, een andere categorie of wis de filters.
                <?php endif; ?>
            </p>
            <a class="btn btn-primary" href="<?= htmlspecialchars(url('/')) ?>">Terug naar home</a>
        </div>
    <?php endif; ?>

</div>

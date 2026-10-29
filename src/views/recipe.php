<?php

/**
 * Detailpagina van een recept — issue #13.
 *
 * Route: /recipes/<id> (zie httpdocs/index.php). Alle data komt uit de
 * database: het recept zelf, de categorie, de auteur (users) en de
 * ingrediënten met hoeveelheden uit de koppeltabel recipe_ingredients.
 *
 * Wireframe (docs/04-prototype.md §1): grote gerechtfoto + kerninfo
 * (tijd, moeilijkheid, categorie), ingrediëntenlijst, stap-voor-stap
 * bereiding en deel-/bewaarknoppen.
 *
 * @var int|null $recipeId
 */

$recipeId = (int) ($recipeId ?? 0);

$recipe      = null;
$ingredients = [];
$steps       = [];
$dbError     = null;

/**
 * Moeilijkheid -> leesbaar label (komt overeen met de ENUM in de database).
 *
 * @var array<string, string>
 */
$difficultyLabels = [
    'makkelijk' => 'Makkelijk',
    'gemiddeld' => 'Gemiddeld',
    'moeilijk'  => 'Moeilijk',
];

/**
 * Emoji per categorie-slug (zie ook src/views/home.php).
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

try {
    $pdo = db();

    $stmt = $pdo->prepare(
        'SELECT r.id, r.title, r.description, r.steps, r.image_url,
                r.prep_time, r.difficulty,
                c.name AS category_name, c.slug AS category_slug,
                u.username AS author_name
         FROM recipes r
         INNER JOIN categories c ON c.id = r.category_id
         INNER JOIN users u ON u.id = r.user_id
         WHERE r.id = :id'
    );
    $stmt->execute([':id' => $recipeId]);
    $recipe = $stmt->fetch() ?: null;

    if ($recipe !== null) {
        // Ingrediënten met hoeveelheid (n:n-koppeltabel).
        $stmt = $pdo->prepare(
            'SELECT i.name, ri.amount
             FROM recipe_ingredients ri
             INNER JOIN ingredients i ON i.id = ri.ingredient_id
             WHERE ri.recipe_id = :id
             ORDER BY i.name'
        );
        $stmt->execute([':id' => $recipeId]);
        $ingredients = $stmt->fetchAll();

        // Bereidingswijze: één stap per regel opgeslagen in `recipes.steps`.
        $stepLines = preg_split('/\R/', (string) ($recipe['steps'] ?? '')) ?: [];
        $steps     = array_values(array_filter(
            array_map('trim', $stepLines),
            static fn (string $step): bool => $step !== ''
        ));
    }
} catch (PDOException $e) {
    $dbError = $e->getMessage();
}

// Absolute URL van deze pagina, nodig voor de deel-knoppen.
$scheme = 'http';

if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $scheme = explode(',', (string) $_SERVER['HTTP_X_FORWARDED_PROTO'])[0];
} elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $scheme = 'https';
}

$shareUrl  = $scheme . '://' . (string) ($_SERVER['HTTP_HOST'] ?? 'localhost') . url('recipes/' . $recipeId);
$shareText = $recipe !== null ? (string) $recipe['title'] . ' — Smaakmaatje' : 'Smaakmaatje';

$difficulty = $recipe !== null ? (string) $recipe['difficulty'] : '';
?>
<div class="container">

    <?php if ($dbError !== null): ?>
        <div class="page-card">
            <h1>Even geen verbinding 🍳</h1>
            <p>Dit recept kan nu niet uit de database geladen worden. Probeer het later opnieuw.</p>
            <a class="btn btn-primary" href="<?= htmlspecialchars(url('recipes')) ?>">Terug naar recepten</a>
        </div>
    <?php elseif ($recipe === null): ?>
        <div class="page-card">
            <h1>Recept niet gevonden 🔍</h1>
            <p>Dit recept bestaat niet (meer). Bekijk onze andere recepten.</p>
            <a class="btn btn-primary" href="<?= htmlspecialchars(url('recipes')) ?>">Terug naar recepten</a>
        </div>
    <?php else: ?>
        <a class="back-link" href="<?= htmlspecialchars(url('recipes')) ?>">
            <span aria-hidden="true">←</span> Terug naar recepten
        </a>

        <article class="recipe-detail">
            <!-- Grote gerechtfoto (met emoji-fallback als de foto niet laadt) -->
            <div class="recipe-detail__hero">
                <span class="recipe-detail__fallback" aria-hidden="true"><?= $categoryIcons[$recipe['category_slug']] ?? '🍽️' ?></span>
                <?php if (!empty($recipe['image_url'])): ?>
                    <img
                        class="recipe-detail__image"
                        src="<?= htmlspecialchars((string) $recipe['image_url']) ?>"
                        alt="<?= htmlspecialchars((string) $recipe['title']) ?>"
                        onerror="this.remove()"
                    >
                <?php endif; ?>
            </div>

            <div class="recipe-detail__head">
                <span class="recipe-detail__category"><?= htmlspecialchars((string) $recipe['category_name']) ?></span>
                <h1 class="recipe-detail__title"><?= htmlspecialchars((string) $recipe['title']) ?></h1>

                <?php if (!empty($recipe['description'])): ?>
                    <p class="recipe-detail__description"><?= htmlspecialchars((string) $recipe['description']) ?></p>
                <?php endif; ?>

                <!-- Kerninfo: bereidingstijd, moeilijkheid en categorie -->
                <div class="recipe-meta">
                    <span class="meta-chip">
                        <span aria-hidden="true">⏱</span> <?= (int) $recipe['prep_time'] ?> min
                    </span>
                    <span class="meta-chip meta-chip--<?= htmlspecialchars($difficulty) ?>">
                        <span aria-hidden="true">🔥</span>
                        <?= htmlspecialchars($difficultyLabels[$difficulty] ?? $difficulty) ?>
                    </span>
                    <a
                        class="meta-chip"
                        href="<?= htmlspecialchars(url('recipes') . '?' . http_build_query(['categorie' => $recipe['category_slug']])) ?>"
                    >
                        <span aria-hidden="true"><?= $categoryIcons[$recipe['category_slug']] ?? '🍽️' ?></span>
                        <?= htmlspecialchars((string) $recipe['category_name']) ?>
                    </a>
                </div>

                <!-- Auteur (uit de database) -->
                <div class="recipe-author">
                    <span class="recipe-author__avatar" aria-hidden="true">
                        <?= htmlspecialchars(strtoupper(substr((string) $recipe['author_name'], 0, 1))) ?>
                    </span>
                    <span>Recept van <strong><?= htmlspecialchars((string) $recipe['author_name']) ?></strong></span>
                </div>

                <!-- Bewaren + delen -->
                <div class="recipe-actions">
                    <button
                        class="btn btn-primary btn-save"
                        type="button"
                        data-save-recipe
                        data-recipe-id="<?= (int) $recipe['id'] ?>"
                        data-recipe-title="<?= htmlspecialchars((string) $recipe['title']) ?>"
                        aria-pressed="false"
                    >
                        <span aria-hidden="true">♡</span>
                        <span data-save-label>Bewaar</span>
                    </button>

                    <div class="share-buttons" aria-label="Recept delen">
                        <button
                            class="share-btn share-btn--native"
                            type="button"
                            data-share-native
                            data-share-url="<?= htmlspecialchars($shareUrl) ?>"
                            data-share-title="<?= htmlspecialchars($shareText) ?>"
                            hidden
                        >📤 Deel</button>
                        <a
                            class="share-btn share-btn--whatsapp"
                            href="https://wa.me/?text=<?= rawurlencode($shareText . ' ' . $shareUrl) ?>"
                            target="_blank"
                            rel="noopener"
                        >WhatsApp</a>
                        <a
                            class="share-btn share-btn--facebook"
                            href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode($shareUrl) ?>"
                            target="_blank"
                            rel="noopener"
                        >Facebook</a>
                        <a
                            class="share-btn share-btn--x"
                            href="https://twitter.com/intent/tweet?url=<?= rawurlencode($shareUrl) ?>&amp;text=<?= rawurlencode($shareText) ?>"
                            target="_blank"
                            rel="noopener"
                        >X</a>
                        <button
                            class="share-btn share-btn--copy"
                            type="button"
                            data-share-copy
                            data-share-url="<?= htmlspecialchars($shareUrl) ?>"
                        >🔗 Kopieer link</button>
                    </div>
                </div>
            </div>

            <div class="recipe-detail__body">
                <!-- Ingrediëntenlijst met hoeveelheden -->
                <section class="recipe-section" aria-labelledby="recipe-ingredients">
                    <h2 id="recipe-ingredients">🧺 Ingrediënten</h2>
                    <?php if ($ingredients !== []): ?>
                        <ul class="ingredients-list" role="list">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <li class="ingredient-row">
                                    <span class="ingredient-row__name"><?= htmlspecialchars((string) $ingredient['name']) ?></span>
                                    <span class="ingredient-row__amount"><?= htmlspecialchars((string) $ingredient['amount']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="recipe-section__empty">Voor dit recept zijn nog geen ingrediënten toegevoegd.</p>
                    <?php endif; ?>
                </section>

                <!-- Stap-voor-stap bereidingswijze -->
                <section class="recipe-section" aria-labelledby="recipe-steps">
                    <h2 id="recipe-steps">👩‍🍳 Bereidingswijze</h2>
                    <?php if ($steps !== []): ?>
                        <ol class="steps-list" role="list">
                            <?php foreach ($steps as $index => $step): ?>
                                <li class="step">
                                    <span class="step__num" aria-hidden="true"><?= $index + 1 ?></span>
                                    <span class="step__text">
                                        <?php /* Eventueel aanwezige nummering ("1. ") weghalen. */ ?>
                                        <?= htmlspecialchars((string) preg_replace('/^\d+[.)]\s*/', '', $step)) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <p class="recipe-section__empty">De bereidingswijze is nog niet toegevoegd.</p>
                    <?php endif; ?>
                </section>
            </div>
        </article>
    <?php endif; ?>

</div>


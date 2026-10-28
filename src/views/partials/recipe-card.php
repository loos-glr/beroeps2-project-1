<?php

/**
 * Herbruikbare receptkaart — gebruikt op de homepagina én de overzichtspagina.
 *
 * Vóór de include moeten deze variabelen zijn gezet:
 *
 * @var array<string, mixed> $recipe          Recept uit de database met de
 *                                            kolommen id, title, image_url,
 *                                            prep_time, difficulty,
 *                                            category_name en category_slug.
 * @var string               $linkUrl         Waar de kaart naartoe linkt.
 * @var bool                 $showDescription Omschrijving tonen? (optioneel)
 */

$showDescription = $showDescription ?? false;

// Moeilijkheid -> leesbaar label (komt overeen met de ENUM in de database).
$difficultyLabels = [
    'makkelijk' => 'Makkelijk',
    'gemiddeld' => 'Gemiddeld',
    'moeilijk'  => 'Moeilijk',
];

$difficulty = (string) $recipe['difficulty'];
?>
<a class="recipe-card" href="<?= htmlspecialchars($linkUrl) ?>">
    <div class="recipe-card__media">
        <?php /* Fallback-emoji: blijft zichtbaar als de foto niet laadt. */ ?>
        <span class="recipe-card__fallback" aria-hidden="true">🍽️</span>
        <?php if (!empty($recipe['image_url'])): ?>
            <img
                class="recipe-card__image"
                src="<?= htmlspecialchars((string) $recipe['image_url']) ?>"
                alt="<?= htmlspecialchars((string) $recipe['title']) ?>"
                loading="lazy"
                onerror="this.remove()"
            >
        <?php endif; ?>
        <span class="recipe-card__badge recipe-card__badge--<?= htmlspecialchars($difficulty) ?>">
            <?= htmlspecialchars($difficultyLabels[$difficulty] ?? $difficulty) ?>
        </span>
    </div>
    <div class="recipe-card__body">
        <span class="recipe-card__category"><?= htmlspecialchars((string) $recipe['category_name']) ?></span>
        <h3 class="recipe-card__title"><?= htmlspecialchars((string) $recipe['title']) ?></h3>
        <?php if ($showDescription && !empty($recipe['description'])): ?>
            <p class="recipe-card__description"><?= htmlspecialchars((string) $recipe['description']) ?></p>
        <?php endif; ?>
        <span class="recipe-card__time">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"></circle>
                <polyline points="12 7 12 12 15 14"></polyline>
            </svg>
            <?= (int) $recipe['prep_time'] ?> min
        </span>
    </div>
</a>

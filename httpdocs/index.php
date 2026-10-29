<?php

declare(strict_types=1);

/**
 * Front controller / entrypoint.
 *
 * Alle verzoeken komen hier binnen (via router.php in ontwikkeling of via
 * .htaccess op Apache-hosting) en worden naar een pagina gerouteerd.
 */

require __DIR__ . '/../src/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = '/' . trim($path, '/');

$routes = [
    '/'        => 'home',
    '/recipes' => 'recipes',
];

$page     = $routes[$path] ?? null;
$recipeId = null;

// Detailpagina van een recept (issue #13): /recipes/<id>, alleen cijfers.
if ($page === null && preg_match('#^/recipes/(\d+)$#', $path, $matches) === 1) {
    $page     = 'recipe';
    $recipeId = (int) $matches[1];
}

$page ??= '404';

$pageTitles = [
    'home'    => 'Stop de Ontkoking',
    'recipes' => 'Recepten',
    'recipe'  => 'Recept',
    '404'     => 'Pagina niet gevonden',
];

if ($page === '404') {
    http_response_code(404);
}

$pageTitle = $pageTitles[$page] ?? 'Stop de Ontkoking';

// Op de detailpagina zetten we de titel van het recept in de <title>-tag.
// De view haalt alle overige gegevens zelf op; hier alleen de titel.
if ($page === 'recipe' && $recipeId !== null) {
    try {
        $stmt = db()->prepare('SELECT title FROM recipes WHERE id = :id');
        $stmt->execute([':id' => $recipeId]);
        $title = $stmt->fetchColumn();

        if ($title === false) {
            // Recept bestaat niet: 404-status (moet vóór de output gebeuren).
            http_response_code(404);
        } else {
            $pageTitle = (string) $title;
        }
    } catch (PDOException $e) {
        // Database onbereikbaar: de view toont straks een nette melding.
    }
}

$view = __DIR__ . '/../src/views/' . $page . '.php';

require __DIR__ . '/../src/views/header.php';
require $view;
require __DIR__ . '/../src/views/footer.php';

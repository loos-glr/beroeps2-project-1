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

$page = $routes[$path] ?? '404';

$pageTitles = [
    'home'    => 'Stop de Ontkoking',
    'recipes' => 'Recepten',
    '404'     => 'Pagina niet gevonden',
];

if ($page === '404') {
    http_response_code(404);
}

$pageTitle = $pageTitles[$page] ?? 'Stop de Ontkoking';
$view = __DIR__ . '/../src/views/' . $page . '.php';

require __DIR__ . '/../src/views/header.php';
require $view;
require __DIR__ . '/../src/views/footer.php';

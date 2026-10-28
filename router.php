<?php

declare(strict_types=1);

/**
 * Router-script voor de ingebouwde PHP-server (lokale ontwikkeling).
 *
 * Start met:  php -S localhost:8000 -t public router.php
 *
 * Bestaande bestanden uit httpdocs/ worden direct geserveerd; alle overige
 * verzoeken gaan naar de front controller (httpdocs/index.php).
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$file = __DIR__ . '/public' . $path;

if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/httpdocs/index.php';

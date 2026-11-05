<?php

declare(strict_types=1);

/**
 * Bootstrap: laadt de configuratie en stelt kleine helpers beschikbaar.
 *
 * Wordt als eerste geïmporteerd door public/index.php.
 */

const CONFIG_LOCAL   = __DIR__ . '/../config/config.local.php';
const CONFIG_EXAMPLE = __DIR__ . '/../config/config.example.php';

/**
 * Laadt de actieve configuratie (lokaal bestand indien aanwezig,
 * anders het voorbeeldbestand).
 *
 * @return array<string, mixed>
 */
function loadConfig(): array
{
    $path = is_file(CONFIG_LOCAL) ? CONFIG_LOCAL : CONFIG_EXAMPLE;

    return require $path;
}

/**
 * Leest een configuratiewaarde via dot-notatie, bv. config('db.host').
 *
 * @param string|null $key
 * @param mixed       $default
 *
 * @return mixed
 */
function config(?string $key = null, mixed $default = null): mixed
{
    static $config = null;

    if ($config === null) {
        $config = loadConfig();
    }

    if ($key === null) {
        return $config;
    }

    $value = $config;

    foreach (explode('.', $key) as $segment) {
        if (is_array($value) && array_key_exists($segment, $value)) {
            $value = $value[$segment];
        } else {
            return $default;
        }
    }

    return $value;
}

/**
 * Geeft aan of de applicatie in development-modus draait.
 */
function isDevelopment(): bool
{
    return config('app.env', 'production') === 'development';
}

/**
 * Geeft de gedeelde PDO-verbinding terug (lazy, singleton).
 *
 * @throws PDOException
 */
function db(): PDO
{
    return Database::connection();
}

/**
 * Geeft het basispad van de applicatie terug, zonder trailing slash
 * (bv. '/beroeps2/project-1', of '' wanneer de app in de root draait).
 *
 * Volgorde: 1) config 'app.basePath', 2) automatische detectie.
 *
 * Bij een interne Apache-rewrite van /beroeps2/project-1/route naar
 * public/index.php bevat SCRIPT_NAME '/beroeps2/project-1/public/index.php'
 * terwijl REQUEST_URI '/beroeps2/project-1/route' is. We leiden het basispad
 * daarom af uit SCRIPT_NAME door '/public/index.php' eraf te halen.
 * Bij de ingebouwde PHP-server (router.php) levert dit '' op.
 */
function basePath(): string
{
    static $base = null;

    if ($base !== null) {
        return $base;
    }

    $configured = config('app.basePath');

    if (is_string($configured)) {
        return $base = rtrim('/' . trim($configured, '/'), '/');
    }

    $script = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
    $suffix = '/public/index.php';

    if ($script !== '' && str_ends_with($script, $suffix)) {
        return $base = substr($script, 0, -strlen($suffix));
    }

    return $base = '';
}

/**
 * Bepaalt het route-pad van het huidige verzoek, zonder basispad en
 * zonder eventueel '/public'-voorvoegsel, bv. '/recipes'.
 */
function requestPath(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = basePath();

    if ($base !== '' && ($path === $base || str_starts_with($path, $base . '/'))) {
        $path = substr($path, strlen($base));
    }

    if ($path === '/public' || str_starts_with($path, '/public/')) {
        $path = substr($path, strlen('/public'));
    }

    return '/' . trim($path, '/');
}

/**
 * Bouwt een URL voor een route/pagina, bv. url('recipes') => '/recipes'
 * (of '/beroeps2/project-1/recipes' op de server).
 */
function url(string $path = ''): string
{
    $prefix = config('app.baseUrl');
    $prefix = is_string($prefix) && $prefix !== '' ? $prefix : basePath();

    return rtrim($prefix, '/') . '/' . ltrim($path, '/');
}

/**
 * Bouwt een URL naar een statisch bestand in public/assets/, bv.
 * asset('css/app.css') => '/assets/css/app.css'.
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

// Sessie-cookie afgeschermd op het basispad, zodat sessies niet botsen met
// andere PHP-projecten op hetzelfde domein. De sessie wordt hier niet gestart;
// roep session_start() aan waar nodig.
if (session_status() === PHP_SESSION_NONE) {
    $sessionPath = config('session.path');
    session_name((string) config('session.name', 'smaakmaatje_sid'));
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => is_string($sessionPath) ? $sessionPath : (basePath() ?: '/'),
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// Eenvoudige autoloader voor klassen in src/ (geen Composer nodig).
spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . '/' . $class . '.php';

    if (is_file($file)) {
        require $file;
    }
});

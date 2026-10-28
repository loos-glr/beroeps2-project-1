<?php

declare(strict_types=1);

/**
 * Bootstrap: laadt de configuratie en stelt kleine helpers beschikbaar.
 *
 * Wordt als eerste geïmporteerd door httpdocs/index.php.
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
 * Bouwt een URL voor een route/pagina, bv. url('recipes') => '/recipes'.
 */
function url(string $path = ''): string
{
    return rtrim((string) config('app.baseUrl', ''), '/') . '/' . ltrim($path, '/');
}

/**
 * Bouwt een URL naar een statisch bestand in httpdocs/assets/, bv.
 * asset('css/app.css') => '/assets/css/app.css'.
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

// Eenvoudige autoloader voor klassen in src/ (geen Composer nodig).
spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . '/' . $class . '.php';

    if (is_file($file)) {
        require $file;
    }
});

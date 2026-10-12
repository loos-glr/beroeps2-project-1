<?php

declare(strict_types=1);

/**
 * Configuratie (voorbeeldbestand).
 *
 * Kopieer dit bestand naar `config/config.local.php` en vul daar de echte
 * databasegegevens in. `config.local.php` staat in `.gitignore` en wordt
 * dus nooit naar GitHub gecommit.
 *
 * @return array<string, mixed>
 */
return [
    'app' => [
        // 'development' toont o.a. de database-status op de homepagina.
        // Zet op 'production' zodra de site live gaat (FTP-hosting).
        'env'     => 'development',
        'baseUrl' => '',
    ],
    'db' => [
        'host'    => 'localhost',
        'port'    => 3306,
        'name'    => 'smaakmaatje',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
];

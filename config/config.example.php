<?php

declare(strict_types=1);

/**
 * Configuratie (voorbeeldbestand).
 *
 * Kopieer dit bestand naar `config/config.local.php` en vul daar de echte
 * databasegegevens in. `config.local.php` staat in `.gitignore` en wordt
 * dus nooit naar GitHub gecommit.
 *
 * De standaardwaarden hieronder werken zonder aanpassing in DDEV. Op Plesk
 * maak je zelf een database + gebruiker aan; zet die gegevens in
 * `config.local.php` (host blijft `localhost`).
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
        'user'    => 'student_user',
        'pass'    => 'student_password',
        'charset' => 'utf8mb4',
    ],
];

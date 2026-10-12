<?php

declare(strict_types=1);

/**
 * Database-verbinding via PDO (MySQL).
 *
 * Singleton-wrapper rond PDO met nette foutafhandeling. Er wordt pas
 * verbonden wanneer Database::connection() voor het eerst wordt aangeroepen.
 */
final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    /**
     * Geeft de gedeelde PDO-verbinding terug.
     *
     * @throws PDOException wanneer het verbinden mislukt.
     */
    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            (string) config('db.host', 'localhost'),
            (int) config('db.port', 3306),
            (string) config('db.name', ''),
            (string) config('db.charset', 'utf8mb4')
        );

        try {
            self::$connection = new PDO(
                $dsn,
                (string) config('db.user', ''),
                (string) config('db.pass', ''),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            // Log de technische oorzaak (zonder wachtwoord) en geef hem door.
            error_log('[Database] Verbinding mislukt: ' . $e->getMessage());

            throw $e;
        }

        return self::$connection;
    }
}

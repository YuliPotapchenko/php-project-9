<?php

declare(strict_types=1);

namespace App\Service;

use PDO;

class Connection
{
    private static ?self $conn = null;

    private function __construct()
    {
    }

    public function connect(): PDO
    {
        $databaseUrl = parse_ini_file('database.ini');

        $dsn = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $databaseUrl['host'],
            $databaseUrl['port'],
            $databaseUrl['database'],
            $databaseUrl['user'],
            $databaseUrl['pass']
        );

        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function get(): self
    {
        if (!self::$conn) {
            self::$conn = new self();
        }

        return self::$conn;
    }
}

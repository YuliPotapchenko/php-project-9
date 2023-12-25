<?php

declare(strict_types=1);

namespace App\Service;

use PDO;

class DbConnection
{
    private static ?self $conn = null;

    private function __construct()
    {
    }

    public function connect(): PDO
    {
        $databaseUrl = parse_url($_ENV['DATABASE_URL']);

        if (!$databaseUrl) {
            throw new \Exception("Error reading database url");
        }
        $host = $databaseUrl['host'] ?? '';
        $port = $databaseUrl['port'] ?? '';
        $name = $databaseUrl['path'] ? ltrim($databaseUrl['path'], '/') : '';
        $user = $databaseUrl['user'] ?? '';
        $pass = $databaseUrl['pass'] ?? '';

        if ($port) {
            $dsn = sprintf(
                "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
                $host,
                $port,
                $name,
                $user,
                $pass
            );
        } else {
            $dsn = sprintf(
                "pgsql:host=%s;dbname=%s;user=%s;password=%s",
                $host,
                $name,
                $user,
                $pass
            );
        }

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

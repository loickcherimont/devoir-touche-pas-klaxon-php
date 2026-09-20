<?php

namespace Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Database
 *
 * Builds the PDO connection once and shares it across the application.
 * Singleton: a single PDO object exists and is reused everywhere.
 */
final class Database
{
    private static ?Database $instance = null;

    private PDO $pdo;

    /**
     * Private constructor: "new Database()" is forbidden outside.
     * The connection is created here, once.
     */
    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            Config::get('DB_HOST', 'localhost'),
            Config::get('DB_PORT', '3306'),
            Config::get('DB_NAME', 'touche_pas_au_klaxon')
        );

        try {
            $this->pdo = new PDO(
                $dsn,
                Config::get('DB_USER', 'root'),
                Config::get('DB_PASS', '')
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed', 0, $exception);
        }
    }

    /**
     * Single entry point: always returns the same Database object.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * The PDO connection used by the models.
     */
    public function connection(): PDO
    {
        return $this->pdo;
    }
}
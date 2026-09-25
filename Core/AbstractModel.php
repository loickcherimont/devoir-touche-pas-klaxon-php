<?php

namespace Core;

use PDO;

/**
 * AbstractModel
 *
 * Base class for every model of the application.
 * It provides the PDO connection and a secure query helper,
 * so this code is never duplicated in the models (DRY).
 */
abstract class AbstractModel
{
    /**
     * The PDO connection is injected through the constructor:
     * each model must receive it (= a clearly declared dependency).
     */
    public function __construct(protected PDO $pdo) {}

    /**
     * Executes a prepared statement and returns every row.
     *
     * @param string                $stmt   The SQL query with placeholders (ex: :id)
     * @param array<string, mixed>  $params Values bound to the placeholders
     * @return array<array<string, mixed>> The rows as associative arrays
     */
    protected function findAll(string $stmt, array $params = []): array
    {
        $query = $this->pdo->prepare($stmt);
        $query->execute($params);
        return $query->fetchAll();
    }

    /**
     * Executes a prepared statement and returns the first matching row.
     *
     * @param string               $stmt   The SQL query with placeholders (ex: :id)
     * @param array<string, mixed> $params Values bound to the placeholders
     * @return array<string, mixed>|false The first row as an associative array, or false when no row matches
     */
    protected function findOne(string $stmt, array $params = []): array|false
    {
        $query = $this->pdo->prepare($stmt);
        $query->execute($params);

        return $query->fetch();
    }

    /**
     * Executes a prepared statement that does not return any row
     * (INSERT, UPDATE, DELETE).
     *
     * @param string               $stmt   The SQL query with placeholders (ex: :id)
     * @param array<string, mixed> $params Values bound to the placeholders
     */
    protected function save(string $stmt, array $params = []): void
    {
        $query = $this->pdo->prepare($stmt);
        $query->execute($params);
    }
}

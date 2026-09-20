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
}
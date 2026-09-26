<?php

namespace App\Model\User;

use Core\AbstractModel;
use PDO;

/**
 * UserModel
 *
 * Data access for users.
 */
class UserModel extends AbstractModel
{

    /**
     * SQL query to find a specific trip using its id.
     */
    private const SQL_FIND_USER_BY_EMAIL = <<<'SQL'
        SELECT * FROM users WHERE email = :email
        SQL;
    /**
     * Returns the first user matching the given email address.
     *
     * @param string $email The email address to look up
     * @return array<string, mixed>|false The matching user as an associative array, or false when no user matches
     */
    public function getUserByEmail(string $email): array|false
    {
        return $this->findOne(self::SQL_FIND_USER_BY_EMAIL, ['email' => $email]);
    }

    /**
     * Returns the first user matching the given id.
     *
     * @param int $id The id to look up
     * @return array<string, mixed>|false The matching user as an associative array, or false when no user matches
     */
    public function getUserById(int $id): array|false
    {
        return $this->findOne('SELECT * FROM users WHERE id = :id', ['id' => $id]);
    }
}

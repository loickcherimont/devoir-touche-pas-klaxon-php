<?php

namespace App\Model;

use Core\AbstractModel;
use PDO;

/**
 * UserModel
 *
 * Data access for users.
 */
class UserModel extends AbstractModel
{

    private const SQL = 'SELECT * FROM users WHERE email = :email';

    /**
     * Returns the first user matching the given email address.
     *
     * @param string $email The email address to look up
     * @return array<string, mixed>|false The matching user as an associative array, or false when no user matches
     */
    public function getUserByEmail(string $email): array|false
    {
        return $this->findOne(self::SQL, ['email' => $email]);
    }
}

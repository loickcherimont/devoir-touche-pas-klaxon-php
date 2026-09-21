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

    public function getUserByEmail(string $email)
    {
        return $this->findByEmail(self::SQL, ['email' => $email]);
    }
}

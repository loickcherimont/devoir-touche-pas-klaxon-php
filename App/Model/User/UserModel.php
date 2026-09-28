<?php

namespace App\Model\User;

use Core\AbstractModel;

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
     * SQL query to find a specific user using its id.
     */
    private const SQL_FIND_USER_BY_ID = <<<'SQL'
        SELECT * FROM users WHERE id = :id
        SQL;
    /**
     * SQL query listing the non-admin users for the read-only admin dashboard,
     * ordered by natural id so the rows follow the insertion order.
     */
    private const SQL_FIND_ALL_NON_ADMIN_USERS = <<<'SQL'
        SELECT id, nom, prenom, email, role
        FROM users
        WHERE role != :excluded_role
        ORDER BY id
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
        return $this->findOne(self::SQL_FIND_USER_BY_ID, ['id' => $id]);
    }

    /**
     * Returns every non-admin user, for the read-only admin listing.
     *
     * @return array<AdminUserDTO> The non-admin users as readonly DTOs, ready for the dashboard
     */
    public function getAllNonAdminUsers(): array
    {
        $rows = $this->findAll(self::SQL_FIND_ALL_NON_ADMIN_USERS, ['excluded_role' => UserRole::Admin->value]);

        return array_map(fn(array $row): AdminUserDTO => new AdminUserDTO(
            id: $row['id'],
            prenom: $row['prenom'],
            nom: $row['nom'],
            email: $row['email'],
            role: UserRole::tryFrom((string) $row['role'])
        ), $rows);
    }
}

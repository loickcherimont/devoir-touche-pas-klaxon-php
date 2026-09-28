<?php

namespace App\Model\Agency;

use Core\AbstractModel;

/**
 * AgencyModel
 *
 * Data access for agencies.
 */
class AgencyModel extends AbstractModel
{
    /**
     * SQL query listing the agencies for the read-only admin dashboard, ordered
     * by natural id so the rows follow the insertion order.
     */
    private const SQL_FIND_ALL_AGENCIES = <<<'SQL'
        SELECT id, nom
        FROM agencies
        ORDER BY id
        SQL;

    /**
     * Returns every agency from the database.
     *
     * @return array<array<string, mixed>> All agencies as associative arrays
     */
    public function getAllAgencies(): array
    {
        return $this->findAll('SELECT * FROM agencies');
    }

    /**
     * Returns every agency, mapped to immutable DTOs for the read-only admin
     * dashboard listing. Never used for any write operation.
     *
     * @return array<AdminAgencyDTO> All agencies as readonly DTOs
     */
    public function getAllAgenciesForAdmin(): array
    {
        $rows = $this->findAll(self::SQL_FIND_ALL_AGENCIES);

        return array_map(fn (array $row): AdminAgencyDTO => new AdminAgencyDTO(
            id: $row['id'],
            nom: $row['nom']
        ), $rows);
    }
}

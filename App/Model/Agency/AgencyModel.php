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
     * SQL query finding one agency by its name. The utf8mb4 collation makes
     * the comparison case-insensitive, so "Bordeaux" and "bordeaux" collide
     * naturally through this query.
     */
    private const SQL_FIND_AGENCY_BY_NOM = <<<'SQL'
        SELECT id, nom
        FROM agencies
        WHERE nom = :nom
        SQL;
    /**
     * SQL query inserting a new agency. The caller must ensure the name does
     * not already exist: the database has no UNIQUE constraint on agencies.nom.
     */
    private const SQL_INSERT_AGENCY = <<<'SQL'
        INSERT INTO agencies (nom)
        VALUES (:nom)
        SQL;

    /**
     * Returns every agency, mapped to immutable DTOs. Shared by the trip
     * creation and update forms and by the admin dashboard.
     *
     * @return array<AdminAgencyDTO> All agencies as readonly DTOs
     */
    public function getAllAgencies(): array
    {
        $rows = $this->findAll(self::SQL_FIND_ALL_AGENCIES);

        return array_map(fn (array $row): AdminAgencyDTO => new AdminAgencyDTO(
            id: $row['id'],
            nom: $row['nom']
        ), $rows);
    }

    /**
     * Returns the first agency matching the given name, or false when none
     * does. Used to reject duplicate names before the insert.
     *
     * @param string $nom The agency name to look up
     * @return array<string, mixed>|false The matching agency as an associative array, or false when no agency matches
     */
    public function getAgencyByNom(string $nom): array|false
    {
        return $this->findOne(self::SQL_FIND_AGENCY_BY_NOM, ['nom' => $nom]);
    }

    /**
     * Inserts a new agency.
     *
     * @param string $nom The name of the agency to create
     */
    public function saveAgency(string $nom): void
    {
        $this->save(self::SQL_INSERT_AGENCY, ['nom' => $nom]);
    }
}

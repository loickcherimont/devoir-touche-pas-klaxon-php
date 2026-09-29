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
     * SQL query listing every agency, shared by the admin dashboard and by the
     * trip creation and update forms, ordered by natural id so the rows follow
     * the insertion order.
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
     * SQL query inserting a new agency. The UNIQUE constraint on agencies.nom
     * backs the caller-side duplicate check: a concurrent insert that slips
     * through the lookup would fail here instead of duplicating the row.
     */
    private const SQL_INSERT_AGENCY = <<<'SQL'
        INSERT INTO agencies (nom)
        VALUES (:nom)
        SQL;
    /**
     * SQL query deleting one agency. The database forbids deleting an agency
     * still referenced by a trip (FOREIGN KEY, ON DELETE RESTRICT): the caller
     * catches the resulting integrity-constraint violation.
     */
    private const SQL_DELETE_AGENCY = <<<'SQL'
        DELETE FROM agencies
        WHERE id = :id
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
     * Returns the agency matching the given name, or null when none does. Used
     * to reject duplicate names before the insert, whatever the case variant:
     * the utf8mb4 collation makes the comparison case-insensitive naturally.
     *
     * @param string $nom The agency name to look up
     * @return AdminAgencyDTO|null The matching agency, or null when none matches
     */
    public function getAgencyByNom(string $nom): ?AdminAgencyDTO
    {
        $row = $this->findOne(self::SQL_FIND_AGENCY_BY_NOM, ['nom' => $nom]);

        return $row === false
            ? null
            : new AdminAgencyDTO(id: $row['id'], nom: $row['nom']);
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

    /**
     * Deletes the agency with the given id.
     *
     * The deletion is rejected by the foreign key whenever the agency is still
     * referenced by a trip; the caller catches the violation to display a
     * readable error instead of an unhandled exception.
     *
     * @param int $id Id of the agency to delete
     */
    public function deleteAgencyById(int $id): void
    {
        $this->save(self::SQL_DELETE_AGENCY, ['id' => $id]);
    }
}

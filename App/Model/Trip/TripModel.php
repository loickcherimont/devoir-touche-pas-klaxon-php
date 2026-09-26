<?php

namespace App\Model\Trip;

use Core\AbstractModel;

/**
 * TripModel
 *
 * Data access for trips.
 */
class TripModel extends AbstractModel
{
    /**
     * SQL query selecting upcoming trips with available seats,
     * joined with their departure and arrival agencies.
     */
    private const SQL_AFFICHAGE = <<<'SQL'
SELECT
    trips.id,
    a_depart.nom AS depart,
    DATE(trips.gdh_depart) AS date_depart,
    TIME(trips.gdh_depart) AS heure_depart,
    a_arrivee.nom AS destination,
    DATE(trips.gdh_arrivee) AS date_arrivee,
    TIME(trips.gdh_arrivee) AS heure_arrivee,
    trips.places_disponibles
FROM trips
JOIN agencies AS a_depart ON trips.agence_depart_id = a_depart.id
JOIN agencies AS a_arrivee ON trips.agence_arrivee_id = a_arrivee.id
WHERE trips.places_disponibles > 0
  AND trips.gdh_depart > NOW()
ORDER BY depart;
SQL;

    /**
     * SQL query inserting a new trip.
     */
    private const SQL_INSERT = <<<'SQL'
INSERT INTO trips(gdh_depart, gdh_arrivee, places_disponibles, agence_depart_id, agence_arrivee_id, users_id)
VALUES (:gdh_depart, :gdh_arrivee, :places_disponibles, :agence_depart_id, :agence_arrivee_id, :users_id)
SQL;

    /**
     * SQL query selecting the contact details displayed in the trip modal.
     */
    private const SQL_FIND_TRIP_BY_ID = <<<'SQL'
SELECT
    users.prenom,
    users.nom,
    users.telephone,
    users.email,
    trips.places_disponibles
FROM trips
JOIN users ON users.id = trips.users_id
WHERE trips.id = :id
SQL;

    /**
     * Returns the trips to display on the home page.
     *
     * @return array<array<string, mixed>> The trips as associative arrays
     */
    public function getTrips(): array
    {
        return $this->findAll(self::SQL_AFFICHAGE);
    }

    /**
     * Returns the details displayed in the modal, or null if the trip is absent.
     *
     * @param int $id Trip identifier.
     */
    public function findDetailsById(int $id): ?TripDetailsDTO
    {
        $trip = $this->findOne(self::SQL_FIND_TRIP_BY_ID, ['id' => $id]);

        if ($trip === false) {
            return null;
        }

        return new TripDetailsDTO(
            (string) $trip['prenom'],
            (string) $trip['nom'],
            (string) $trip['telephone'],
            (string) $trip['email'],
            (int) $trip['places_disponibles'],
        );
    }

    /**
     * Inserts a new trip in the database.
     *
     * @param TripDTO $tripDTO The trip to insert, without its id (auto-increment)
     * @param int     $userId  Id of the user who created the trip
     */
    public function saveTrip(TripDTO $tripDTO, int $userId): void
    {
        $this->save(
            self::SQL_INSERT,
            [
                'gdh_depart' => $tripDTO->gdhDepart->format('Y-m-d H:i:s'),
                'gdh_arrivee' => $tripDTO->gdhArrivee->format('Y-m-d H:i:s'),
                'places_disponibles' => $tripDTO->placesDisponibles,
                'agence_depart_id' => $tripDTO->agenceDepartId,
                'agence_arrivee_id' => $tripDTO->agenceArriveeId,
                'users_id' => $userId,
            ]
        );
    }
}

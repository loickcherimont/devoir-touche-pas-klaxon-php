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
    private const SQL_DEFAULT_DISPLAY = <<<'SQL'
SELECT
    trips.id,
    a_depart.nom AS depart,
    DATE(trips.gdh_depart) AS date_depart,
    TIME(trips.gdh_depart) AS heure_depart,
    a_arrivee.nom AS destination,
    DATE(trips.gdh_arrivee) AS date_arrivee,
    TIME(trips.gdh_arrivee) AS heure_arrivee,
    trips.places_disponibles,
    trips.users_id
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
     * SQL query selecting the data needed to pre-fill the trip update form,
     * joined with the trip author and its departure/arrival agencies.
     */
    private const SQL_FIND_TRIP_FOR_UPDATE_BY_ID = <<<'SQL'
SELECT
    trips.id,
    users.prenom,
    users.nom,
    users.telephone,
    users.email,
    trips.agence_depart_id,
    a_depart.nom AS depart,
    DATE(trips.gdh_depart) AS date_depart,
    TIME(trips.gdh_depart) AS heure_depart,
    trips.agence_arrivee_id,
    a_arrivee.nom AS destination,
    DATE(trips.gdh_arrivee) AS date_arrivee,
    TIME(trips.gdh_arrivee) AS heure_arrivee,
    trips.places_disponibles,
    trips.users_id
FROM trips
JOIN agencies AS a_depart ON trips.agence_depart_id = a_depart.id
JOIN agencies AS a_arrivee ON trips.agence_arrivee_id = a_arrivee.id
JOIN users ON users.id = trips.users_id
WHERE trips.id = :id
SQL;

    /**
     * SQL query updating the editable fields of an existing trip.
     */
    private const SQL_UPDATE_BY_ID = <<<'SQL'
UPDATE trips
SET gdh_depart = :gdh_depart,
    gdh_arrivee = :gdh_arrivee,
    places_disponibles = :places_disponibles,
    agence_depart_id = :agence_depart_id,
    agence_arrivee_id = :agence_arrivee_id
WHERE trips.id = :id
SQL;

    /**
     * SQL query deleting a trip.
     * The author condition is part of the query: a user can never delete
     * a trip owned by somebody else, whatever the id sent in the URL.
     */
    private const SQL_DELETE_BY_ID = <<<'SQL'
DELETE FROM trips
WHERE id = :id
  AND users_id = :users_id
SQL;

    /**
     * Returns the trips to display on the home page.
     *
     * @return array<array<string, mixed>> The trips as associative arrays
     */
    public function getTrips(): array
    {
        return $this->findAll(self::SQL_DEFAULT_DISPLAY);
    }

    /**
     * Returns the details displayed in the modal, or null if the trip is absent.
     *
     * @param int $id Trip identifier.
     * @return TripDetailsDTO|null The trip author details, or null when the trip does not exist.
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
     * Returns the data needed to pre-fill the trip update form,
     * or null if the trip is absent.
     *
     * @param int $id Trip identifier.
     * @return TripToUpdateDetailsDTO|null The editable trip details, or null when the trip does not exist.
     */
    public function findTripToUpdateDetailsById(int $id): ?TripToUpdateDetailsDTO
    {
        $trip = $this->findOne(self::SQL_FIND_TRIP_FOR_UPDATE_BY_ID, ['id' => $id]);

        if ($trip === false) {
            return null;
        }

        return new TripToUpdateDetailsDTO(
            id: (int) $trip['id'],
            authorFirstName: (string) $trip['prenom'],
            authorLastName: (string) $trip['nom'],
            phone: (string) $trip['telephone'],
            email: (string) $trip['email'],
            departureAgencyId: (int) $trip['agence_depart_id'],
            departure: (string) $trip['depart'],
            departureDate: (string) $trip['date_depart'],
            departureTime: (string) $trip['heure_depart'],
            destinationAgencyId: (int) $trip['agence_arrivee_id'],
            destination: (string) $trip['destination'],
            arrivalDate: (string) $trip['date_arrivee'],
            arrivalTime: (string) $trip['heure_arrivee'],
            availableSeats: (int) $trip['places_disponibles'],
            ownerId: (int) $trip['users_id'],
        );
    }

    /**
     * Inserts a new trip in the database.
     *
     * @param TripDataDTO $tripData The trip to insert, without its id (auto-increment).
     * @param int         $userId   Id of the user who created the trip.
     */
    public function saveTrip(TripDataDTO $tripData, int $userId): void
    {
        $this->save(
            self::SQL_INSERT,
            [
                'gdh_depart' => $tripData->gdhDepart->format('Y-m-d H:i:s'),
                'gdh_arrivee' => $tripData->gdhArrivee->format('Y-m-d H:i:s'),
                'places_disponibles' => $tripData->placesDisponibles,
                'agence_depart_id' => $tripData->agenceDepartId,
                'agence_arrivee_id' => $tripData->agenceArriveeId,
                'users_id' => $userId,
            ]
        );
    }

    /**
     * Updates the editable fields of an existing trip in the database.
     *
     * @param TripDataDTO $tripData The new values of the trip fields.
     * @param int         $id       Id of the trip to update.
     */
    public function updateTripById(TripDataDTO $tripData, int $id): void
    {
        $this->save(self::SQL_UPDATE_BY_ID, [
            'gdh_depart' => $tripData->gdhDepart->format('Y-m-d H:i:s'),
            'gdh_arrivee' => $tripData->gdhArrivee->format('Y-m-d H:i:s'),
            'places_disponibles' => $tripData->placesDisponibles,
            'agence_depart_id' => $tripData->agenceDepartId,
            'agence_arrivee_id' => $tripData->agenceArriveeId,
            'id' => $id,
        ]);
    }

    /**
     * Deletes a trip from the database, but only if it belongs to the given user.
     *
     * Both conditions (trip id and author id) are checked by the SQL query
     * itself, so the deletion happens in a single atomic statement and the
     * controller never has to load the trip first.
     *
     * @param int $id     Id of the trip to delete.
     * @param int $userId Id of the logged-in user, who must own the trip.
     */
    public function deleteTripById(int $id, int $userId): void
    {
        $this->save(self::SQL_DELETE_BY_ID, [
            'id' => $id,
            'users_id' => $userId,
        ]);
    }
}

<?php

namespace App\Model;

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
     * Returns the trips to display on the home page.
     *
     * @return array<array<string, mixed>> The trips as associative arrays
     */
    public function getTrips(): array
    {
        return $this->findAll(self::SQL_AFFICHAGE);
    }
}
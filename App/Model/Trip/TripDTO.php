<?php

namespace App\Model\Trip;

use DateTimeImmutable;

/**
 * TripDTO
 *
 * Immutable data transfer object carrying a trip between the
 * controller layer and the model layer.
 */
final class TripDTO
{
	/**
	 * @param int                $agenceDepartId   Id of the departure agency
	 * @param int                $agenceArriveeId  Id of the arrival agency
	 * @param DateTimeImmutable  $gdhDepart        Departure date and time
	 * @param DateTimeImmutable  $gdhArrivee       Arrival date and time
	 * @param int                $placesDisponibles Number of free seats
	 */
	public function __construct(
		public readonly int $agenceDepartId,
		public readonly int $agenceArriveeId,
		public readonly DateTimeImmutable $gdhDepart,
		public readonly DateTimeImmutable $gdhArrivee,
		public readonly int $placesDisponibles
	){}
}
<?php

namespace App\Model\Trip;

/**
 * TripResponseDTO
 *
 * Immutable data transfer object carrying one trip of the home page listing.
 * userIsOwner is computed by the controller, so the template only has to
 * check it to decide whether to display the "edit" button.
 */
final class TripResponseDTO
{
	/**
	 * @param int    $id               Id of the trip
	 * @param string $departureAgency  Name of the departure agency
	 * @param string $departureDate    Departure date (ex: '2026-10-18')
	 * @param string $departureTime    Departure time (ex: '14:44:00')
	 * @param string $destination      Name of the arrival agency
	 * @param string $arrivalDate      Arrival date (ex: '2026-10-18')
	 * @param string $arrivalTime      Arrival time (ex: '16:44:00')
	 * @param int    $availableSeats   Number of free seats
	 * @param bool   $userIsOwner      True when the logged-in user created the trip
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $departureAgency,
		public readonly string $departureDate,
		public readonly string $departureTime,
		public readonly string $destination,
		public readonly string $arrivalDate,
		public readonly string $arrivalTime,
		public readonly int $availableSeats,
		public readonly bool $userIsOwner
	) {}
}

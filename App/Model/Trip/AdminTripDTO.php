<?php

namespace App\Model\Trip;

/**
 * AdminTripDTO
 *
 * Immutable data transfer object carrying one trip of the admin dashboard
 * listing in read-only. Only displayed data, never used for any write
 * operation.
 */
final class AdminTripDTO
{
	/**
	 * @param int    $id              Id of the trip
	 * @param string $owner           Full name of the trip author
	 * @param string $departureAgency Name of the departure agency
	 * @param string $departureDate   Departure date, MySQL DATE format
	 * @param string $departureTime   Departure time, MySQL TIME format
	 * @param string $arrivalAgency   Name of the arrival agency
	 * @param string $arrivalDate     Arrival date, MySQL DATE format
	 * @param string $arrivalTime     Arrival time, MySQL TIME format
	 * @param int    $availableSeats  Number of seats still available
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $owner,
		public readonly string $departureAgency,
		public readonly string $departureDate,
		public readonly string $departureTime,
		public readonly string $arrivalAgency,
		public readonly string $arrivalDate,
		public readonly string $arrivalTime,
		public readonly int $availableSeats,
	) {}
}
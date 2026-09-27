<?php

namespace App\Model\Trip;

/**
 * TripToUpdateDetailsDTO
 *
 * Immutable data transfer object carrying every value the trip update form
 * needs: the trip author details (displayed read-only), the trip fields
 * (pre-filled in the form) and the id of the user who owns the trip, used
 * to authorize the update.
 */
final class TripToUpdateDetailsDTO
{
	/**
	 * @param int    $id                   Id of the trip
	 * @param string $authorFirstName      First name of the trip author
	 * @param string $authorLastName       Last name of the trip author
	 * @param string $phone                Phone number of the trip author
	 * @param string $email                Email address of the trip author
	 * @param int    $departureAgencyId    Id of the departure agency
	 * @param string $departure            Name of the departure agency
	 * @param string $departureDate        Departure date (ex: '2026-10-18')
	 * @param string $departureTime        Departure time (ex: '14:44:00')
	 * @param int    $destinationAgencyId  Id of the arrival agency
	 * @param string $destination          Name of the arrival agency
	 * @param string $arrivalDate          Arrival date (ex: '2026-10-18')
	 * @param string $arrivalTime          Arrival time (ex: '16:44:00')
	 * @param int    $availableSeats       Number of free seats
	 * @param int    $ownerId              Id of the user who created the trip
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $authorFirstName,
		public readonly string $authorLastName,
		public readonly string $phone,
		public readonly string $email,
		public readonly int $departureAgencyId,
		public readonly string $departure,
		public readonly string $departureDate,
		public readonly string $departureTime,
		public readonly int $destinationAgencyId,
		public readonly string $destination,
		public readonly string $arrivalDate,
		public readonly string $arrivalTime,
		public readonly int $availableSeats,
		public readonly int $ownerId
	) {}
}

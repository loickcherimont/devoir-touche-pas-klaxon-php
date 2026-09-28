<?php

namespace App\Model\Agency;

/**
 * AdminAgencyDTO
 *
 * Immutable data transfer object carrying one agency of the admin dashboard
 * listing in read-only. Only displayed data, never used for any write
 * operation.
 */
final class AdminAgencyDTO
{
	/**
	 * @param int    $id  Id of the agency
	 * @param string $nom Name of the agency (a city)
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $nom
	) {}
}
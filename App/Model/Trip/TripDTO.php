<?php

namespace App\Model\Trip;

use DateTimeImmutable;

final class TripDTO
{
	public function __construct(
		public readonly int $agenceDepartId,
		public readonly int $agenceArriveeId,
		public readonly DateTimeImmutable $gdhDepart,
		public readonly DateTimeImmutable $gdhArrivee,
		public readonly int $placesDisponibles
	){}
}
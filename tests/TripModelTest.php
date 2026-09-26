<?php

use App\Model\Trip\TripDTO;
use App\Model\Trip\TripModel;
use PHPUnit\Framework\TestCase;

/**
 * TripModelTest
 *
 * Unit tests for the trip write operations.
 * PDO is mocked, so no database connection is required.
 */
class TripModelTest extends TestCase
{
	/**
	 * @var int The user id used as the trip author
	 */
	private const USER_ID = 7;

	/**
	 * @var string The departure date and time used across the tests
	 */
	private const DEPART = '2026-09-26 10:26:59';

	/**
	 * @var string The arrival date and time used across the tests
	 */
	private const ARRIVEE = '2026-09-27 10:26:59';

	public function testSaveTripSendsAnInsertToPdoWithTheTripValues(): void
	{
		$createTripDTO = new TripDTO(
			agenceDepartId: 1,
			agenceArriveeId: 2,
			gdhDepart: new DateTimeImmutable(self::DEPART),
			gdhArrivee: new DateTimeImmutable(self::ARRIVEE),
			placesDisponibles: 4,
		);

		$pdoStmt = $this->createMock(PDOStatement::class);
		$pdo = $this->createMock(PDO::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('INSERT INTO trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with([
				'gdh_depart' => self::DEPART,
				'gdh_arrivee' => self::ARRIVEE,
				'places_disponibles' => 4,
				'agence_depart_id' => 1,
				'agence_arrivee_id' => 2,
				'users_id' => self::USER_ID,
			])
			->willReturn(true);

		$model = new TripModel($pdo);

		$model->saveTrip($createTripDTO, self::USER_ID);
	}
}

<?php

namespace Tests;

use App\Model\Trip\TripDataDTO;
use App\Model\Trip\TripModel;
use DateTimeImmutable;
use PDO;
use PDOException;
use PDOStatement;
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
	 * @var int The trip id used by the update test
	 */
	private const TRIP_ID = 5;

	/**
	 * @var string The departure date and time used across the tests
	 */
	private const DEPART = '2026-09-26 10:26:59';

	/**
	 * @var string The arrival date and time used across the tests
	 */
	private const ARRIVEE = '2026-09-27 10:26:59';

	/**
	 * @var int The departure agency id used across the tests
	 */
	private const AGENCE_DEPART_ID = 1;

	/**
	 * @var int The arrival agency id used across the tests
	 */
	private const AGENCE_ARRIVEE_ID = 2;

	/**
	 * @var int The number of free seats used across the tests
	 */
	private const PLACES_DISPONIBLES = 4;

	public function testSaveTripSendsAnInsertToPdoWithTheTripValues(): void
	{
		$tripData = $this->buildTripData();

		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('INSERT INTO trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with([
				'gdh_depart' => self::DEPART,
				'gdh_arrivee' => self::ARRIVEE,
				'places_disponibles' => self::PLACES_DISPONIBLES,
				'agence_depart_id' => self::AGENCE_DEPART_ID,
				'agence_arrivee_id' => self::AGENCE_ARRIVEE_ID,
				'users_id' => self::USER_ID,
			])
			->willReturn(true);

		$model = new TripModel($pdo);

		$model->saveTrip($tripData, self::USER_ID);
	}

	public function testUpdateTripByIdSendsAnUpdateToPdoWithTheTripValues(): void
	{
		$tripData = $this->buildTripData();

		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('UPDATE trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with([
				'gdh_depart' => self::DEPART,
				'gdh_arrivee' => self::ARRIVEE,
				'places_disponibles' => self::PLACES_DISPONIBLES,
				'agence_depart_id' => self::AGENCE_DEPART_ID,
				'agence_arrivee_id' => self::AGENCE_ARRIVEE_ID,
				'id' => self::TRIP_ID,
			])
			->willReturn(true);

		$model = new TripModel($pdo);

		$model->updateTripById($tripData, self::TRIP_ID);
	}

	/**
	 * The trip id is paired with its owner id: the SQL query only deletes the
	 * row when both match, so a user cannot delete somebody else's trip.
	 */
	public function testDeleteTripByIdSendsADeleteToPdoWithTheTripIdAndItsOwnerId(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('DELETE FROM trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with([
				'id' => self::TRIP_ID,
				'users_id' => self::USER_ID,
			])
			->willReturn(true);

		$model = new TripModel($pdo);

		$model->deleteTripById(self::TRIP_ID, self::USER_ID);
	}

	/**
	 * The admin delete drops the owner condition: only the trip id matters.
	 */
	public function testDeleteTripByAdminIdSendsADeleteToPdoWithTheTripId(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('DELETE FROM trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with(['id' => self::TRIP_ID])
			->willReturn(true);

		$model = new TripModel($pdo);

		$model->deleteTripByAdminId(self::TRIP_ID);
	}

	public function testSaveTripPropagatesThePdoExceptionThrownByExecution(): void
	{
		$tripData = $this->buildTripData();

		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('INSERT INTO trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->willThrowException(new PDOException('Foreign key constraint fails', 23000));

		$model = new TripModel($pdo);

		$this->expectException(PDOException::class);

		$model->saveTrip($tripData, self::USER_ID);
	}

	/**
	 * The update goes through the same exception-free save() helper: a
	 * rejection by the database must also reach the caller as a PDOException.
	 */
	public function testUpdateTripByIdPropagatesThePdoExceptionThrownByExecution(): void
	{
		$tripData = $this->buildTripData();

		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('UPDATE trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->willThrowException(new PDOException('Foreign key constraint fails', 23000));

		$model = new TripModel($pdo);

		$this->expectException(PDOException::class);

		$model->updateTripById($tripData, self::TRIP_ID);
	}

	/**
	 * A failed delete must not be silently swallowed either: the caller needs
	 * to know that the row is still in the database.
	 */
	public function testDeleteTripByIdPropagatesThePdoExceptionThrownByExecution(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('DELETE FROM trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->willThrowException(new PDOException('Cannot delete the row', 23000));

		$model = new TripModel($pdo);

		$this->expectException(PDOException::class);

		$model->deleteTripById(self::TRIP_ID, self::USER_ID);
	}

	/**
	 * The admin delete follows the same rule as the regular one: the failure
	 * propagates to the admin controller untouched.
	 */
	public function testDeleteTripByAdminIdPropagatesThePdoExceptionThrownByExecution(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('DELETE FROM trips'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->willThrowException(new PDOException('Cannot delete the row', 23000));

		$model = new TripModel($pdo);

		$this->expectException(PDOException::class);

		$model->deleteTripByAdminId(self::TRIP_ID);
	}

	/**
	 * Builds the trip submitted by the create and update forms,
	 * shared by the two write tests.
	 *
	 * @return TripDataDTO The trip values sent to the model.
	 */
	private function buildTripData(): TripDataDTO
	{
		return new TripDataDTO(
			agenceDepartId: self::AGENCE_DEPART_ID,
			agenceArriveeId: self::AGENCE_ARRIVEE_ID,
			gdhDepart: new DateTimeImmutable(self::DEPART),
			gdhArrivee: new DateTimeImmutable(self::ARRIVEE),
			placesDisponibles: self::PLACES_DISPONIBLES,
		);
	}
}

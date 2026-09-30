<?php

namespace Tests;

use App\Model\Agency\AgencyModel;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\TestCase;

/**
 * AgencyModelTest
 *
 * Unit tests for the agency write operations.
 * PDO is mocked, so no database connection is required.
 */
class AgencyModelTest extends TestCase
{
	/**
	 * @var int The agency id used across the tests
	 */
	private const AGENCY_ID = 99;

	/**
	 * @var string The agency name used across the tests
	 */
	private const AGENCY_NAME = 'Clisson';

	public function testSaveAgencySendsAnInsertToPdoWithAgencyName(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('INSERT INTO agencies'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with(['nom' => self::AGENCY_NAME])
			->willReturn(true);

		$model = new AgencyModel($pdo);
		$model->saveAgency(self::AGENCY_NAME);
	}

	public function testUpdateAgencyByIdSendsAnUpdateToPdoWithAgencyNewNameAndId(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('UPDATE agencies'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with([
				'nom' => self::AGENCY_NAME,
				'id' => self::AGENCY_ID
			])
			->willReturn(true);

		$model = new AgencyModel($pdo);
		$model->updateAgencyById(self::AGENCY_NAME, self::AGENCY_ID);
	}

	public function testDeleteAgencyByIdSendsADeleteToPdoWithTheAgencyId(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('DELETE FROM agencies'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with(['id' => self::AGENCY_ID])
			->willReturn(true);

		$model = new AgencyModel($pdo);
		$model->deleteAgencyById(self::AGENCY_ID);
	}

	/**
	 * In production the PDO runs in ERRMODE_EXCEPTION: a database rejection
	 * (foreign key, duplicate, server error) surfaces as a PDOException thrown
	 * by execute(). The model catches nothing, so it must propagate to the
	 * caller, which decides how to display the failure.
	 */
	public function testSaveAgencyPropagatesThePdoExceptionThrownByExecution(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('INSERT INTO agencies'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->willThrowException(new PDOException('Duplicate name', 23000));

		$model = new AgencyModel($pdo);

		$this->expectException(PDOException::class);

		$model->saveAgency(self::AGENCY_NAME);
	}

	/**
	 * The update goes through the same exception-free save() helper: a
	 * rejection by the database must also reach the caller as a PDOException.
	 */
	public function testUpdateAgencyByIdPropagatesThePdoExceptionThrownByExecution(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('UPDATE agencies'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->willThrowException(new PDOException('Duplicate name', 23000));

		$model = new AgencyModel($pdo);

		$this->expectException(PDOException::class);

		$model->updateAgencyById(self::AGENCY_NAME, self::AGENCY_ID);
	}

	/**
	 * A failed delete must not be silently swallowed either: the caller needs
	 * to know that the row is still in the database. A trip still referencing
	 * the agency is exactly the rejection AdminController::deleteAgency()
	 * catches with SQLSTATE 23000.
	 */
	public function testDeleteAgencyByIdPropagatesThePdoExceptionThrownByExecution(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains('DELETE FROM agencies'))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->willThrowException(new PDOException('FK constraint fails', 23000));

		$model = new AgencyModel($pdo);

		$this->expectException(PDOException::class);

		$model->deleteAgencyById(self::AGENCY_ID);
	}
}

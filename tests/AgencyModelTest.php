<?php

namespace Tests;

use App\Model\Agency\AgencyModel;
use PDO;
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
}

<?php

namespace Tests;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

/**
 * AbstractModelTest
 *
 * Test class for SaveSpyModel (helper) for the AbstractModel abstract class.
 */
class AbstractModelTest extends TestCase
{
	/**
	 * @var string The SQL statement used across the test
	 */
	private const STMT = 'INSERT INTO agencies';

	/**
	 * @var array<string, mixed> The params bound to the statement
	 */
	private const PARAMS = ['id' => 99, 'nom' => 'Clisson'];

	public function testSavePassesTheStatementAndParamsToPdo(): void
	{
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);

		$pdo->expects($this->once())
			->method('prepare')
			->with($this->stringContains(self::STMT))
			->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
			->method('execute')
			->with(self::PARAMS)
			->willReturn(true);

		$model = new SaveSpyModel($pdo);

		$model->callSave(self::STMT, self::PARAMS);
	}
}

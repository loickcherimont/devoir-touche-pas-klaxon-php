<?php

use PHPUnit\Framework\TestCase;
use Tests\SaveSpyModel;

/**
 * Test class for TestModel (helper) for AbstractModel abstract class.
 */
class AbstractModelTest extends TestCase
{
	public function testSavePassesTheStatementAndParamsToPdo(): void
	{
		$stmt = 'INSERT INTO agencies';
		$params = ['id' => 99, 'nom' => 'Clisson'];
		$pdo = $this->createMock(PDO::class);
		$pdoStmt = $this->createMock(PDOStatement::class);
		
		$pdo->expects($this->once())
		->method('prepare')
		->with($this->stringContains('INSERT INTO agencies'))
		->willReturn($pdoStmt);

		$pdoStmt->expects($this->once())
		->method('execute')
		->with($params)
		->willReturn(true);

		$model = new SaveSpyModel($pdo);

		$model->callSave($stmt, $params);
	}
}
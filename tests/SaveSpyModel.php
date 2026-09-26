<?php

namespace Tests;

use Core\AbstractModel;

/**
 * Test double exposing the protected save() inherited from AbstractModel,
 * so AbstractModelTest can observe what save() sends to PDO.
 */
class SaveSpyModel extends AbstractModel
{
	/**
	 * Exposes the protected save() to the test suite.
	 *
	 * @param string               $stmt   The SQL query with placeholders (ex: :id)
	 * @param array<string, mixed> $params Values bound to the placeholders
	 */
	public function callSave(string $stmt, array $params = []): void
	{
		$this->save($stmt, $params);
	}
}
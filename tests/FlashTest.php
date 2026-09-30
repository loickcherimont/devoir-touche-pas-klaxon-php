<?php

namespace Tests;

use App\Security\Flash;
use PHPUnit\Framework\TestCase;

/**
 * FlashTest
 *
 * Covers the one-shot session message storage of the Flash helper.
 */
class FlashTest extends TestCase
{
	protected function setUp(): void
	{
		$_SESSION = [];
	}

	/**
	 * A success message is stored under the 'success' alert type.
	 */
	public function testSuccessMessageIsStored(): void
	{
		Flash::success('Créé');

		$this->assertSame(['success' => 'Créé'], Flash::all());
	}

	/**
	 * An error message is stored under the 'danger' alert type.
	 */
	public function testErrorMessageIsStored(): void
	{
		Flash::error('Échec');

		$this->assertSame(['danger' => 'Échec'], Flash::all());
	}

	/**
	 * A stored message survives until the next render, then clear() removes it.
	 */
	public function testClearRemovesStoredMessages(): void
	{
		Flash::success('Créé');
		Flash::clear();

		$this->assertSame([], Flash::all());
	}

	/**
	 * Without any stored message, all() returns an empty array.
	 */
	public function testAllReturnsEmptyArrayWithoutStoredMessage(): void
	{
		$this->assertSame([], Flash::all());
	}
}
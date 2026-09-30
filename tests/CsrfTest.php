<?php

namespace Tests;

use App\Security\Csrf;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * CsrfTest
 *
 * Covers the session token behaviour of the Csrf helper.
 */
class CsrfTest extends TestCase
{
	protected function setUp(): void
	{
		$_SESSION = [];
	}

	/**
	 * The token is generated once and stays stable for the whole session.
	 */
	public function testTokenIsGeneratedOnceAndStableInsideASession(): void
	{
		$first = Csrf::token();
		$second = Csrf::token();

		$this->assertSame($first, $second);
	}

	/**
	 * A request carrying the session token passes the verification.
	 */
	public function testVerifyAcceptsTheMatchingToken(): void
	{
		$request = new Request([], ['csrf_token' => Csrf::token()]);

		$this->assertTrue(Csrf::verify($request));
	}

	/**
	 * A request carrying another token is rejected.
	 */
	public function testVerifyRejectsAWrongToken(): void
	{
		Csrf::token();
		$request = new Request([], ['csrf_token' => 'wrong-token']);

		$this->assertFalse(Csrf::verify($request));
	}

	/**
	 * The hidden field embeds the session token.
	 */
	public function testFieldContainsTheSessionToken(): void
	{
		$this->assertStringContainsString(Csrf::token(), Csrf::field());
	}
}
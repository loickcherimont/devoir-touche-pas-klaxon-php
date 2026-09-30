<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;

/**
 * CSRF protection for every POST form: a single token stored in the session,
 * compared with hash_equals() on submit, and echoed as a hidden field.
 */
final class Csrf
{
	/**
	 * Returns the session token, generating it on first use.
	 *
	 * @return string The token stored in the session
	 */
	public static function token(): string
	{
		if (!isset($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}

		return $_SESSION['csrf_token'];
	}

	/**
	 * Returns the hidden input to place inside a form.
	 *
	 * @return string The HTML of the hidden field
	 */
	public static function field(): string
	{
		return '<input type=\'hidden\' name=\'csrf_token\' value=\'' . self::token() . '\'>';
	}

	/**
	 * Tells whether the submitted token matches the session one.
	 *
	 * @param Request $request Incoming HTTP request carrying the submitted token
	 * @return bool True when the token matches, false otherwise
	 */
	public static function verify(Request $request): bool
	{
		return hash_equals(self::token(), (string) $request->request->get('csrf_token'));
	}
}
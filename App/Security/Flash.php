<?php

namespace App\Security;

/**
 * Flash messages: one-shot session messages consumed on the next render,
 * the standard companion of the Post-Redirect-Get pattern.
 */
final class Flash
{
	/**
	 * Stores a success message for the next render.
	 *
	 * @param string $message The message to display
	 */
	public static function success(string $message): void
	{
		self::store('success', $message);
	}

	/**
	 * Stores an error message for the next render.
	 *
	 * @param string $message The message to display
	 */
	public static function error(string $message): void
	{
		self::store('danger', $message);
	}

	/**
	 * Returns the stored messages without clearing them.
	 *
	 * @return array<string, string> The messages, indexed by Bootstrap alert type
	 */
	public static function all(): array
	{
		$messages = $_SESSION['flash'] ?? [];

		return is_array($messages) ? $messages : [];
	}

	/**
	 * Removes the stored messages once they have been displayed.
	 */
	public static function clear(): void
	{
		unset($_SESSION['flash']);
	}

	/**
	 * Stores a message under the given Bootstrap alert type.
	 *
	 * @param string $type    The alert type (success, danger, ...)
	 * @param string $message The message to display
	 */
	private static function store(string $type, string $message): void
	{
		$_SESSION['flash'][$type] = $message;
	}
}
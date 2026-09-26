<?php

namespace Core;

/**
 * Application configuration class
 */
final class Config
{

	/**
	 * Reads a configuration value from the environment.
	 *
	 * @param string $key     Environment variable name (ex: 'DB_HOST')
	 * @param string $default Value to use when the variable is not defined
	 * @return string        The value found, or $default
	 */
	public static function get(string $key, string $default = ''): string
	{
		$value = getenv($key);

		return $value === false ? $default : $value;
	}
}

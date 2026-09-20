<?php

namespace Core;

/**
 * Application configuration class
 */
final class Config
{

	/**
     * @param string $key     environment variable name (ex: 'DB_HOST')
     * @param string $default value to use if the environment variable does not exist
     * @return string found value or $default
     */
	public static function get(string $key, string $default = ''): string
	{
		$value = getenv($key);

		return $value === false ? $default : $value;
	}
}

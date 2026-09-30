<?php

namespace App;

/**
 * Builds HTML pages by rendering the shared page skeleton (DRY: the only place
 * that captures template output). Static because rendering holds no state.
 */
class View
{
	/**
	 * Renders a full page: header, given template and footer.
	 *
	 * @param string               $content Template file name without .php extension,
	 *                                      e.g. "login" renders templates/login.php.
	 * @param array<string, mixed> $data    Variables made available to the templates.
	 * @return string The complete page as an HTML string.
	 */
	public static function page(string $content, array $data = []): string
	{
		extract($data, EXTR_SKIP);
		ob_start();

		require ROOT_PATH . '/templates/header.php';
		require ROOT_PATH . '/templates/' . $content . '.php';
		require ROOT_PATH . '/templates/footer.php';

		$html = ob_get_clean();

		// ob_get_clean() only returns false if no buffer is active,
		// which cannot happen here since ob_start() opened one above.
		return $html === false ? '' : $html;
	}
}
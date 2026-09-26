<?php

/**
 * Bootstrap — project entry configuration.
 *
 * Loaded automatically by Composer on every request (autoload.files).
 * Defines ROOT_PATH: the absolute path to the project root, the single
 * anchor used to include any file without relative paths.
 */

const ROOT_PATH = __DIR__;

/**
 * Default timezone of the application.
 * Set once here so every date comparison is done on the same
 * reference, instead of relying on the server or the browser one.
 */
date_default_timezone_set('Europe/Paris');
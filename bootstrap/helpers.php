<?php

/**
 * Gets the value of an environment variable. Supports booleans, empty and null.
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? false;

    if ($value === false) {
        return $default;
    }

    $cleaned_value = trim(strtolower($value), "() \n\r\t\v\x00");
    return match ($cleaned_value) {
        'true', 'false', 'yes', 'no' => filter_var($cleaned_value, FILTER_VALIDATE_BOOLEAN),
        'empty' => '',
        'null' => null,
        default => trim($value)
    };
}

/**
 * Indicates if the current application environment is "production".
 *
 * @return bool
 */
function isProduction(): bool
{
    return env('APP_ENV') == 'production';
}

/**
 * Returns the path to the root app directory, appended with extra path.
 *
 * @param string ...$paths
 * @return string
 */
function app_path(string ...$paths): string
{
    $path = array_reduce($paths, fn($acc, $cur) => $acc.'/'.ltrim($cur, ' /\\'), '');

    return __ROOT_DIR__.$path;
}

/**
 * Returns the path to the config directory, appended with extra path.
 *
 * @param string $path
 * @return string
 */
function config_path(string $path): string
{
    return app_path('config', $path);
}

/**
 * Returns the path to the storage directory, appended with extra path.
 *
 * @param string ...$paths
 * @return string
 */
function storage_path(string ...$paths): string
{
    return call_user_func_array('app_path', array_merge(['storage'], $paths));
}

/**
 * Returns the path to the cache directory, appended with extra path.
 *
 * @param string $path
 * @return string
 */
function cache_path(string $path = ''): string
{
    return storage_path('cache', $path);
}

/**
 * Returns the path to the logs' directory, appended with extra path.
 *
 * @param string $path
 * @return string
 */
function logs_path(string $path = ''): string
{
    return storage_path('logs', $path);
}

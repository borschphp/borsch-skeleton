<?php

/**
 * Gets the value of an environment variable. Supports booleans, empty and null.
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env(string $key, $default = null)
{
    $value = $_ENV[$key] ?? false;

    if ($value === false) {
        return $default;
    }

    $cleaned_value = trim(strtolower($value), '() ');
    switch ($cleaned_value) {
        case 'true':
        case 'false':
            return filter_var($cleaned_value, FILTER_VALIDATE_BOOLEAN);

        case 'empty':
            return '';

        case 'null':
            return null;
    }

    return trim($value, '"');
}

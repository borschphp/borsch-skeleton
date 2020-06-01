<?php

/**
 * Get environment variable.
 *
 * @param string $name The environment value to retrieve .
 * @param null $default The default value if not found.
 * @return null|string|array If found, return the string value or an array if $name correspond to a section of the
 *                           ini file.
 *                           If not found, returns $default or null.
 * @uses env.ini
 */
function env(string $name, $default = null)
{
    global $environment;
    if (!is_array($environment)) {
        $environment = parse_ini_file(__DIR__.'/env.ini', true);

        if ($environment == false) {
            throw new RuntimeException(sprintf(
                'Unable to load or parse the env.ini file, located in: %s/env.ini.',
                __DIR__
            ));
        }
    }

    $segments = explode('.', $name);

    // $value = null;
    // foreach ($segments as $segment) {
    //     $value = (($value ?: $environment)[$segment]) ?? $default;
    // }
    // return $value;

    // Only a depth of 2 anyway, can make it simpler.
    $count = count($segments);
    if ($count > 2) {
        return $default;
    } elseif ($count == 2) {
        return $environment[$segments[0]][$segments[1]] ?? $default;
    }

    return $environment[$segments[0]] ?? $default;
}

<?php

$environment_file = config_path('environment.php');

// If environment file exists, load it so that it won't be necessary to parse and load .env file at every call.
if (file_exists($environment_file)) {
    $environment = require_once $environment_file;
    $_ENV = array_merge($_ENV, $environment);
    return;
}

$dotenv = Dotenv\Dotenv::createImmutable(app_path());
$environment = $dotenv->safeLoad();

// In production, save the environment in a file so that it won't be necessary to parse and load .env file at every call.
if (isProduction() && count($environment)) {
    $file = new SplFileObject($environment_file, 'w');
    $file->fwrite('<?php'.PHP_EOL.PHP_EOL.'return '.var_export($environment, true).';'.PHP_EOL);
    $file = null;
}

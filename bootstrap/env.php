<?php

require_once __DIR__.'/../vendor/autoload.php';

$environment_file = __DIR__.'/../config/environment.php';

// If environment file exists, load it so that it wont be necessary to parse and load .env file at every call.
if (file_exists($environment_file)) {
    $environment = require_once $environment_file;
    $_ENV = array_merge($_ENV, $environment);
    return;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$environment = $dotenv->load();

// In production, save the environment in a file so that it wont be necessary to parse and load .env file at every call.
if (env('APP_ENV') == 'production') {
    $file = new SplFileObject($environment_file, 'w');
    $file->fwrite('<?php'.PHP_EOL.PHP_EOL.'return '.var_export($environment, true).';'.PHP_EOL);
    $file = null;
}

<?php

use NiftyCo\Skeletor\Skeletor;

return function (Skeletor $skeletor) {
    $skeletor->intro('Installation wizard');

    $app_name = $skeletor->text('What is the name of your application ?', 'Borsch-Skeleton');

    $installation_type = $skeletor->select('What type of application do you need ?', [
        'MINIMAL' => 'Minimal (to create API)',
        'FULL' =>    'Full    (with views)',
    ], 'FULL');

    if ($installation_type === 'MINIMAL') {
        $skeletor->spin('Removing front handlers', function () use ($skeletor) {
            $skeletor->removeFile('src/Handler/HomeHandler.php');
            $skeletor->pregReplaceInFile(
                '/\s\$app->[\s\S].+/',
                '',
                'config/routes.php'
            );

            return true;
        }, 'Removed front handlers', 'Unable to completely remove front handlers');


        $skeletor->spin('Removing template files and configuration', function () use ($skeletor) {
            $skeletor->removeFile('config/containers/template.container.php');
            $skeletor->pregReplaceInFile(
                '/\n[\s\S].+template[\s\S].+;/',
                '',
                'config/container.php'
            );
            $skeletor->removeFile('storage/views/404.tpl');
            $skeletor->removeFile('storage/views/500.tpl');
            $skeletor->removeFile('storage/views/home.tpl');
            // There is an issue with `Skeletor::removeDirectory(string $filename);` because it internally uses `rmdir`
            // which has a parameter named `$path` and not `$filename`.
            // Because of the use of `get_defined_vars()`, `rmdir` receives a parameter named `$filename` instead of
            // `$path` and that generates an error.
            // $skeletor->removeDirectory('storage/views');
            @rmdir('storage/views');

            return true;
        }, 'Removed template files and configuration', 'Unable to completely remove template files and configuration');

        $skeletor->spin('Removing Latte template engine from composer.json', function () use ($skeletor) {
            $skeletor->pregReplaceInFile(
                '/\n[\s].+"borschphp\/latte"[\s\S].+/',
                '',
                'composer.json'
            );

            $skeletor->exec(['composer', 'update']);

            return true;
        }, 'Removing Latte template engine from composer.json', 'Unable to completely remove Latte template engine from composer.json');

        $skeletor->spin('Creating environment file', function () use ($skeletor, $app_name) {)
            if (!$skeletor->exists('.env')) {
                copy('.env.example', '.env');
            }

            $random_key = base64_encode(random_bytes(32));

            $skeletor->replaceInFile('APP_NAME=Laravel', 'APP_NAME='.$app_name, '.env');
            $skeletor->replaceInFile('APP_KEY=', 'APP_KEY='.$random_key, '.env');

            return true;
        }, 'Created environment file', 'Unable to create environment file');
    }

    $skeletor->outro('Installation complete');
};

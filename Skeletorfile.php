<?php

use NiftyCo\Skeletor\Skeletor;

return function (Skeletor $skeletor) {
    $skeletor->intro('Installation wizard');

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
        }, 'Done successfully');


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
            $skeletor->removeDirectory('storage/views');

            return true;
        }, 'Done successfully');

        $skeletor->spin('Removing latte/latte from composer.json', function () use ($skeletor) {
            $skeletor->updateComposerJson([
                'require' => [
                    'borschphp/latte' => null,
                ],
            ]);

            return true;
        }, 'Done successfully');
    }

    $skeletor->outro('Installation complete');
};

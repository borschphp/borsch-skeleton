<?php

use NiftyCo\Skeletor\Skeletor;

return function (Skeletor $skeletor) {
    $skeletor->intro('Installation wizard');

    $installation_type = $skeletor->select('What type of application do you need ?', [
        'MINIMAL' => 'Minimal (to create API)',
        'FULL' =>    'Full    (with views)',
    ], 'FULL');

    if ($installation_type === 'MINIMAL') {
        $skeletor->log('Removing front handlers');
        $skeletor->removeFile('src/Handler/HomeHandler.php');
        $skeletor->pregReplaceInFile(
            '/\s\$app->[\s\S].+/',
            '',
            'config/routes.php'
        );

        $skeletor->log('Removing template files and configuration');
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

        $skeletor->log('Removing latte/latte from composer.json');
        $skeletor->updateComposerJson([
            'require' => [
                'borschphp/latte' => null,
            ],
        ]);

    }

    $skeletor->outro('Installation complete');
};

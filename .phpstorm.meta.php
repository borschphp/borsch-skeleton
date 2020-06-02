<?php

namespace PHPSTORM_META
{
    override(\Psr\Container\ContainerInterface::get(0), map([
        '' => '@',
    ]));

    expectedArguments(
        \env(),
        0,
        'APP_NAME',
        'APP_ENV',
        'APP_DEBUG',
        'APP_URL',
        'APP_KEY',
        'LOG_CHANNEL',
        'DATABASE',
        'DATABASE.driver',
        'DATABASE.database',
        'DATABASE.username',
        'DATABASE.password',
        'DATABASE.hostname',
        'DATABASE.port',
        'DATABASE.charset'
    );
}

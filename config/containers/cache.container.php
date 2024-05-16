<?php

use App\CachePool\SQLiteCacheItemPool;
use Borsch\Cache\Cache;
use Borsch\Container\Container;
use Monolog\Logger;
use Psr\SimpleCache\CacheInterface;

return static function(Container $container): void {
    $container->set(
        CacheInterface::class,
        fn(SQliteCacheItemPool $pool, Logger $logger) => new Cache($pool, $logger)
    )->cache(true);
};

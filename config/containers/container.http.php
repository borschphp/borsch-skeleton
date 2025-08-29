<?php

use Borsch\Container\Container;
use Borsch\Http\Factory\{ResponseFactory, ServerRequestFactory, StreamFactory, UploadedFileFactory};
use Psr\Http\Message\{ResponseFactoryInterface,
    ServerRequestInterface,
    StreamFactoryInterface,
    UploadedFileFactoryInterface};

return static function (Container $container) {

    /*
     * This is a factory for the PSR-7 ServerRequestInterface.
     *
     * It creates a ServerRequest object based on the current PHP environment.
     * It uses the $_SERVER superglobal to determine the request method, URI, and other relevant information.
     */
    $container->set(ServerRequestInterface::class, static function () {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        $uri = "$scheme://$host" . ($_SERVER['REQUEST_URI'] ?? '');

        return (new ServerRequestFactory())->createServerRequest($_SERVER['REQUEST_METHOD'], $uri, $_SERVER);
    })->cache(false);

    /*
     * Necessary factories for PSR-7, used in middlewares and controllers.
     */
    $container->set(UploadedFileFactoryInterface::class, UploadedFileFactory::class);
    $container->set(StreamFactoryInterface::class, StreamFactory::class);
    $container->set(ResponseFactoryInterface::class, ResponseFactory::class);

};

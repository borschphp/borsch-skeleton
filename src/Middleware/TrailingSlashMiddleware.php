<?php

namespace App\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class TrailingSlashMiddleware
 * @package App\Middleware
 */
class TrailingSlashMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     * @link http://www.slimframework.com/docs/v4/cookbook/route-patterns.html
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path != '/' && str_ends_with($path, '/')) {
            // Permanently redirect paths with a trailing slash to their non-trailing equivalent
            $uri = $uri->withPath(rtrim($path, '/'));

            if ($request->getMethod() == 'GET') {
                return new RedirectResponse((string)$uri, 301);
            }

            $request = $request->withUri($uri);
        }

        return $handler->handle($request);
    }
}

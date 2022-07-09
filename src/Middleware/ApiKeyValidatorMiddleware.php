<?php

namespace App\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class ApiMiddleware
 *
 * A path-piped Middleware, therefore it will be called everytime the requested path will begin by "/path".
 * This is the perfect place to check if the client can access the routes starting by "/api".
 * For instance, you can check if there is an API Key in the request, or other...
 *
 * @package App\Middleware
 */
class ApiKeyValidatorMiddleware implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Example:
        // If the client did not provide valid credentials to access the /api resources,
        // then return a 401 Unauthorized response.
        //
        // Note: ServerRequestInterface::getHeaderLine() always return a string, so for the sake of this example we
        // compare the value to exactly null so that it never actually send a 403 Forbidden response.
        //
        // Modify according to your needs.
        $credentials = $request->getHeaderLine('X-API-KEY');
        if ($credentials === null) {
            return new Response('php://memory', 401);
        }

        // Provided credentials are valid, let's continue.

        // Maybe we want to add credentials information inside the request
        $request = $request
            ->withAttribute('api_key', $credentials ?: '0000-0000-0000-0000')
            ->withAttribute('username', 'john.doe')
            ->withAttribute('email', 'john.doe@gmail.com');

        return $handler->handle($request);
    }
}

<?php

namespace Application\Handler;

use Borsch\Http\Response\HtmlResponse;
use Borsch\Router\Attribute\Controller;
use Borsch\Router\Attribute\Get;
use Borsch\Router\Contract\RouterInterface;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

#[Controller('/api')]
readonly class RedocHandler implements RequestHandlerInterface
{

    public function __construct(
        private RouterInterface $router
    ) {}

    #[Get(path: '/{redoc:redoc|swagger}')]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $openapi_url = $this->router->generateUri('openapi', ['format' => 'json']);

        return new HtmlResponse(
            <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>API Documentation</title>
                <body>
                <redoc spec-url="$openapi_url"></redoc>
                <script src="https://cdn.redoc.ly/redoc/latest/bundles/redoc.standalone.js"> </script>
                </body>
                </html>
            HTML);
    }
}

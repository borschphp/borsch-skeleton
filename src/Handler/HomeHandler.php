<?php

namespace App\Handler;

use Borsch\Router\RouterInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\{Message\ResponseInterface, Message\ServerRequestInterface, Server\RequestHandlerInterface};

/**
 * Class HomeHandler
 * @package App\Handler
 */
class HomeHandler implements RequestHandlerInterface
{

    /**
     * @param RouterInterface $router
     */
    public function __construct(
        protected RouterInterface $router,
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(sprintf(
            '<h3>Hello %s !</h3><p>Check <a href="%s">Peoples API here</a>.</p>',
            ($request->getQueryParams()['name'] ?? $request->getHeaderLine('X-Name')) ?: 'World',
            $this->router->generateUri('peoples')
        ));
    }
}

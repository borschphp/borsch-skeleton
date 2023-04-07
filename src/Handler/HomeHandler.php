<?php

namespace App\Handler;

use App\Template\BasicTemplateEngine;
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
        protected BasicTemplateEngine $engine
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->engine->assign([
            'name' => ($request->getQueryParams()['name'] ?? $request->getHeaderLine('X-Name')) ?: 'World',
            'url' => $this->router->generateUri('peoples')
        ]);

        return new HtmlResponse($this->engine->render('home.tpl'));
    }
}

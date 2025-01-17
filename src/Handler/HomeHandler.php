<?php

namespace App\Handler;

use Borsch\Router\RouterInterface;
use Borsch\Template\TemplateRendererInterface;
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
     * @param TemplateRendererInterface $engine
     */
    public function __construct(
        protected RouterInterface $router,
        protected TemplateRendererInterface $engine
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->engine->assign([
            'name' => ($request->getQueryParams()['name'] ?? $request->getHeaderLine('X-Name')) ?: 'World'
        ]);

        return new HtmlResponse($this->engine->render('home.tpl'));
    }
}

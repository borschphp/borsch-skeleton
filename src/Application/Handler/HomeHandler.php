<?php

namespace Application\Handler;

use Borsch\Http\Response\HtmlResponse;
use Borsch\Template\TemplateRendererInterface;
use Borsch\Router\Attribute\{Controller, Get};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

#[Controller]
readonly class HomeHandler implements RequestHandlerInterface
{

    public function __construct(
        protected TemplateRendererInterface $engine
    ) {}

    #[Get(path: '/', name: 'home')]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->engine->assign([
            'name' => ($request->getQueryParams()['name'] ?? $request->getHeaderLine('X-Name')) ?: 'World'
        ]);

        return new HtmlResponse($this->engine->render('home.tpl'));
    }
}

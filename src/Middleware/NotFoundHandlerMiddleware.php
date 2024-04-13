<?php

namespace App\Middleware;

use App\Exception\ProblemDetailsException;
use Borsch\Template\TemplateRendererInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class NotFoundHandlerMiddleware
 *
 * Generates a 404 Not Found response.
 *
 * @package App\Middleware
 */
class NotFoundHandlerMiddleware implements MiddlewareInterface
{

    public function __construct(
        protected TemplateRendererInterface $engine
    ) {}

    /**
     * @inheritDoc
     * @throws ProblemDetailsException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (str_starts_with($request->getUri()->getPath(), '/api/')) {
            throw new ProblemDetailsException(
                sprintf('No resource found for "%s"', $request->getUri()->getPath()),
                404
            );
        }

        return new HtmlResponse($this->engine->render('404.tpl'));
    }
}

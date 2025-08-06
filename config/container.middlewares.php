<?php

use Borsch\Container\Container;
use Borsch\Http\Response\{HtmlResponse, JsonResponse};
use Borsch\Middleware\{ErrorHandlerMiddleware, NotFoundHandlerMiddleware};
use Borsch\Template\TemplateRendererInterface;
use ProblemDetails\{ProblemDetails, ProblemDetailsException};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

return static function (Container $container) {

    $container->set(ErrorHandlerMiddleware::class, static fn(TemplateRendererInterface $renderer) => new ErrorHandlerMiddleware(
        static function (Throwable $throwable, ServerRequestInterface $request) use ($renderer): ResponseInterface {
            if (str_starts_with($request->getUri()->getPath(), '/api')) {
                return new JsonResponse(new ProblemDetails(
                    type: '://problem/internal-server-error',
                    title: 'Internal server error.',
                    status: 500,
                    detail: $throwable->getMessage()
                ), 500);
            }

            return new HtmlResponse(
                $renderer->render('500.tpl'),
                500
            );
        }
    ));

    $container->set(NotFoundHandlerMiddleware::class, static function (TemplateRendererInterface $renderer) {
        return new NotFoundHandlerMiddleware(static function (ServerRequestInterface $request) use ($renderer): ResponseInterface {
            if (str_starts_with($request->getUri()->getPath(), '/api')) {
                throw new ProblemDetailsException(new ProblemDetails(
                    type: '://problem/not-found',
                    title: 'Not found.',
                    status: 404,
                    detail: "The requested uri ({$request->getUri()->getPath()}) could not be found."
                ));
            }

            return new HtmlResponse(
                $renderer->render('404.tpl'),
                404
            );
        });
    });

};

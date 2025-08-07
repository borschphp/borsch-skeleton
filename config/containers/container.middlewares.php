<?php

use Borsch\Container\Container;
use Borsch\Http\Response\{HtmlResponse, JsonResponse};
use Borsch\Middleware\{ErrorHandlerMiddleware, NotFoundHandlerMiddleware};
use Borsch\Template\TemplateRendererInterface;
use ProblemDetails\{ProblemDetails, ProblemDetailsException};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

return static function (Container $container) {

    /*
     * The ErrorHandlerMiddleware is responsible for handling exceptions and returning appropriate responses.
     *
     * If the request is for an API endpoint, it returns a JSON response with a ProblemDetails object.
     * Otherwise, it returns an HTML response with a 500 error page.
     *
     * It should be registered before any other middleware so that it can catch exceptions and handle them
     * appropriately.
     */
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

    /*
     * The NotFoundHandlerMiddleware is responsible for handling 404 Not Found errors.
     *
     * If the request is for an API endpoint, it returns a JSON response with a ProblemDetails object.
     * Otherwise, it returns an HTML response with a 404 error page.
     */
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

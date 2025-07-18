<?php

use App\Listener\MonologListener;
use Borsch\Formatter\{FormatterInterface, HtmlFormatter, JsonFormatter};
use Borsch\Middleware\{ErrorHandlerMiddleware, NotFoundHandlerMiddleware};
use League\Container\{Container, ServiceProvider\AbstractServiceProvider};
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ResponseFactory;
use ProblemDetails\ProblemDetails;
use ProblemDetails\ProblemDetailsException;
use ProblemDetails\ProblemDetailsMiddleware;
use Psr\Http\Message\{RequestInterface, ResponseInterface, ServerRequestInterface};

return static function(Container $container): void {
    $container->addServiceProvider(new class extends AbstractServiceProvider {

        public function provides(string $id): bool
        {
            return in_array($id, [
                FormatterInterface::class,
                ErrorHandlerMiddleware::class,
                NotFoundHandlerMiddleware::class,
                ProblemDetailsMiddleware::class,
            ]);
        }

        public function register(): void
        {
            $this
                ->getContainer()
                ->add(FormatterInterface::class, fn(): FormatterInterface => new class implements FormatterInterface {

                    public function format(ResponseInterface $response, Throwable $throwable, RequestInterface $request): ResponseInterface
                    {
                        $formatter = str_starts_with($request->getUri()->getPath(), '/api') ?
                            new JsonFormatter() :
                            new HtmlFormatter(isProduction());

                        return $formatter->format($response, $throwable, $request);
                    }
                });

            $this
                ->getContainer()
                ->add(ErrorHandlerMiddleware::class)
                ->addArgument($this->getContainer()->get(FormatterInterface::class))
                ->addArgument([$this->getContainer()->get(MonologListener::class)]);

            $this
                ->getContainer()
                ->add(NotFoundHandlerMiddleware::class)
                ->addArgument(static function (ServerRequestInterface $request): ResponseInterface {
                    if (str_starts_with($request->getUri()->getPath(), '/api')) {
                        throw new ProblemDetailsException(new ProblemDetails(
                            type: '://problem/not-found',
                            title: 'Not found.',
                            status: 404,
                            detail: "The requested uri ({$request->getUri()->getPath()}) could not be find."
                        ));
                    }

                    return new HtmlResponse(
                        '<h1>404 Not Found</h1>',
                        404
                    );
                });

            $this
                ->getContainer()
                ->add(ProblemDetailsMiddleware::class)
                ->addArgument(new ResponseFactory());
        }
    });
};

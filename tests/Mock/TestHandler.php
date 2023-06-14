<?php

namespace AppTest\Mock;

use Borsch\Router\RouteResultInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TestHandler implements RequestHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        if ($path == '/to/exception') {
            throw new Exception('Pest for testz!');
        } elseif (in_array($path, ['/to/post/and/check/json', '/to/post/and/check/urlencoded', '/to/post/and/check/xml'])) {
            return new JsonResponse($request->getParsedBody());
        } elseif ($path == '/to/route/result') {
            return new JsonResponse([
                'route_result_is_success' => $request->getAttribute(RouteResultInterface::class)?->isSuccess() ?? false
            ]);
        }

        return new TextResponse(__METHOD__);
    }
}

<?php

namespace AppTest\Middleware;

use AppTest\App;
use AppTest\Mockup\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

class NotFoundHandlerMiddlewareTest extends App
{

    public function testProcessNotFoundWhenPathDoesNotExist()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/an/unknown/path');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testProcessWhenPathExists()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }
}

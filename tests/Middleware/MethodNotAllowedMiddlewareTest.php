<?php

namespace AppTest\Middleware;

use AppTest\App;
use AppTest\Mockup\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

class MethodNotAllowedMiddlewareTest extends App
{

    public function testProcessSkipWhenMethodIsAllowed()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessBlockWhenMethodIsNotAllowed()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('PUT', 'https://example.com/to/dispatch');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEmpty((string)$response->getBody());
    }
}

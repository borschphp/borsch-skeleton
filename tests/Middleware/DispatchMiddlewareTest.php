<?php

namespace AppTest\Middleware;

use AppTest\App;
use AppTest\Mockup\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

class DispatchMiddlewareTest extends App
{

    public function testProcessExistingRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessNonExistingRouteResultInNotFound()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/not/dispatch');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertNotEquals(TestHandler::class.'::handle', (string)$response->getBody());
        $this->assertEquals(404, $response->getStatusCode());
    }
}

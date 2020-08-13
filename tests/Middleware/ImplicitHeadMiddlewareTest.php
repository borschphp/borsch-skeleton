<?php

namespace AppTest\Middleware;

use AppTest\App;
use AppTest\Mockup\HeadHandler;
use AppTest\Mockup\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

class ImplicitHeadMiddlewareTest extends App
{

    public function testProcessWithGetEquivalent()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('HEAD', 'https://example.com/to/dispatch');
        $response = $this->app->runAndGetResponse($server_request);

        // Should return the equivalent GET response
        // FastRoute already deals with HEAD and GET matching
        // https://github.com/nikic/FastRoute#a-note-on-head-requests
        $this->assertEquals(TestHandler::class.'::handle', (string)$response->getBody());
    }

    public function testProcessWithoutGetEquivalent()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('HEAD', 'https://example.com/to/head/without/post');
        $response = $this->app->runAndGetResponse($server_request);

        // Should return not allowed (MethodNotAllowedMiddleware)
        $this->assertNotEquals(TestHandler::class.'::handle', (string)$response->getBody());
        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testProcessWithHeadRoute()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('HEAD', 'https://example.com/to/head');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(HeadHandler::class.'::handle', (string)$response->getBody());
    }
}

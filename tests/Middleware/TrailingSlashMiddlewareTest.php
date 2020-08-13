<?php

namespace AppTest\Middleware;

use AppTest\App;
use Laminas\Diactoros\ServerRequestFactory;

class TrailingSlashMiddlewareTest extends App
{

    public function testProcess()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch/');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(
            '/to/dispatch', // 12
            substr($response->getHeaderLine('Location'), -12)
        );
    }
}

<?php

namespace AppTest\Middleware;

use AppTest\App;
use Laminas\Diactoros\ServerRequestFactory;

class ImplicitOptionsMiddlewareTest extends App
{

    public function testProcessWithAllowedMethods()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('OPTIONS', 'https://example.com/to/options');
        $response = $this->app->runAndGetResponse($server_request);

        // TODO
        $this->assertEquals(200, $response->getStatusCode());
    }
}

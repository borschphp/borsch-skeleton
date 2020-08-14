<?php

namespace AppTest\Middleware;

use AppTest\App;
use AppTest\Mockup\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

class ContentLengthMiddlewareTest extends App
{

    public function testContentLengthIsCorrect()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');
        $response = $this->app->runAndGetResponse($server_request);

        $this->assertEquals(
            strlen(TestHandler::class.'::handle'),
            (int)$response->getHeaderLine('Content-Length')
        );
    }
}

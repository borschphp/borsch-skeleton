<?php

namespace AppTest\Middleware;

use AppTest\App;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;

class BodyParserMiddlewareTest extends App
{

    public function testProcessJson()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('POST', 'https://example.com/to/post/and/check/json')
            ->withBody((new StreamFactory())->createStream(json_encode(['ping' => 'pong'])))
            ->withHeader('Content-Type', 'application/json');

        $response = $this->app->runAndGetResponse($server_request);

        $this->assertJson((string)$response->getBody());

        $response = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('ping', $response);
        $this->assertEquals('pong', $response['ping']);
    }

    public function testProcessUrlEncoded()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('POST', 'https://example.com/to/post/and/check/urlencoded')
            ->withBody((new StreamFactory())->createStream(http_build_query(['ping' => 'pong'])))
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');

        $response = $this->app->runAndGetResponse($server_request);

        $this->assertJson((string)$response->getBody());

        $response = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('ping', $response);
        $this->assertEquals('pong', $response['ping']);
    }

    public function testProcessApplicationXml()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('POST', 'https://example.com/to/post/and/check/xml')
            ->withBody((new StreamFactory())->createStream('<root><ping>pong</ping></root>'))
            ->withHeader('Content-Type', 'application/xml');

        $response = $this->app->runAndGetResponse($server_request);

        $this->assertJson((string)$response->getBody());

        $response = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('ping', $response);
        $this->assertEquals('pong', $response['ping']);
    }

    public function testProcessTextXml()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('POST', 'https://example.com/to/post/and/check/xml')
            ->withBody((new StreamFactory())->createStream('<root><ping>pong</ping></root>'))
            ->withHeader('Content-Type', 'application/xml');

        $response = $this->app->runAndGetResponse($server_request);

        $this->assertJson((string)$response->getBody());

        $response = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('ping', $response);
        $this->assertEquals('pong', $response['ping']);
    }
}

<?php

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;

it('can process JSON', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('POST', 'https://example.com/to/post/and/check/json')
        ->withBody((new StreamFactory())->createStream(json_encode(['ping' => 'pong'])))
        ->withHeader('Content-Type', 'application/json');

    $response = $this->app->runAndGetResponse($server_request);

    expect(json_decode((string)$response->getBody(), true))
        ->toBeArray()
        ->toMatchArray(['ping' => 'pong']);
});

it('can process URL Encoded', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('POST', 'https://example.com/to/post/and/check/urlencoded')
        ->withBody((new StreamFactory())->createStream(http_build_query(['ping' => 'pong'])))
        ->withHeader('Content-Type', 'application/x-www-form-urlencoded');

    $response = $this->app->runAndGetResponse($server_request);

    expect(json_decode((string)$response->getBody(), true))
        ->toBeArray()
        ->toMatchArray(['ping' => 'pong']);
});

it('can process XML (application)', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('POST', 'https://example.com/to/post/and/check/xml')
        ->withBody((new StreamFactory())->createStream('<root><ping>pong</ping></root>'))
        ->withHeader('Content-Type', 'application/xml');

    $response = $this->app->runAndGetResponse($server_request);

    expect(json_decode((string)$response->getBody(), true))
        ->toBeArray()
        ->toMatchArray(['ping' => 'pong']);
});

it('can process XML (text)', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('POST', 'https://example.com/to/post/and/check/xml')
        ->withBody((new StreamFactory())->createStream('<root><ping>pong</ping></root>'))
        ->withHeader('Content-Type', 'text/xml');

    $response = $this->app->runAndGetResponse($server_request);

    expect(json_decode((string)$response->getBody(), true))
        ->toBeArray()
        ->toMatchArray(['ping' => 'pong']);
});

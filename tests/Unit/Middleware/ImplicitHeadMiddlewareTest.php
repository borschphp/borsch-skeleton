<?php

use AppTest\Mock\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

it('can process with GET equivalent', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('HEAD', 'https://example.com/to/dispatch');

    $response = $this->app->runAndGetResponse($server_request);

    // Should return the equivalent GET response
    // FastRoute already deals with HEAD and GET matching
    // https://github.com/nikic/FastRoute#a-note-on-head-requests
    expect((string)$response->getBody())
        ->toEqual(TestHandler::class.'::handle');
});

it('can process without GET equivalent', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('HEAD', 'https://example.com/to/head/without/post');

    $response = $this->app->runAndGetResponse($server_request);

    // Should return not allowed (MethodNotAllowedMiddleware)
    expect((string)$response->getBody())->not()->toEqual(TestHandler::class.'::handle')
        ->and($response->getStatusCode())->toEqual(405);
});

it('can process with HEAD route', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('HEAD', 'https://example.com/to/head');

    $response = $this->app->runAndGetResponse($server_request);

    expect((string)$response->getBody())->toEqual(TestHandler::class.'::handle');
});

<?php

use AppTest\Mock\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

it('skip process when method is allowed', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('GET', 'https://example.com/to/dispatch');
    $response = $this->app->runAndGetResponse($server_request);

    expect($response->getStatusCode())->toBe(200)
        ->and((string)$response->getBody())->toEqual(TestHandler::class.'::handle');
});

it('process when method is not allowed', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('PUT', 'https://example.com/to/dispatch');
    /** @var \Psr\Http\Message\ResponseInterface $response */
    $response = $this->app->runAndGetResponse($server_request);

    expect($response->getStatusCode())->toBe(405)
        ->and((string)$response->getBody())->toBeEmpty()
        ->and($response->getHeaderLine('Allow'))->toEqual('GET');
});

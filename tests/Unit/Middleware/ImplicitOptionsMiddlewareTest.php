<?php

use AppTest\Mock\TestHandler;
use Laminas\Diactoros\ServerRequestFactory;

it('can process with OPTION route', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('OPTIONS', 'https://example.com/to/options');
    $response = $this->app->runAndGetResponse($server_request);

    expect($response->getStatusCode())->toBe(200)
        ->and((string)$response->getBody())->toEqual(TestHandler::class.'::handle');
});

// TODO

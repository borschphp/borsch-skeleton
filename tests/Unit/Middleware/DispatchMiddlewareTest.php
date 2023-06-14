<?php

use AppTest\Mock\TestHandler;

it('can process an existing route', function () {
    $response = $this->app->runAndGetResponse($this->server_request);
    expect((string)$response->getBody())->toBe(TestHandler::class.'::handle');
});

it('can process non existing route and return not found', function () {
    $response = $this->app->runAndGetResponse($this->server_request_not_found);
    expect((string)$response->getBody())->not()->toBe(TestHandler::class.'::handle')
        ->and($response->getStatusCode())->toBe(404);
});

<?php

use Laminas\Diactoros\ServerRequestFactory;

it('process GET route', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('GET', 'https://example.com/to/route/result');
    $response = $this->app->runAndGetResponse($server_request);

    expect($response->getStatusCode())->toBe(200)
        ->and(json_decode((string)$response->getBody(), true))
        ->toHaveKey('route_result_is_success')
        ->toMatchArray(['route_result_is_success' => true]);
});

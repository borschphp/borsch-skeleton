<?php

use Laminas\Diactoros\ServerRequestFactory;

it('process when path does not exist', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('GET', 'https://example.com/to/an/unknown/path');
    $response = $this->app->runAndGetResponse($server_request);

    expect($response->getStatusCode())->toBe(404);
});

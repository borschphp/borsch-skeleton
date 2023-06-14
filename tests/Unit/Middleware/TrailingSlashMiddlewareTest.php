<?php

use Laminas\Diactoros\ServerRequestFactory;

it('removes trailing slash and redirect to non-slashed uri', function () {
    $server_request = (new ServerRequestFactory())
        ->createServerRequest('GET', 'https://example.com/to/dispatch/');
    $response = $this->app->runAndGetResponse($server_request);

    expect($response->getStatusCode())->toBe(301)
        ->and(substr($response->getHeaderLine('Location'), -12))->toEqual('/to/dispatch');
});

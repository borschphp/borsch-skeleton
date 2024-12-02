<?php

use App\Middleware\ErrorHandlerMiddleware;

it('can process valid listener', function () {
    $this->app->runAndGetResponse($this->server_request_to_exception);

    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'App.CRITICAL: GET https://example.com/to/exception => Pest for testz!'
        );
});

it('cannot add invalid listener', function () {
    $this->app->getContainer()->get(ErrorHandlerMiddleware::class)->addListener(null);
})->throws(TypeError::class);

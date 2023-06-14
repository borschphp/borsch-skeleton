<?php

use AppTest\Mock\TestHandler;

test('content length is correct', function () {
    $response = $this->app->runAndGetResponse($this->server_request);
    expect(TestHandler::class.'::handle')
        ->toHaveLength((int)$response->getHeaderLine('Content-Length'));
});

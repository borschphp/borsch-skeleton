<?php

it('can be invoked', function () {
    ($this->listener)(new InvalidArgumentException('Not Found', 404), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.CRITICAL: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_ERROR', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_ERROR), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.ERROR: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_RECOVERABLE_ERROR', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_RECOVERABLE_ERROR), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.ERROR: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_CORE_ERROR', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_RECOVERABLE_ERROR), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.ERROR: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_COMPILE_ERROR', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_RECOVERABLE_ERROR), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.ERROR: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_USER_ERROR', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_USER_ERROR), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.ERROR: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_PARSE', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_PARSE), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.ERROR: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_WARNING', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_WARNING), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.WARNING: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_USER_WARNING', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_USER_WARNING), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.WARNING: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_CORE_WARNING', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_CORE_WARNING), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.WARNING: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_COMPILE_WARNING', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_COMPILE_WARNING), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.WARNING: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_NOTICE', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_NOTICE), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.NOTICE: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_USER_NOTICE', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_USER_NOTICE), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.NOTICE: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_STRICT', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_STRICT), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.INFO: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_DEPRECATED', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_DEPRECATED), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.INFO: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with severity E_USER_DEPRECATED', function () {
    ($this->listener)(new ErrorException('Not Found', 404, E_USER_DEPRECATED), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.INFO: GET https://example.com/to/dispatch => Not Found'
        );
});

it('can handle error exception with default DEBUG severity', function () {
    ($this->listener)(new ErrorException('Not Found', 404, 9999999), $this->server_request);
    expect($this->log_file)->toBeFile()
        ->and(file_get_contents($this->log_file))->toContain(
            'Borsch.DEBUG: GET https://example.com/to/dispatch => Not Found'
        );
});

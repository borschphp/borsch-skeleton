<?php

namespace AppTest\Listener;

use App\Listener\MonologListener;
use AppTest\App;
use ErrorException;
use InvalidArgumentException;
use Laminas\Diactoros\ServerRequestFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MonologListenerTest extends App
{

    public function __destruct()
    {
        $file = sprintf('%s/%s.log', __DIR__, 'app');
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    public function tearDown(): void
    {
        $file = sprintf('%s/%s.log', __DIR__, 'app');
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    public function testInvocation()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new InvalidArgumentException('Not Found', 404), $server_request);

        $this->assertFileExists($log_file);
    }

    public function testHandleErrorExceptionSeverityError()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_ERROR), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.error', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityRecoverableError()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_RECOVERABLE_ERROR), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.error', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityCoreError()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_CORE_ERROR), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.error', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityCompileError()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_COMPILE_ERROR), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.error', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityUserError()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_USER_ERROR), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.error', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityParseError()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_PARSE), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.error', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityWarning()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_WARNING), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.warning', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityUserWarning()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_USER_WARNING), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.warning', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityCoreWarning()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_CORE_WARNING), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.warning', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityCompileWarning()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_COMPILE_WARNING), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.warning', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityNotice()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_NOTICE), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.notice', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityUserNotice()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_USER_NOTICE), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.notice', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityInfo()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_STRICT), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.info', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityDeprecatedInfo()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_DEPRECATED), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.info', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityUserDeprecatedInfo()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, E_USER_DEPRECATED), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.info', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }

    public function testHandleErrorExceptionSeverityDefault()
    {
        $server_request = (new ServerRequestFactory())
            ->createServerRequest('GET', 'https://example.com/to/dispatch');

        $log_file = sprintf('%s/%s.log', __DIR__, 'app');
        $logger = new Logger('Borsch');
        $logger->pushHandler(new StreamHandler($log_file));

        $listener = new MonologListener($logger);
        $listener(new ErrorException('Not Found', 404, 9999999), $server_request);

        $log = file_get_contents($log_file);

        $this->assertFileExists($log_file);
        $this->assertStringContainsStringIgnoringCase('borsch.debug', $log);
        $this->assertStringContainsStringIgnoringCase('not found', $log);
    }
}

<?php

namespace App\Listener;

use Borsch\Middleware\ErrorHandlerMiddleware;
use ErrorException;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Class MonologListener
 *
 * This listener is used to log errors in the ErrorHandlerMiddleware.
 * It is instantiated in the config/container.php file, in the ErrorHandlerMiddleware::class definition.
 *
 * Feel free to modify it according to your needs, or copy then use it as a skeleton for your own listener.
 * Do not forget to add your listener in the ErrorHandlerMiddleware::class definition in config/container.php.
 *
 * @package App\Listener
 * @see ErrorHandlerMiddleware
 */
class MonologListener
{

    /**
     * @param Logger $logger
     */
    public function __construct(
        protected Logger $logger
    ) {}

    /**
     * @param Throwable $throwable
     * @param ServerRequestInterface $request
     */
    public function __invoke(Throwable $throwable, ServerRequestInterface $request): void
    {
        if ($throwable instanceof ErrorException) {
            $this->handleErrorException($throwable, $request);
            return;
        }

        $this->logger->critical($this->formatLog($throwable, $request));
    }

    /**
     * @param ErrorException $exception
     * @param ServerRequestInterface $request
     */
    protected function handleErrorException(ErrorException $exception, ServerRequestInterface $request): void
    {
        $log = $this->formatLog($exception, $request);
        $severity = $exception->getSeverity();

        if ($this->isError($severity)) {
            $this->logger->error($log);
        } elseif ($this->isWarning($severity)) {
            $this->logger->warning($log);
        } elseif ($this->isNotice($severity)) {
            $this->logger->notice($log);
        } elseif ($this->isInformation($severity)) {
            $this->logger->info($log);
        } else {
            $this->logger->debug($log);
        }
    }

    /**
     * @param Throwable $throwable
     * @param ServerRequestInterface $request
     * @return string
     */
    protected function formatLog(Throwable $throwable, ServerRequestInterface $request): string
    {
        return sprintf(
            '%s %s => %s Stacktrace: %s',
            $request->getMethod(),
            (string)$request->getUri(),
            $throwable->getMessage(),
            $throwable->getTraceAsString()
        );
    }

    /**
     * @param int $code
     * @return bool
     */
    protected function isError(int $code): bool
    {
        return in_array($code, [
            E_ERROR,
            E_RECOVERABLE_ERROR,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
            E_PARSE
        ]);
    }

    /**
     * @param int $code
     * @return bool
     */
    protected function isWarning(int $code): bool
    {
        return in_array($code, [
            E_WARNING,
            E_USER_WARNING,
            E_CORE_WARNING,
            E_COMPILE_WARNING
        ]);
    }

    /**
     * @param int $code
     * @return bool
     */
    protected function isNotice(int $code): bool
    {
        return in_array($code, [
            E_NOTICE,
            E_USER_NOTICE
        ]);
    }

    /**
     * @param int $code
     * @return bool
     */
    protected function isInformation(int $code): bool
    {
        // If PHP version is 8.4 then do not include E_STRICT because it is deprecated (throws exception)
        if (version_compare(PHP_VERSION, '8.4', '>=')) {
            return in_array($code, [
                E_DEPRECATED,
                E_USER_DEPRECATED
            ]);
        }

        return in_array($code, [
            E_STRICT,
            E_DEPRECATED,
            E_USER_DEPRECATED
        ]);
    }
}

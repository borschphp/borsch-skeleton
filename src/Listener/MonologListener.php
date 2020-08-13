<?php
/**
 * @author debuss-a
 */

namespace App\Listener;

use App\Middleware\ErrorHandlerMiddleware;
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

    /** @var Logger */
    protected $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Throwable $throwable
     * @param ServerRequestInterface $request
     */
    public function __invoke(Throwable $throwable, ServerRequestInterface $request)
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
    protected function handleErrorException(ErrorException $exception, ServerRequestInterface $request)
    {
        $log = $this->formatLog($exception, $request);

        switch ($exception->getSeverity()) {
            case E_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_PARSE:
                $this->logger->error($log);
                break;

            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                $this->logger->warning($log);
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $this->logger->notice($log);
                break;

            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $this->logger->info($log);
                break;

            default:
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
            '%s %s => %s%s%s',
            $request->getMethod(),
            (string)$request->getUri(),
            $throwable->getMessage(),
            PHP_EOL,
            $throwable->getTraceAsString()
        );
    }
}

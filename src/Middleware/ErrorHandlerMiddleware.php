<?php

namespace App\Middleware;

use App\Formatter\HtmlExplicitDevelopmentFormatter;
use League\BooBoo\BooBoo;
use League\BooBoo\Exception\NoFormattersRegisteredException;
use League\BooBoo\Formatter\NullFormatter;
use League\BooBoo\Handler\LogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ErrorHandlerMiddleware
 *
 * It uses the league/booboo package to deal with error.
 *
 * @package App\Middleware
 * @see https://booboo.thephpleague.com/
 */
class ErrorHandlerMiddleware implements MiddlewareInterface
{

    /** @var BooBoo */
    protected $booboo;

    /**
     * ErrorHandlerMiddleware constructor.
     */
    public function __construct()
    {
        $this->booboo = new BooBoo([]);
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // All errors of warning or higher show in the browser,
        // but errors that are below this level to be ignored.
        $html = new HtmlExplicitDevelopmentFormatter();
        $html->setErrorLimit(E_ERROR|E_WARNING|E_USER_ERROR|E_USER_WARNING);

        $null = new NullFormatter();

        $this->booboo->pushFormatter($null);
        $this->booboo->pushFormatter($html);

        // To designate an error-formatter for use on production systems
        // Should be commented on production, use only if really needed.
        $this->booboo->setErrorPageFormatter($html);

        // Log Handler
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler(__DIR__.'/../../storage/logs/app.log'));

        $logger = new LogHandler($log);
        $this->booboo->pushHandler($logger);

        // Register BooBoo as the error handler.
        try {
            $this->booboo->register();
        } catch (NoFormattersRegisteredException $e) {
        }

        return $handler->handle($request);
    }
}

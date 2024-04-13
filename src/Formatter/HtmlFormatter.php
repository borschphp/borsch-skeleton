<?php

namespace App\Formatter;

use App\Template\LatteEngine;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

/**
 * Class HtmlFormatter
 * @package App\Formatter
 */
class HtmlFormatter
{

    public function __construct()
    {
    }

    public function __invoke(ResponseInterface $response, Throwable $throwable): ResponseInterface
    {
        $body = isProduction() ?
            $this->getTemplateEngineHandledException($throwable) :
            $this->getWhoopsHandledException($throwable);

        $response->getBody()->write($body);

        return $response
            ->withHeader('Content-Type', 'text/html');
    }

    protected function getTemplateEngineHandledException(Throwable $throwable): string
    {
        return (new LatteEngine())->render('500.tpl');
    }

    protected function getWhoopsHandledException(Throwable $throwable): string
    {
        $whoops = new Whoops();
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new PrettyPageHandler);

        return $whoops->handleException($throwable);
    }
}

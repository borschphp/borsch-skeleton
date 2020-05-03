<?php

namespace App\Formatter;

use League\BooBoo\Formatter\AbstractFormatter;
use Throwable;

/**
 * Class HtmlExplicitDevelopmentFormatter
 *
 * An error formatter similar to the Slim Framework one.
 *
 * @package App\Formatter
 * @see http://www.slimframework.com/
 */
class HtmlExplicitDevelopmentFormatter extends AbstractFormatter
{

    /**
     * @param Throwable $e
     * @return string
     */
    public function format($e)
    {
        $html = '<style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                font-family: monospace;
                font-size: 11px;
            }
            h1 {margin: 0 auto}
            h3 {text-decoration: underline}
            .content {padding: 15px;}
            .details, .trace {white-space: pre;}
            .trace {line-height: 1}
        </style>';
        $html .= '<div class="content">';
        $html .= '<h1>Borsch Application Error</h1>';
        $html .= '<p>The application could not run because of the following error:</p>';

        $html .= '<h3>Details</h3>';
        $html .= '<div class="details">';
        $html .= sprintf('<strong>Type:   </strong> %s<br>', get_class($e));
        $html .= sprintf('<strong>Message:</strong> %s<br>', $e->getMessage());
        $html .= sprintf('<strong>File:   </strong> %s<br>', $e->getFile());
        $html .= sprintf('<strong>Line:   </strong> %s<br>', $e->getLine());
        $html .= '</div>';

        $html .= '<h3>Trace</h3>';
        $root_dir = dirname(dirname(dirname(__FILE__)));
        $trace = str_replace($root_dir, '', nl2br($e->getTraceAsString()));
        $trace = preg_replace('/(\([0-9]+\): )(.+)/', '$1<br>&#8594; $2<br>', $trace);
        $html .= sprintf('<div class="trace">%s</div>', $trace);

        $html .= '</div>';

       return $html;
    }
}

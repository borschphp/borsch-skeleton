<?php

namespace PHPSTORM_META
{
    override(\Psr\Container\ContainerInterface::get(0), map([
        '' => '@',
    ]));

    override(\Psr\Http\Message\ServerRequestInterface::getAttribute(0), map([
        '' => '@',
    ]));

}

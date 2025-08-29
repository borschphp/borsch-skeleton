<?php

namespace Infrastructure\Mapper;

use Domain\Model\Model;

interface MapperInterface
{

    public function map(iterable $object): Model;
}

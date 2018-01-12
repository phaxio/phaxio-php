<?php

namespace Phaxio;

abstract class AbstractResource extends \ArrayObject
{
    protected $phaxio;

    public function __construct($phaxio, $data = array())
    {
        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        $this->phaxio = $phaxio;
        $this->exchangeArray($data);
    }
}

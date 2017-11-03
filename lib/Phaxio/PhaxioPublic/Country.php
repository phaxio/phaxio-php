<?php

namespace Phaxio\PhaxioPublic;

class Country extends \ArrayObject
{
    private $phaxio;

    public function __construct($phaxio, $data = array())
    {
        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        $this->phaxio = $phaxio;
        $this->exchangeArray($data);
    }
}

<?php

namespace Phaxio;

class PhaxCodes
{
    private $phaxio;

    public function __construct($phaxio)
    {
        $this->phaxio = $phaxio;
    }

    public function create($params) {
        return PhaxCode::create($this->phaxio, $params);
    }

    public function init($identifier) {
        return PhaxCode::init($this->phaxio, $identifier);
    }
}

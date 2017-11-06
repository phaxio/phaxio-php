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

    public function retrieve($identifier) {
        return PhaxCode::retrieve($this->phaxio, $identifier);
    }
}

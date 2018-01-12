<?php

namespace Phaxio;

class Account
{
    private $phaxio;

    public function __construct($phaxio)
    {
        $this->phaxio = $phaxio;
    }

    public function getStatus() {
        return Account\Status::init($this->phaxio);
    }
}

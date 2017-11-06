<?php

namespace Phaxio;

class Account
{
    private $phaxio;

    public function __construct($phaxio)
    {
        $this->phaxio = $phaxio;
    }

    public function status() {
        return Account\Status::retrieve($this->phaxio);
    }
}

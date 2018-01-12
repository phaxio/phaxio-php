<?php

namespace Phaxio;

class PhaxioPublic
{
    private $phaxio;

    public function __construct($phaxio)
    {
        $this->phaxio = $phaxio;
    }

    public function areaCodes() {
        return new PhaxioPublic\AreaCodes($this->phaxio);
    }

    public function countries() {
        return new PhaxioPublic\Countries($this->phaxio);
    }
}

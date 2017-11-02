<?php

namespace Phaxio;

class PhoneNumbers
{
    private $phaxio;

    public function __construct($phaxio)
    {
        $this->phaxio = $phaxio;
    }

    public function create($params) {
        return PhoneNumber::create($this->phaxio, $params);
    }

    public function retrieve($phone_number) {
        return PhoneNumber::retrieve($this->phaxio, $phone_number);
    }

    public function getList($params = array()) {
        return new PhoneNumberCollection($this->phaxio, $params);
    }
}

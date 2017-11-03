<?php

namespace Phaxio;

class PhoneNumbers extends Resources
{
    protected $collection_class = 'PhoneNumberCollection';

    public function create($params) {
        return PhoneNumber::create($this->phaxio, $params);
    }

    public function retrieve($phone_number) {
        return PhoneNumber::retrieve($this->phaxio, $phone_number);
    }
}

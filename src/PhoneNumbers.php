<?php

namespace Phaxio;

class PhoneNumbers extends AbstractResources
{
    protected $collection_class = 'PhoneNumberCollection';

    public function create($params) {
        return PhoneNumber::create($this->phaxio, $params);
    }

    public function init($phone_number) {
        return PhoneNumber::init($this->phaxio, $phone_number);
    }
}

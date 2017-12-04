<?php

namespace Phaxio;

class PhoneNumber extends AbstractResource
{
    public static function create($phaxio, $params) {
        $phone_number = new self($phaxio);
        return $phone_number->_create($params);
    }

    public static function init($phaxio, $phone_number) {
        return new self($phaxio, array('phone_number' => $phone_number));
    }

    private function _create($params) {
        if (isset($this->phone_number)) throw new Exception("PhoneNumber #{$this->phone_number} already created");

        $result = $this->phaxio->doRequest('POST', 'phone_numbers', $params);
        $this->phone_number = $result->getData()['phone_number'];

        return $this;
    }

    public function retrieve() {
        if (!isset($this->phone_number)) throw new Exception("Must set phone_number before getting PhoneNumber");

        $result = $this->phaxio->doRequest("GET", 'phone_numbers/' . urlencode($this->phone_number));
        $this->exchangeArray($result->getData());

        return $this;
    }

    public function delete() {
        $result = $this->phaxio->doRequest("DELETE", 'phone_numbers/' . urlencode($this->phone_number));

        return true;
    }
}

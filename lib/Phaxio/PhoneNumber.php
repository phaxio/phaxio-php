<?php

namespace Phaxio;

class PhoneNumber extends \ArrayObject
{
    private $phaxio;

    public function __construct($phaxio, $data = array())
    {
        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        $this->phaxio = $phaxio;
        $this->exchangeArray($data);
    }

    public static function create($phaxio, $params) {
        $fax = new self($phaxio);
        return $fax->_create($params);
    }

    public static function retrieve($phaxio, $phone_number) {
        $fax = new self($phaxio, array('phone_number' => $phone_number));
        return $fax->refresh();
    }

    private function _create($params) {
        if (isset($this->phone_number)) throw new PhaxioException("PhoneNumber #{$this->phone_number} already created");

        $result = $this->phaxio->doRequest('POST', 'phone_numbers', $params);
        $this->phone_number = $result->getData()['phone_number'];

        return $this;
    }

    public function refresh() {
        if (!isset($this->phone_number)) throw new PhaxioException("Must set phone_number before getting PhoneNumber");

        $result = $this->phaxio->doRequest("GET", 'phone_numbers/' . urlencode($this->phone_number));
        $this->exchangeArray($result->getData());

        return $this;
    }

    public function delete() {
        $result = $this->phaxio->doRequest("DELETE", 'phone_numbers/' . urlencode($this->phone_number));

        return $result;
    }
}

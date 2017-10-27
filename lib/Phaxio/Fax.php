<?php

namespace Phaxio;

class Fax implements \ArrayAccess
{
    private $id;
    private $data;
    private $phaxio;

    public function __construct($phaxio, $id = null)
    {
        $this->phaxio = $phaxio;
        $this->id = $id;
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public static function create($phaxio, $params) {
        $fax = new self($phaxio);
        return $fax->_create($params);
    }

    public static function retrieve($phaxio, $id) {
        $fax = new self($phaxio, $id);
        return $fax->get();
    }

    public function _create($params) {
        if ($this->id) throw new PhaxioException("Fax #$id already created");

        $result = $this->phaxio->doRequest('POST', 'faxes', $params);
        $this->id = $result->getData()['id'];

        return $this;
    }

    public function get() {
        if (!$this->id) throw new PhaxioException("Must set ID before getting fax");

        $result = $this->phaxio->doRequest("GET", 'faxes/' . urlencode($this->id));
        $this->data = $result->getData();

        return $this;
    }
}

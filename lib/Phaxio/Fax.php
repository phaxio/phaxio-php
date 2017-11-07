<?php

namespace Phaxio;

class Fax extends AbstractResource
{
    public static function create($phaxio, $params) {
        $fax = new self($phaxio);
        return $fax->_create($params);
    }

    public static function init($phaxio, $id) {
        return new self($phaxio, array('id' => $id));
    }

    private function _create($params) {
        if (isset($this->id)) throw new Exception("Fax #{$this->id} already created");

        $result = $this->phaxio->doRequest('POST', 'faxes', $params);
        $this->id = $result->getData()['id'];

        return $this;
    }

    public function retrieve() {
        if (!isset($this->id)) throw new Exception("Must set ID before getting fax");

        $result = $this->phaxio->doRequest("GET", 'faxes/' . urlencode($this->id));
        $this->exchangeArray($result->getData());

        return $this;
    }

    public function cancel() {
        $result = $this->phaxio->doRequest("POST", 'faxes/' . urlencode($this->id) . "/cancel");

        return true;
    }

    public function resend($params = array()) {
        $result = $this->phaxio->doRequest("POST", 'faxes/' . urlencode($this->id) . "/resend", $params);

        return self::init($this->phaxio, $result->getData()['id']);
    }

    public function delete() {
        $result = $this->phaxio->doRequest("DELETE", 'faxes/' . urlencode($this->id));

        return true;
    }

    public function getFile($params = array()) {
        return new Fax\File($this->phaxio, $this->id, $params);
    }
}

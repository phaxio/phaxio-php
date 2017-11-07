<?php

namespace Phaxio;

class PhaxCode extends AbstractResource
{
    public static function create($phaxio, $params) {
        $phax_code = new self($phaxio);
        return $phax_code->_create($params);
    }

    public static function init($phaxio, $identifier = null) {
        return new self($phaxio, array('identifier' => $identifier));
    }

    private function _create($params) {
        if (isset($this->identifier)) throw new Exception("PhaxCode #{$this->identifier} already created");

        $result = $this->phaxio->doRequest('POST', 'phax_codes', $params);
        $this->identifier = $result->getData()['identifier'];

        return $this;
    }

    public function retrieve($getMetadata = false) {
        $format = ($getMetadata ? ".json" : ".png");

        if ($this->identifier === null) {
            $result = $this->phaxio->doRequest("GET", 'phax_code' . $format, array(), $getMetadata);
        } else {
            $result = $this->phaxio->doRequest("GET", 'phax_codes/' . urlencode($this->identifier) . $format, array(), $getMetadata);
        }

        if ($getMetadata) {
            $this->exchangeArray(array_merge($this->getArrayCopy(), $result->getData()));
        } else {
            $this->exchangeArray(array_merge($this->getArrayCopy(), $result));
        }

        return $this;
    }
}

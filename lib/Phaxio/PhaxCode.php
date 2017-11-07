<?php

namespace Phaxio;

class PhaxCode extends AbstractResource
{
    public static function create($phaxio, $params) {
        $phax_code = new self($phaxio);
        return $phax_code->_create($params);
    }

    public static function retrieve($phaxio, $identifier = null) {
        $phax_code = new self($phaxio, array('identifier' => $identifier));
        return $phax_code->refresh();
    }

    private function _create($params) {
        if (isset($this->identifier)) throw new Exception("PhaxCode #{$this->identifier} already created");

        $result = $this->phaxio->doRequest('POST', 'phax_codes', $params);
        $this->identifier = $result->getData()['identifier'];

        return $this;
    }

    public function getBarcode() {
        $this->refresh(".png");
        return $this->bytes;
    }

    public function refresh($getBarcode = false) {
        $format = ($getBarcode ? ".png" : ".json");

        if ($this->identifier === null) {
            $result = $this->phaxio->doRequest("GET", 'phax_code' . $format, array(), !$getBarcode);
        } else {
            $result = $this->phaxio->doRequest("GET", 'phax_codes/' . urlencode($this->identifier) . $format, array(), !$getBarcode);
        }

        if ($getBarcode) {
            $this->bytes = $result['body'];
        } else {
            $this->exchangeArray($result->getData());
        }

        return $this;
    }
}

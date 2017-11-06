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
        if (isset($this->identifier)) throw new PhaxioException("PhaxCode #{$this->identifier} already created");

        $result = $this->phaxio->doRequest('POST', 'phax_codes', $params);
        $this->identifier = $result->getData()['identifier'];

        return $this;
    }

    public function refresh() {
        if ($this->identifier === null) {
            $result = $this->phaxio->doRequest("GET", 'phax_code');
        } else {
            $result = $this->phaxio->doRequest("GET", 'phax_codes/' . urlencode($this->identifier));
        }

        $this->exchangeArray($result->getData());

        return $this;
    }
}

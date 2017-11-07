<?php

namespace Phaxio\Fax;

class File extends \ArrayObject
{
    private $phaxio;
    private $fax_id;

    public function __construct($phaxio, $fax_id) {
        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        $this->phaxio = $phaxio;
        $this->fax_id = $fax_id;
        $this->params = $params;
    }

    public function delete() {
        $this->phaxio->doRequest("DELETE", 'faxes/' . urlencode($this->fax_id) . '/file');

        return true;
    }

    public function retrieve($params = array()) {
        $result = $this->phaxio->doRequest("GET", 'faxes/' . urlencode($this->fax_id) . '/file', $params, false);
        $this->exchangeArray($result);

        return $this;
    }
}

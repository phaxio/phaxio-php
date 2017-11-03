<?php

namespace Phaxio\Fax;

class File
{
    private $phaxio;
    private $fax_id;
    private $params;
    private $cached_response;

    public function __construct($phaxio, $fax_id, $params) {
        $this->phaxio = $phaxio;
        $this->fax_id = $fax_id;
        $this->params = $params;
    }

    public function getBytes() {
        return $this->response()['body'];
    }

    public function getContentType() {
        return $this->response()['contentType'];
    }

    public function delete() {
        $this->phaxio->doRequest("DELETE", 'faxes/' . urlencode($this->fax_id) . '/file');

        return true;
    }

    private function response() {
        if (!isset($this->cached_response)) {
            $this->cached_response = $this->phaxio->doRequest("GET", 'faxes/' . urlencode($this->fax_id) . '/file', $this->params, false);
        }

        return $this->cached_response;
    }
}

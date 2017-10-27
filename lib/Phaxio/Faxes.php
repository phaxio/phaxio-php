<?php

namespace Phaxio;

class Faxes
{
    private $phaxio;

    public function __construct($phaxio)
    {
        $this->phaxio = $phaxio;
    }

    public function create($params) {
        return Fax::create($this->phaxio, $params);
    }

    public function retrieve($id) {
        return Fax::retrieve($this->phaxio, $id);
    }
}

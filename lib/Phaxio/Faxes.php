<?php

namespace Phaxio;

class Faxes extends AbstractResources
{
    protected $collection_class = 'FaxCollection';

    public function create($params) {
        return Fax::create($this->phaxio, $params);
    }

    public function retrieve($id) {
        return Fax::retrieve($this->phaxio, $id);
    }
}

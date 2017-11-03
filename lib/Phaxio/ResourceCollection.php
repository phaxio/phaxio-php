<?php

namespace Phaxio;

class ResourceCollection extends \ArrayObject
{
    protected $phaxio;
    protected $resource;
    protected $resource_class;
    private $params;
    private $total;

    public function __construct($phaxio, $params) {
        $this->phaxio = $phaxio;
        $this->params = $params;

        $this->retrievePage();

        return $this;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getPage() {
        return $this->params['page'];
    }

    public function getPerPage() {
        return $this->params['per_page'];
    }

    private function retrievePage() {
        $results = $this->phaxio->doRequest("GET", $this->resource, $this->params);

        $this->total = $results->getPaging()['total'];
        $this->params['page'] = $results->getPaging()['page'];
        $this->params['per_page'] = $results->getPaging()['per_page'];

        foreach ($results->getData() as $result) {
            $resource_class = "Phaxio\\{$this->resource_class}";
            $fax = new $resource_class($this->phaxio);
            $fax->exchangeArray($result);
            $this[] = $fax;
        }
    }
}

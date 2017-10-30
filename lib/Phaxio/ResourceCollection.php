<?php

namespace Phaxio;

class ResourceCollection extends \ArrayObject
{
    private $phaxio;
    protected $resource;
    protected $resource_class;
    private $params;
    private $per_page;
    private $page;
    private $total;

    public function __construct($phaxio, $params) {
        $this->phaxio = $phaxio;
        $this->params = $params;

        $this->retrievePage();

        return $this;
    }

    public function retrievePage() {
        $results = $this->phaxio->doRequest("GET", $this->resource, $this->params);

        foreach ($results->getData() as $result) {
            $resource_class = "Phaxio\\{$this->resource_class}";
            $fax = new $resource_class($this->phaxio);
            $fax->exchangeArray($result);
            $this[] = $fax;
        }
    }
}

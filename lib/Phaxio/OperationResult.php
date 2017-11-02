<?php

namespace Phaxio;

class OperationResult
{
    private $message = null;
    private $success = false;
    private $data = null;
    private $paging = null;

    public function __construct($success, $message = null, $data = null, $paging = null)
    {
        $this->success = $success;
        $this->message = $message;

        if ($data != null) {
            $this->data = $data;
        }

        if ($paging != null) {
            $this->paging = $paging;
        }
    }

    public function getPaging(){
        if (!isset($this->paging)){
            throw Exception("This API result has no paging information");
        }

        return $this->paging;
    }

    public function succeeded()
    {
        return $this->success;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessage()
    {
        return $this->message;
    }
}

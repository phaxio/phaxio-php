<?php

namespace Phaxio;

class PhaxioOperationResult
{
    private $message = null;
    private $success = false;
    private $data = null;

    public function __construct($success, $message = null, $data = null)
    {
        $this->success = $success;
        $this->message = $message;

        if ($data != null) {
            $this->data = $data;
        }
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

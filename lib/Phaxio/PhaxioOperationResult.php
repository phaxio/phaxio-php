<?php

namespace Phaxio;

class PhaxioOperationResult
{
    private $message = null;
    private $success = false;
    private $data = null;
    private $pagingData = null;

    public function __construct($success, $message = null, $data = null)
    {
        $this->success = $success;
        $this->message = $message;

        if ($data != null) {
            $this->data = $data;
        }
    }

    public function addPagingData(array $pagingData){
        $this->pagingData = $pagingData;
    }

    public function isPaged(){
        return $this->pagingData != null;
    }

    public function getPage(){
        if (!$this->isPaged()){
            throw Exception("This API result has no paging information");
        }

        return $this->pagingData['page'];
    }

    public function getTotalPages(){
        if (!$this->isPaged()){
            throw Exception("This API result has no paging information");
        }

        return $this->pagingData['total_pages'];
    }

    public function getMaxPerPage(){
        if (!$this->isPaged()){
            throw Exception("This API result has no paging information");
        }

        return $this->pagingData['max_per_page'];
    }

    public function getTotalResults(){
        if (!$this->isPaged()){
            throw Exception("This API result has no paging information");
        }

        return $this->pagingData['total_results'];
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

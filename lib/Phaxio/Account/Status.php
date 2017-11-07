<?php

namespace Phaxio\Account;

class Status extends \Phaxio\AbstractResource
{
    public static function init($phaxio) {
        return new self($phaxio);
    }

    public function retrieve() {
        $result = $this->phaxio->doRequest("GET", 'account/status');
        $this->exchangeArray($result->getData());

        return $this;
    }
}

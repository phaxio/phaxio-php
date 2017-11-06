<?php

namespace Phaxio\Account;

class Status extends \Phaxio\AbstractResource
{
    public static function retrieve($phaxio) {
        $phax_code = new self($phaxio);
        return $phax_code->refresh();
    }

    public function refresh() {
        $result = $this->phaxio->doRequest("GET", 'account/status');
        $this->exchangeArray($result->getData());

        return $this;
    }
}

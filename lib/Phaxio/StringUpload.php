<?php

namespace Phaxio;

class StringUpload
{
    public $string;
    public $extension;

    public function __construct($str, $ext) {
        $this->string = $str;
        $this->extension = $ext;
    }
}

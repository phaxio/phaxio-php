<?php

namespace Phaxio\PhaxioPublic;

class CountryCollection extends \Phaxio\AbstractResourceCollection
{
    protected $resource = 'public/countries';
    protected $resource_class = 'PhaxioPublic\\Country';
}

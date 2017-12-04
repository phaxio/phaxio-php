<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

$params = array(
    'to' => $toNumber,
    'file' => array(
        $phaxio->stringUpload('Page 1'),
        $phaxio->stringUpload('<b>Page 2<b>', 'html')
    )
);

$result = $phaxio->sendFax($params);
var_dump($result);


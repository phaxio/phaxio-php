<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$params = array(
    'to' => $toNumber,
    'content_url' => array(
        'https://www.phaxio.com',
        'https://www.example.com'
    )
);

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

$result = $phaxio->sendFax($params);
var_dump($result);


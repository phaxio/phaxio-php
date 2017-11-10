<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$params = array(
    'to' => $toNumber,
    'file' => array(
        # Use open file handles to upload files
        fopen('files/coverPage.html', 'r'),
        fopen('files/content.pdf', 'r')
    )
);

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

$result = $phaxio->sendFax($params);
var_dump($result);


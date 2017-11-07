<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$params = array(
    'to' => $toNumber,
    'file' => array(
        # Create files and put their names here prefixed with '@'
        '@files/coverPage.html',
        '@files/content.pdf'
    )
);

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

$result = $phaxio->sendFax($params);
var_dump($result);


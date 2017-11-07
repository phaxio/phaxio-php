<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$startTime = (new DateTime("-1 day"))->format(DateTime::RFC3339);

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

$result = $phaxio->listFaxes(array('created_before' => $startTime));
var_dump($result);

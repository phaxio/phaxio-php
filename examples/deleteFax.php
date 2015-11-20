<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->deleteFax(1234, true);
var_dump($result);

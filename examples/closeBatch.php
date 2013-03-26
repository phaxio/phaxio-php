<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

//enter a batchId to close
$batchId = 23;

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->closeBatch($batchId);
var_dump($result);

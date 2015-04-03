<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->faxCancel($faxId);
var_dump($result);

<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->sendFax($toNumber, array(), array('callback_url' => $customCallback, 'string_data' => $textData, 'string_data_type' => 'text'));
var_dump($result);

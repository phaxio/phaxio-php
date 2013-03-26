<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->sendFax($toNumber, array(), array('string_data' => $htmlData, 'string_data_type' => 'html'));
var_dump($result);

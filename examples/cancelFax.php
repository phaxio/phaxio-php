<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

# Fax must not be completed
$faxId = 48554;

$result = $phaxio->initFax($faxId)->cancel();
var_dump($result);

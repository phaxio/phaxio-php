<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

# Fax must be test fax and completed
$faxId = 1234;

$result = $phaxio->initFax($fax_id)->delete();
var_dump($result);

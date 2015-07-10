<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

# Create files and put their names here
$files = array(
	'files/coverPage.html',
	'files/content.pdf'
);

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->sendFax($toNumber, $files);
var_dump($result);


<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

$faxId = 1234;
$outfile = "/tmp/out.jpg";

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$phaxio->download($faxId, Phaxio::THUMBNAIL_SMALL, $outfile);

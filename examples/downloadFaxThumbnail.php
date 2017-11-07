<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$faxId = 1234;
$outfile = "/tmp/out.jpg";
$params = array('thumbnail' => 'l');

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

file_put_contents($outfile, $phaxio->retriveFaxFile($faxId, $params)->body);

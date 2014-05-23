<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

$endDate = time();
$startDate = strtotime("-1 week");

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->faxList($startDate, $endDate, array('number' => '18778275130'));
echo ("Retrieved page {$result->getPage()} of {$result->getTotalPages()} ({$result->getTotalResults()} results) \n");
var_dump($result);

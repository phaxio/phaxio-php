<?php

require_once('phaxio_config.php');
require_once('autoload.php');

use Phaxio\Phaxio;

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

for ($i = 1; $i <= 5; $i++){
    $result = $phaxio->sendFax($toNumber, array(), array('string_data' => "This is part $i of a 5 part fax batch.", 'string_data_type' => 'text', 'batch' => true));
    var_dump($result);
}

echo "Your batch has been created for $toNumber.  You can now see it in the Phaxio web UI.  To fire it, see fireBatch.php";

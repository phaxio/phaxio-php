<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

$params = array(
    'to' => $toNumber,
    'file' => array(
        # Create files and put their names here prefixed with '@'
        '@files/coverPage.html',
        '@files/content.pdf'
    ),
    'batch_delay' => 60
);

echo "Creating a 3 part batch with a one minute delay...\n";
for ($i = 1; $i <= 3; $i++){
    $result = $phaxio->sendFax($params);

    var_dump($result);
    echo "Part $i of 3 sent.  Sleeping for 5 seconds...\n";
    sleep(5);
}

echo "First batch created successfully.  Sleeping for 50 seconds and creating a new batch.  First batch will start to send. \n\n";
sleep(50);

echo "Creating a 3 part batch with a one minute delay...\n";
for ($i = 1; $i <= 3; $i++){
    $result = $phaxio->sendFax($params);

    var_dump($result);
    echo "Part $i of 3 sent.  Sleeping for 5 seconds...";
    sleep(5);
}
echo "Done.";

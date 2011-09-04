<?
require_once('phaxio_config.php');
require_once(dirname(__FILE__) . '/../lib/Phaxio.class.php');

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

echo "Creating a 3 part batch with a one minute delay...\n";
for ($i = 1; $i <= 3; $i++){
    $result = $phaxio->sendFax($toNumber, array(), array('string_data' => "This is part $i of a 3 part fax batch.  This is the first batch.",
        'string_data_type' => 'text', 'batch' => true, 'batch_delay' => 60));

    var_dump($result);
    echo "Part $i of 3 sent.  Sleeping for 5 seconds...\n";
    sleep(5);
}

echo "First batch created successfully.  Sleeping for 45 seconds and creating a new batch.  First batch will start to send. \n\n";
sleep(45);

echo "Creating a 3 part batch with a one minute delay...\n";
for ($i = 1; $i <= 3; $i++){
    $result = $phaxio->sendFax($toNumber, array(), array('string_data' => "This is part $i of a 3 part fax batch.  This is the second batch.",
        'string_data_type' => 'text', 'batch' => true, 'batch_delay' => 60));

    var_dump($result);
    echo "Part $i of 3 sent.  Sleeping for 5 seconds...";
    sleep(5);
}
echo "Done."

?>


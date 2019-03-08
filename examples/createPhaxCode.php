<?php

require_once('phaxio_config.php');
require_once('autoload.php');

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);

$params = array(
  'metadata' => "This example metadata will be associated with any fax received with the PhaxCode generated below."
);

$phax_code = $phaxio->phaxCodes()->create($params);
$phax_code->retrieve(); # Downloads the image

$filename = "PhaxCode-$phax_code[identifier].png";

file_put_contents($filename, $phax_code->body);

echo "Saved barcode to $filename\n";



<?
require_once('phaxio_config.php');
require_once(dirname(__FILE__) . '/../lib/PhaxioHelper.class.php');

//enter a batchId to close
$batchId = 23;

$phaxio = new PhaxioHelper($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->closeBatch($batchId);
var_dump($result);

?>


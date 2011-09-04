<?
require_once('phaxio_config.php');
require_once(dirname(__FILE__) . '/../lib/Phaxio.class.php');

//enter a batchId to fire
$batchId = 0;

$phaxio = new Phaxio($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->fireBatch($batchId);
var_dump($result);

?>


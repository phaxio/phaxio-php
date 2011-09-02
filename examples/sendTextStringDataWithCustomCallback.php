<?
require_once('phaxio_config.php');
require_once(dirname(__FILE__) . '/../lib/PhaxioHelper.class.php');

$phaxio = new PhaxioHelper($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->sendFax($toNumber, array(), array('callback_url' => $customCallback, 'string_data' => $textData, 'string_data_type' => 'text'));
var_dump($result);

?>


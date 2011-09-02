<?
require_once('phaxio_config.php');
require_once(dirname(__FILE__) . '/../lib/PhaxioHelper.class.php');

$phaxio = new PhaxioHelper($apiKeys[$apiMode], $apiSecrets[$apiMode], $apiHost);
$result = $phaxio->sendFax($toNumber, array(), array('string_data' => $htmlData, 'string_data_type' => 'html'));
var_dump($result);

?>


<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);
require 'vendor/autoload.php';

$agent = new \PhilKra\Agent([
	'appName' => 'app1',
	'serverUrl' => '{Fill In}',
	'apmVersion' => 'v1'
], [
	'user' => [
		'id' => 12345,
		'email' => 'test@example.com'
	]
]);

$trx_name = 'Demo Simple Transaction 3';
$agent->startTransaction($trx_name);
sleep(2);
$agent->stopTransaction($trx_name);
$agent->send();
die('Done');

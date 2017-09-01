<?php

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-control:no-cache,no-store,must-revalidate');
header('Pramga: no-cache');
header('Expires:0');
if (preg_match('/(Googlebot|Msnbot|YodaoBot|Sosospider|Baiduspider|Sogou web spider|gosospider|Huaweisymantecspider|Gigabot|OutfoxBot)/i', $_SERVER['HTTP_USER_AGENT']) || ($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/4.0')) {
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ($_SERVER['HTTP_USER_AGENT'] == '') {
	exit('NC');
}

require './config.php';
require_once LIB_PATH . '/kernel.php';
require APP_PATH . '/ad/jump.php';
$c = $_GET['c'];
$u = $_GET['u'];
$p = $_GET['p'];
$url = $_GET['t'];
$a = new jump();
if (($c == 'a') && $p && $url) {
	$a->run_tourl_zlink($u, $p, $url);
}
else {
	$a->Start();
	$a->run();
}

?>

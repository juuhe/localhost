<?php

require_once '../../config.php';
require_once LIB_PATH . '/init.php';
$sid = get('sid');
$cid = get('cid');
$rid = get('rid');

if ($rid) {
	$c_name = 'c_rid';
	$c_value = $rid;
}

if ($cid) {
	$c_name = 'c_cid';
	$c_value = $cid;
}

if ($sid) {
	$c_name = 'c_sid';
	$c_value = $sid;
}

setcookie($c_name, $c_value, TIMES + (86400 * 30), '/');
$url = url('index.register');
header('Location: ' . $url);

?>

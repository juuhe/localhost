<?php

define('WWW_ZYH_DIR', @$_SERVER['HTTP_HOST']);
define('WWW_ZYA_DIR', @$_SERVER['SERVER_ADDR'] ? @$_SERVER['SERVER_ADDR'] : @$_SERVER['LOCAL_ADDR']);
define('WWW_RZ_DIR', '5AF7EB75771ADAA391151EFE127C5588');
$am = explode('/', $_SERVER['SCRIPT_NAME']);

if (in_array('admin', $am)) {
	echo ' prohibited to use the "admin" directory  ';
	exit();
}

require_once '../config.php';
require_once dirname(dirname(__DIR__)) . '/library/init.php';
define('ADMIN_DEFAULT_CONTROLLER', 'admin/admin');
define('ISADMIN', true);
APP::run();

?>

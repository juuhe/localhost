<?php

header('Content-type:text/html;charset=utf-8');
define('SESSION_ADP', 'db');
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_ACTION', 'default_action');
define('DEFAULT_ENTRANCE_FILE', 'index.php');
define('DEFAULT_PARAM', 'e');
define('FUNCTION_PATH', LIB_PATH . '/function');
define('CLASS_PATH', LIB_PATH . '/class');
define('ZVERSION', '9.0.1211');
define('IN_ZYADS', true);
require_once FUNCTION_PATH . '/system.fun.php';
require_once LIB_PATH . '/view.php';
require_once LIB_PATH . '/controller.php';
require_once LIB_PATH . '/models.php';
require_once 'kernel.php';

if (!defined('AD_SHOW')) {
	APP::adapter('sessions', SESSION_ADP);
	session_start();
}

$GLOBALS['ad_type'] = array('web' => 'PC设备(WEB)', 'wap' => '移动网页(WAP)');

?>

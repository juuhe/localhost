<?php

final class APP
{
	static public function run()
	{
		if (WWW_RZ_DIR != '5AF7EB75771ADAA391151EFE127C5588') {
			exit('<h3>502 Bad Gateway</h3>');
		}

		APP::init();
		APP::_format_rewrite();
		APP::get_request();
	}

	static private function init()
	{
		spl_autoload_register(array('APP', 'my_autoload'));
		set_exception_handler(array('APP', 'my_exception'));
	}

	static private function get_request()
	{
		$m = '';

		if (!APP_REWRITE) {
			$m = request(DEFAULT_PARAM);
			if (!$m && !defined('ADMIN_DEFAULT_CONTROLLER')) {
				$m = DEFAULT_CONTROLLER;
			}

			if (!$m && (ISADMIN === true)) {
				$m = ADMIN_DEFAULT_CONTROLLER;
			}
		}
		else {
			$m = request(DEFAULT_PARAM);

			if (empty($m)) {
				$path = trim(server('PATH_INFO'), '/');

				if (empty($path)) {
					$path = server('REQUEST_URI');

					if (WEB_URL != '/') {
						$path = trim(str_replace(WEB_URL, '', $path), '/');
					}
					else {
						$path = substr($path, 1);
					}

					if (ISADMIN === true) {
						$path = substr(strstr(trim($path, '/'), '/'), 1);
					}
				}

				if (empty($path)) {
					$m = DEFAULT_CONTROLLER;

					if (ISADMIN === true) {
						$m = ADMIN_DEFAULT_CONTROLLER;
					}
				}
				else {
					preg_match('#^([a-z_][a-z0-9_\\./]*/|)([a-z0-9_]+)(?:\\.([a-z_][a-z0-9_]*))?(?:/|$)#sim', $path, $ma);
					$m = trim($ma[0], '/');
				}
			}
		}

		if (empty($m)) {
			$m = DEFAULT_CONTROLLER;

			if (ISADMIN === true) {
				$m = ADMIN_DEFAULT_CONTROLLER;
			}
		}

		APP::controller($m);
	}

	static public function format_url($route, $vars = NULL, $import = NULL)
	{
		$WEB_URL = APP::web_uri($route);
		$base_url = ($import ? $WEB_URL . $import : $WEB_URL . DEFAULT_ENTRANCE_FILE);

		if (strpos($route, '?') !== false) {
			$get = parse_url($route);

			if ($get['query']) {
				$route = $get['path'];
				parse_str($vars, $vars);
				parse_str($get['query'], $params);
				$vars = array_merge($params, $vars);
			}
		}

		if (!is_array($vars)) {
			if (!empty($vars)) {
				parse_str($vars, $vars);
			}
			else {
				$vars = array();
			}
		}

		$str = '';

		if (!empty($vars)) {
			$kv1 = array();

			foreach ($vars as $k => $v) {
				$kv1[] = $k . '=' . urlencode($v);
			}

			$str = (empty($kv1) ? '' : implode('&', $kv1));
		}

		if (!APP_REWRITE) {
			$str = ($str ? '&' . $str : '');
			$str = DEFAULT_PARAM . '=' . $route . $str;
			return $base_url . '?' . $str;
		}
		else {
			$str = ($str ? preg_replace('#(?:^|&)([a-z0-9_]+)=#sim', "/\x01-", $str) : '/');
			$str = (empty($vars) ? $WEB_URL . trim($route, '/ ') : $WEB_URL . trim($route, '/ ') . $str);
			return $str;
		}
	}

	static public function web_uri($route)
	{
		$name = server('SCRIPT_NAME');
		$path = pathinfo($name);
		$a = explode('/', $route);

		if ($a[0] != 'admin') {
			return WEB_URL;
		}

		$web_path = str_replace('\\', '/', $path['dirname']);
		$web_path = (empty($web_path) ? '/' : $web_path . '/');
		return $web_path;
	}

	static public function format_rote($route)
	{
		if (!$route) {
			return NULL;
		}

		$route = trim($route);
		$p = preg_match('#^([a-z_][a-z0-9_\\./]*/|[a-z_][a-z0-9_\\./]*,|)([a-z0-9_]+)(?:\\.([a-z_][a-z0-9_]*))?$#sim', $route, $r);

		if (!$p) {
			throw new Exception('pgRote Error');
		}

		if (empty($r[2])) {
			$r[2] = DEFAULT_CONTROLLER;
		}

		if (empty($r[3])) {
			$r[3] = DEFAULT_ACTION;
		}

		return $r;
	}

	static public function _format_rewrite()
	{
		if (APP_REWRITE) {
			$m = server('PATH_INFO');

			if (empty($m)) {
				$m = server('REQUEST_URI');
			}

			if ($m) {
				$s = trim($m, '/');

				if (preg_match_all('#/([a-z0-9_]+)-([^/]+)#sim', $s, $get)) {
					foreach ($get[1] as $i => $params) {
						if (isset($_GET[$params])) {
							continue;
						}

						$_GET[$params] = urldecode($get[2][$i]);
						$_REQUEST[$params] = urldecode($get[2][$i]);
					}
				}
			}
		}
	}

	static public function load_file($file, $mod = false)
	{
		static $loaded = array();

		if (isset($loaded[$file])) {
			return NULL;
		}

		if ($mod) {
			$f = (is_array($file) ? $file : APP::format_rote($file));
			$dirs = $f[1];
			$file = $f[2];

			if (!preg_match('/^[a-z_][a-z0-9_]*$/i', $file)) {
				throw new Exception('Filename error ');
			}

			if (!preg_match('/^[a-z_][a-z0-9_\\/,]*$/i', $dirs) && $dirs) {
				throw new Exception('dir error ');
			}

			if (($mod == 'tpl') && !$dirs) {
				$dirs = $GLOBALS['tpl'][RUN_CONTROLLER] . '/';
			}

			$d = array('ctl' => APP_PATH . '/controller/' . $dirs . $file . '.ctl.php', 'mod' => APP_PATH . '/models/' . $dirs . $file . '.mod.php', 'tpl' => TPL_DIR . '/' . $dirs . $file . '.tpl.php', 'func' => FUNCTION_PATH . '/' . $dirs . $file . '.fun.php', 'cls' => LIB_PATH . '/class/' . $dirs . $file . '.cls.php');
			$file = $d[$mod];
		}

		if (!is_file($file)) {
			throw new Exception('dir_file error ');
		}

		$loaded[$file] = true;
		return include_once $file;
	}

	static public function adapter($name, $type, $new = true)
	{
		$path = $name . '/' . $type . '_' . $name;

		if ($new) {
			return APP::load_class($path, 'adp');
		}
		else {
			$file_path = LIB_PATH . '/class/' . $path . '.adp.php';
			APP::load_file($file_path);
		}
	}

	static private function controller($route)
	{
		$c = APP::format_rote($route);
		$c[1] = str_replace('adv/', 'advertiser/', $c[1]);
		$c[1] = str_replace('aff/', 'affiliate/', $c[1]);
		$dirs = preg_replace('/[^a-z0-9_\\/]+/i', '', $c[1]);
		$class_name = $c[2];
		$fun = $c[3];
		define('RUN_CONTROLLER_DIR', $dirs);
		define('RUN_CONTROLLER', $dirs . $c[2]);
		define('RUN_CONTROLLER_CLASS', $class_name);
		define('RUN_ACTION', $fun);
		$file_path = APP_PATH . '/controller/' . $dirs . $class_name . '.ctl.php';

		if (!is_file($file_path)) {
			throw new Exception('Not ' . $class_name . ' controller');
		}

		APP::load_file($file_path);

		if (RUN_CONTROLLER_DIR == 'admin/') {
			if (WWW_ARZ_DIR != '006713EA795A324671690EDE7104E6FA') {
				exit('<h3>502 Bad Gateway A</h3>');
			}
		}

		if (defined('CUSTOM_APP_DIR')) {
			$z_class_name = 'z_' . $class_name;
			if (!class_exists($z_class_name) && file_exists($file_path = CUSTOM_APP_DIR . '/controller/' . $dirs . $class_name . '.ctl.php')) {
				APP::load_file($file_path);
				$class_name = $z_class_name;
			}
		}

		$class_name = $class_name . '_ctl';

		if (!class_exists($class_name)) {
			throw new Exception('Class Error ' . $class_name);
		}

		$obj = new $class_name();

		if (method_exists($obj, $fun)) {
			$ret = $obj->$fun();
		}
		else {
			throw new Exception('Can\'t find method');
		}
	}

	static public function load_mode($route)
	{
		static $_classes = array();
		$c = APP::format_rote($route);
		$dirs = $c[1];
		$mode_name = $c[2];
		$fun = $c[3];
		$id = $mode_name . $fun;
		if (isset($_classes[$id]) && is_object($_classes[$id])) {
			return $_classes[$id];
		}

		$file_path = APP_PATH . '/models/' . $dirs . $mode_name . '.mod.php';

		if (!is_file($file_path)) {
			throw new Exception('Not ' . $mode_name . ' mode');
		}

		APP::load_file($file_path);

		if (defined('CUSTOM_APP_DIR')) {
			$z_mode_name = 'z_' . $mode_name;
			if (!class_exists($z_mode_name) && file_exists($file_path = CUSTOM_APP_DIR . '/models/' . $dirs . $mode_name . '.mod.php')) {
				APP::load_file($file_path);
				$mode_name = $z_mode_name;
			}
		}

		$mode_name = $mode_name . '_mod';

		if (!class_exists($mode_name)) {
			throw new Exception('Class Error ' . $mode_name);
		}

		$obj = new $mode_name();
		$_classes[$id] = $obj;
		return $obj;
	}

	static public function load_class($route, $type = 'cls')
	{
		static $_classes = array();
		$c = APP::format_rote($route);
		$dirs = $c[1];
		$mode_name = $c[2];
		$fun = $c[3];
		$id = $mode_name . $fun;
		if (isset($_classes[$id]) && is_object($_classes[$id])) {
			return $_classes[$id];
		}

		$file_path = LIB_PATH . '/class/' . $dirs . $mode_name . '.' . $type . '.php';
		APP::load_file($file_path);

		if (!class_exists($mode_name)) {
			throw new Exception('Class Error ' . $mode_name);
		}

		$obj = new $mode_name();
		$_classes[$id] = $obj;
		return $obj;
	}

	static public function dr()
	{
		$arg = func_get_args();
		$route = array_shift($arg);
		$c = APP::format_rote($route);

		if ($c[3] == DEFAULT_ACTION) {
			$c[3] = '';
		}

		if (!class_exists('app_models')) {
			require_once LIB_PATH . '/models.php';
		}

		$obj = APP::load_mode($route);

		if (!method_exists($obj, $c[3])) {
			throw new Exception('DR#' . $c[2] . ' Method can not run->' . $c[3] . '()');
		}

		$data = call_user_func_array(array(&$obj, $c[3]), $arg);
		return $data;
	}

	static public function api()
	{
		$arg = func_get_args();
		$route = array_shift($arg);
		$c = APP::format_rote($route);

		if ($c[3] == DEFAULT_ACTION) {
			$c[3] = '';
		}

		$obj = APP::load_api($route);

		if (!method_exists($obj, $c[3])) {
			throw new Exception('API#' . $c[2] . ' Method can not run->' . $c[3] . '()');
		}

		$data = call_user_func_array(array(&$obj, $c[3]), $arg);
		return $data;
	}

	static public function load_api($route)
	{
		static $_classes = array();
		$c = APP::format_rote($route);
		$dirs = $c[1];
		$api_name = 'api_' . $c[2];
		$fun = $c[3];
		$id = $api_name . $fun;
		if (isset($_classes[$id]) && is_object($_classes[$id])) {
			return $_classes[$id];
		}

		$file_path = APP_PATH . '/api/' . $dirs . $api_name . '.php';
		APP::load_file($file_path);

		if (!class_exists($api_name)) {
			throw new Exception('Class Error ' . $api_name);
		}

		$obj = new $api_name();
		$_classes[$id] = $obj;
		return $obj;
	}

	static public function my_exception($e)
	{
		if ($e->getMessage() != '') {
			$out = 'error \'' . (string) $e->getMessage() . '\'';
		}

		if (APP_DEBUG) {
			$out .= ' ' . $e->getFile() . ':' . $e->getLine() . "\n\n";
			$out .= $e->getTraceAsString();
		}

		echo nl2br(htmlspecialchars($out));
		return NULL;
	}

	static public function my_autoload()
	{
	}
}

function dr()
{
	$arg = func_get_args();
	return call_user_func_array(array('APP', 'dr'), $arg);
}

function api()
{
	$arg = func_get_args();
	return call_user_func_array(array('APP', 'api'), $arg);
}


?>

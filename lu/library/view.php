<?php

class TPL
{
	static public $data = array();
	static public $tpl_key = false;
	static public $add_tpl_key_path;

	static public function tplPath($tplname, $dir = NULL)
	{
		if (!$dir) {
			$act = (!TPL::$tpl_key ? RUN_CONTROLLER : TPL::$tpl_key);
			$dir = (is_array($GLOBALS['tpl'][$act]) ? TPL::$tpl_key : $GLOBALS['tpl'][$act]);
		}

		if (!defined('WEB_TPL_DIR')) {
			define('WEB_TPL_DIR', TPL_DIR . '/' . $dir . TPL::$add_tpl_key_path);
		}

		if (!defined('SRC_TPL_DIR')) {
			define('SRC_TPL_DIR', WEB_URL . TPL_DIR_NAME . '/' . $dir);
		}

		if (defined('CUSTOM_APP_DIR')) {
			if (file_exists($file_path = CUSTOM_APP_DIR . '/view/' . $dir . TPL::$add_tpl_key_path . '/' . $tplname . '.tpl.php')) {
				define('Z_WEB_TPL_DIR', CUSTOM_APP_DIR . '/view/' . $dir . TPL::$add_tpl_key_path);
				return $file_path;
			}
		}

		return WEB_TPL_DIR . '/' . $tplname . '.tpl.php';
	}

	static public function fetch($tplname)
	{
		ob_start();
		ob_implicit_flush(0);
		TPL::display($tplname);
		return ob_get_clean();
	}

	static public function display($tplname, $output = true)
	{
		$file_path = TPL::tplPath($tplname);

		if (!is_file($file_path)) {
			throw new Exception('Not ' . $tplname . ' view');
		}

		extract(TPL::$data, EXTR_SKIP);
		include_once $file_path;
	}

	static public function assign($name, $value = NULL)
	{
		if (is_array($name)) {
			TPL::$data[$name] = array_merge(TPL::$data, $name);
		}
		else {
			TPL::$data[$name] = &$value;
		}
	}

	static public function load($name)
	{
	}
}


?>

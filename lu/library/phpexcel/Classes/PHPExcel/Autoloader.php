<?php

class PHPExcel_Autoloader
{
	static public function Register()
	{
		return spl_autoload_register(array('PHPExcel_Autoloader', 'Load'));
	}

	static public function Load($pObjectName)
	{
		if (class_exists($pObjectName) || (strpos($pObjectName, 'PHPExcel') === false)) {
			return false;
		}

		$pObjectFilePath = PHPEXCEL_ROOT . str_replace('_', DIRECTORY_SEPARATOR, $pObjectName) . '.php';
		if ((file_exists($pObjectFilePath) === false) || (is_readable($pObjectFilePath) === false)) {
			return false;
		}

		require $pObjectFilePath;
	}
}


?>

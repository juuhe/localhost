<?php

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
	PHPExcel_Autoloader::Register();
	PHPExcel_Shared_ZipStreamWrapper::register();

	if (ini_get('mbstring.func_overload') & 2) {
		throw new Exception('Multibyte function overloading in PHP must be disabled for string functions (2).');
	}
}

class PHPExcel_Reader_DefaultReadFilter implements PHPExcel_Reader_IReadFilter
{
	public function readCell($column, $row, $worksheetName = '')
	{
		return true;
	}
}

?>

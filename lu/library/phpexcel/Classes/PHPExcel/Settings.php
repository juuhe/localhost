<?php

class PHPExcel_Settings
{
	static public function getCacheStorageMethod()
	{
		return PHPExcel_CachedObjectStorageFactory::$_cacheStorageMethod;
	}

	static public function getCacheStorageClass()
	{
		return PHPExcel_CachedObjectStorageFactory::$_cacheStorageClass;
	}

	static public function setCacheStorageMethod($method = PHPExcel_CachedObjectStorageFactory::cache_in_memory, $arguments = array())
	{
		return PHPExcel_CachedObjectStorageFactory::initialize($method, $arguments);
	}

	static public function setLocale($locale)
	{
		return PHPExcel_Calculation::getInstance()->setLocale($locale);
	}
}

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
	PHPExcel_Autoloader::Register();
	PHPExcel_Shared_ZipStreamWrapper::register();

	if (ini_get('mbstring.func_overload') & 2) {
		throw new Exception('Multibyte function overloading in PHP must be disabled for string functions (2).');
	}
}

?>

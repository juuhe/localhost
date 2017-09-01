<?php

class PHPExcel_IOFactory
{
	/**
	 *	Search locations
	 *
	 *	@var	array
	 *	@access	private
	 *	@static
	 */
	static private $_searchLocations = array(
		array('type' => 'IWriter', 'path' => 'PHPExcel/Writer/{0}.php', 'class' => 'PHPExcel_Writer_{0}'),
		array('type' => 'IReader', 'path' => 'PHPExcel/Reader/{0}.php', 'class' => 'PHPExcel_Reader_{0}')
		);
	/**
	 *	Autoresolve classes
	 *
	 *	@var	array
	 *	@access	private
	 *	@static
	 */
	static private $_autoResolveClasses = array('Excel2007', 'Excel5', 'Excel2003XML', 'OOCalc', 'SYLK', 'Serialized', 'CSV');

	private function __construct()
	{
	}

	static public function getSearchLocations()
	{
		return self::$_searchLocations;
	}

	static public function setSearchLocations($value)
	{
		if (is_array($value)) {
			self::$_searchLocations = $value;
		}
		else {
			throw new Exception('Invalid parameter passed.');
		}
	}

	static public function addSearchLocation($type = '', $location = '', $classname = '')
	{
		self::$_searchLocations[] = array('type' => $type, 'path' => $location, 'class' => $classname);
	}

	static public function createWriter(PHPExcel $phpExcel, $writerType = '')
	{
		$searchType = 'IWriter';

		foreach (self::$_searchLocations as $searchLocation) {
			if ($searchLocation['type'] == $searchType) {
				$className = str_replace('{0}', $writerType, $searchLocation['class']);
				$classFile = str_replace('{0}', $writerType, $searchLocation['path']);
				$instance = new $className($phpExcel);

				if (!is_null($instance)) {
					return $instance;
				}
			}
		}

		throw new Exception('No ' . $searchType . ' found for type ' . $writerType);
	}

	static public function createReader($readerType = '')
	{
		$searchType = 'IReader';

		foreach (self::$_searchLocations as $searchLocation) {
			if ($searchLocation['type'] == $searchType) {
				$className = str_replace('{0}', $readerType, $searchLocation['class']);
				$classFile = str_replace('{0}', $readerType, $searchLocation['path']);
				$instance = new $className();

				if (!is_null($instance)) {
					return $instance;
				}
			}
		}

		throw new Exception('No ' . $searchType . ' found for type ' . $readerType);
	}

	static public function load($pFilename)
	{
		$reader = self::createReaderForFile($pFilename);
		return $reader->load($pFilename);
	}

	static public function identify($pFilename)
	{
		$reader = self::createReaderForFile($pFilename);
		$className = get_class($reader);
		$classType = explode('_', $className);
		unset($reader);
		return array_pop($classType);
	}

	static public function createReaderForFile($pFilename)
	{
		$pathinfo = pathinfo($pFilename);

		if (isset($pathinfo['extension'])) {
			switch (strtolower($pathinfo['extension'])) {
			case 'xlsx':
				$reader = self::createReader('Excel2007');
				break;

			case 'xls':
				$reader = self::createReader('Excel5');
				break;

			case 'ods':
				$reader = self::createReader('OOCalc');
				break;

			case 'slk':
				$reader = self::createReader('SYLK');
				break;

			case 'xml':
				$reader = self::createReader('Excel2003XML');
				break;

			case 'csv':
				break;

			default:
				break;
			}

			if (isset($reader) && $reader->canRead($pFilename)) {
				return $reader;
			}
		}

		foreach (self::$_autoResolveClasses as $autoResolveClass) {
			$reader = self::createReader($autoResolveClass);

			if ($reader->canRead($pFilename)) {
				return $reader;
			}
		}
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

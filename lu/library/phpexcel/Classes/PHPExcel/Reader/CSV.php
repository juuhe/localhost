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

class PHPExcel_Reader_CSV implements PHPExcel_Reader_IReader
{
	/**
	 *	Input encoding
	 *
	 *	@access	private
	 *	@var	string
	 */
	private $_inputEncoding;
	/**
	 *	Delimiter
	 *
	 *	@access	private
	 *	@var string
	 */
	private $_delimiter;
	/**
	 *	Enclosure
	 *
	 *	@access	private
	 *	@var	string
	 */
	private $_enclosure;
	/**
	 *	Line ending
	 *
	 *	@access	private
	 *	@var	string
	 */
	private $_lineEnding;
	/**
	 *	Sheet index to read
	 *
	 *	@access	private
	 *	@var	int
	 */
	private $_sheetIndex;
	/**
	 *	PHPExcel_Reader_IReadFilter instance
	 *
	 *	@access	private
	 *	@var	PHPExcel_Reader_IReadFilter
	 */
	private $_readFilter;

	public function __construct()
	{
		$this->_inputEncoding = 'UTF-8';
		$this->_delimiter = ',';
		$this->_enclosure = '"';
		$this->_lineEnding = PHP_EOL;
		$this->_sheetIndex = 0;
		$this->_readFilter = new PHPExcel_Reader_DefaultReadFilter();
	}

	public function canRead($pFilename)
	{
		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		return true;
	}

	public function load($pFilename)
	{
		$objPHPExcel = new PHPExcel();
		return $this->loadIntoExisting($pFilename, $objPHPExcel);
	}

	public function getReadFilter()
	{
		return $this->_readFilter;
	}

	public function setReadFilter(PHPExcel_Reader_IReadFilter $pValue)
	{
		$this->_readFilter = $pValue;
		return $this;
	}

	public function setInputEncoding($pValue = 'UTF-8')
	{
		$this->_inputEncoding = $pValue;
		return $this;
	}

	public function getInputEncoding()
	{
		return $this->_inputEncoding;
	}

	public function loadIntoExisting($pFilename, PHPExcel $objPHPExcel)
	{
		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		while ($objPHPExcel->getSheetCount() <= $this->_sheetIndex) {
			$objPHPExcel->createSheet();
		}

		$objPHPExcel->setActiveSheetIndex($this->_sheetIndex);
		$fileHandle = fopen($pFilename, 'r');

		if ($fileHandle === false) {
			throw new Exception('Could not open file ' . $pFilename . ' for reading.');
		}

		switch ($this->_inputEncoding) {
		case 'UTF-8':
			fgets($fileHandle, 4) == "\xef\xbb\xbf" ? fseek($fileHandle, 3) : fseek($fileHandle, 0);
			break;

		default:
			break;
		}

		$currentRow = 0;
		$rowData = array();

		while (($rowData = fgetcsv($fileHandle, 0, $this->_delimiter, $this->_enclosure)) !== false) {
			++$currentRow;
			$rowDataCount = count($rowData);

			for ($i = 0; $i < $rowDataCount; ++$i) {
				$columnLetter = PHPExcel_Cell::stringFromColumnIndex($i);
				if (($rowData[$i] != '') && $this->_readFilter->readCell($columnLetter, $currentRow)) {
					$rowData[$i] = str_replace('\\' . $this->_enclosure, $this->_enclosure, $rowData[$i]);
					$rowData[$i] = str_replace($this->_enclosure . $this->_enclosure, $this->_enclosure, $rowData[$i]);

					if ($this->_inputEncoding !== 'UTF-8') {
						$rowData[$i] = PHPExcel_Shared_String::ConvertEncoding($rowData[$i], 'UTF-8', $this->_inputEncoding);
					}

					$objPHPExcel->getActiveSheet()->getCell($columnLetter . $currentRow)->setValue($rowData[$i]);
				}
			}
		}

		fclose($fileHandle);
		return $objPHPExcel;
	}

	public function getDelimiter()
	{
		return $this->_delimiter;
	}

	public function setDelimiter($pValue = ',')
	{
		$this->_delimiter = $pValue;
		return $this;
	}

	public function getEnclosure()
	{
		return $this->_enclosure;
	}

	public function setEnclosure($pValue = '"')
	{
		if ($pValue == '') {
			$pValue = '"';
		}

		$this->_enclosure = $pValue;
		return $this;
	}

	public function getLineEnding()
	{
		return $this->_lineEnding;
	}

	public function setLineEnding($pValue = PHP_EOL)
	{
		$this->_lineEnding = $pValue;
		return $this;
	}

	public function getSheetIndex()
	{
		return $this->_sheetIndex;
	}

	public function setSheetIndex($pValue = 0)
	{
		$this->_sheetIndex = $pValue;
		return $this;
	}
}

?>

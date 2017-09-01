<?php

class PHPExcel_Writer_CSV implements PHPExcel_Writer_IWriter
{
	/**
	 * PHPExcel object
	 *
	 * @var PHPExcel
	 */
	private $_phpExcel;
	/**
	 * Delimiter
	 *
	 * @var string
	 */
	private $_delimiter;
	/**
	 * Enclosure
	 *
	 * @var string
	 */
	private $_enclosure;
	/**
	 * Line ending
	 *
	 * @var string
	 */
	private $_lineEnding;
	/**
	 * Sheet index to write
	 *
	 * @var int
	 */
	private $_sheetIndex;
	/**
	 * Pre-calculate formulas
	 *
	 * @var boolean
	 */
	private $_preCalculateFormulas = true;
	/**
	 * Whether to write a BOM (for UTF8).
	 *
	 * @var boolean
	 */
	private $_useBOM = false;

	public function __construct(PHPExcel $phpExcel)
	{
		$this->_phpExcel = $phpExcel;
		$this->_delimiter = ',';
		$this->_enclosure = '"';
		$this->_lineEnding = PHP_EOL;
		$this->_sheetIndex = 0;
	}

	public function save($pFilename = NULL)
	{
		$sheet = $this->_phpExcel->getSheet($this->_sheetIndex);
		$saveArrayReturnType = PHPExcel_Calculation::getArrayReturnType();
		PHPExcel_Calculation::setArrayReturnType(PHPExcel_Calculation::RETURN_ARRAY_AS_VALUE);
		$fileHandle = fopen($pFilename, 'w');

		if ($fileHandle === false) {
			throw new Exception('Could not open file ' . $pFilename . ' for writing.');
		}

		if ($this->_useBOM) {
			fwrite($fileHandle, "\xef\xbb\xbf");
		}

		$cellsArray = $sheet->toArray('', $this->_preCalculateFormulas);

		foreach ($cellsArray as $row) {
			$this->_writeLine($fileHandle, $row);
		}

		fclose($fileHandle);
		PHPExcel_Calculation::setArrayReturnType($saveArrayReturnType);
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
			$pValue = NULL;
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

	public function getUseBOM()
	{
		return $this->_useBOM;
	}

	public function setUseBOM($pValue = false)
	{
		$this->_useBOM = $pValue;
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

	private function _writeLine($pFileHandle = NULL, $pValues = NULL)
	{
		if (!is_null($pFileHandle) && is_array($pValues)) {
			$writeDelimiter = false;
			$line = '';

			foreach ($pValues as $element) {
				$element = str_replace($this->_enclosure, $this->_enclosure . $this->_enclosure, $element);

				if ($writeDelimiter) {
					$line .= $this->_delimiter;
				}
				else {
					$writeDelimiter = true;
				}

				$line .= $this->_enclosure . $element . $this->_enclosure;
			}

			$line .= $this->_lineEnding;
			fwrite($pFileHandle, $line);
		}
		else {
			throw new Exception('Invalid parameters passed.');
		}
	}

	public function getPreCalculateFormulas()
	{
		return $this->_preCalculateFormulas;
	}

	public function setPreCalculateFormulas($pValue = true)
	{
		$this->_preCalculateFormulas = $pValue;
		return $this;
	}
}

?>

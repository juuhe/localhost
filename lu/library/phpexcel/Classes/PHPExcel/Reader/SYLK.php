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

class PHPExcel_Reader_SYLK implements PHPExcel_Reader_IReader
{
	/**
	 * Input encoding
	 *
	 * @var string
	 */
	private $_inputEncoding;
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
	 * Sheet index to read
	 *
	 * @var int
	 */
	private $_sheetIndex;
	/**
	 * Formats
	 *
	 * @var array
	 */
	private $_formats = array();
	/**
	 * Format Count
	 *
	 * @var int
	 */
	private $_format = 0;
	/**
	 * PHPExcel_Reader_IReadFilter instance
	 *
	 * @var PHPExcel_Reader_IReadFilter
	 */
	private $_readFilter;

	public function __construct()
	{
		$this->_inputEncoding = 'ANSI';
		$this->_delimiter = ';';
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

		$fh = fopen($pFilename, 'r');
		$data = fread($fh, 2048);
		fclose($fh);
		$delimiterCount = substr_count($data, ';');

		if ($delimiterCount < 1) {
			return false;
		}

		$lines = explode("\n", $data);

		if (substr($lines[0], 0, 4) != 'ID;P') {
			return false;
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

	public function setInputEncoding($pValue = 'ANSI')
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
		$fromFormats = array('\\-', '\\ ');
		$toFormats = array('-', ' ');
		$fileHandle = fopen($pFilename, 'r');

		if ($fileHandle === false) {
			throw new Exception('Could not open file ' . $pFilename . ' for reading.');
		}

		$rowData = array();
		$column = $row = '';

		while (($rowData = fgets($fileHandle)) !== false) {
			$rowData = PHPExcel_Shared_String::SYLKtoUTF8($rowData);
			$rowData = explode('	', str_replace('¤', ';', str_replace(';', '	', str_replace(';;', '¤', rtrim($rowData)))));
			$dataType = array_shift($rowData);

			if ($dataType == 'P') {
				$formatArray = array();

				foreach ($rowData as $rowDatum) {
					switch ($rowDatum[0]) {
					case 'P':
						$formatArray['numberformat']['code'] = str_replace($fromFormats, $toFormats, substr($rowDatum, 1));
						break;

					case 'E':
					case 'F':
						$formatArray['font']['name'] = substr($rowDatum, 1);
						break;

					case 'L':
						$formatArray['font']['size'] = substr($rowDatum, 1);
						break;

					case 'S':
						$styleSettings = substr($rowDatum, 1);

						for ($i = 0; $i < strlen($styleSettings); ++$i) {
							switch ($styleSettings[$i]) {
							case 'I':
								$formatArray['font']['italic'] = true;
								break;

							case 'D':
								$formatArray['font']['bold'] = true;
								break;

							case 'T':
								$formatArray['borders']['top']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;

							case 'B':
								$formatArray['borders']['bottom']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;

							case 'L':
								$formatArray['borders']['left']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;

							case 'R':
								$formatArray['borders']['right']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;
							}
						}

						break;
					}
				}

				$this->_formats['P' . $this->_format++] = $formatArray;
			}
			else if ($dataType == 'C') {
				$hasCalculatedValue = false;
				$cellData = $cellDataFormula = '';

				foreach ($rowData as $rowDatum) {
					switch ($rowDatum[0]) {
					case 'C':
					case 'X':
						$column = substr($rowDatum, 1);
						break;

					case 'R':
					case 'Y':
						$row = substr($rowDatum, 1);
						break;

					case 'K':
						$cellData = substr($rowDatum, 1);
						break;

					case 'E':
						$cellDataFormula = '=' . substr($rowDatum, 1);
						$temp = explode('"', $cellDataFormula);

						foreach ($temp as $key => &$value) {
							if (($key % 2) == 0) {
								preg_match_all('/(R(\\[?-?\\d*\\]?))(C(\\[?-?\\d*\\]?))/', $value, $cellReferences, PREG_SET_ORDER + PREG_OFFSET_CAPTURE);
								$cellReferences = array_reverse($cellReferences);

								foreach ($cellReferences as $cellReference) {
									$rowReference = $cellReference[2][0];

									if ($rowReference == '') {
										$rowReference = $row;
									}

									if ($rowReference[0] == '[') {
										$rowReference = $row + trim($rowReference, '[]');
									}

									$columnReference = $cellReference[4][0];

									if ($columnReference == '') {
										$columnReference = $column;
									}

									if ($columnReference[0] == '[') {
										$columnReference = $column + trim($columnReference, '[]');
									}

									$A1CellReference = PHPExcel_Cell::stringFromColumnIndex($columnReference - 1) . $rowReference;
									$value = substr_replace($value, $A1CellReference, $cellReference[0][1], strlen($cellReference[0][0]));
								}
							}
						}

						unset($value);
						$cellDataFormula = implode('"', $temp);
						$hasCalculatedValue = true;
						break;
					}
				}

				$columnLetter = PHPExcel_Cell::stringFromColumnIndex($column - 1);
				$cellData = PHPExcel_Calculation::_unwrapResult($cellData);
				$objPHPExcel->getActiveSheet()->getCell($columnLetter . $row)->setValue($hasCalculatedValue ? $cellDataFormula : $cellData);

				if ($hasCalculatedValue) {
					$cellData = PHPExcel_Calculation::_unwrapResult($cellData);
					$objPHPExcel->getActiveSheet()->getCell($columnLetter . $row)->setCalculatedValue($cellData);
				}
			}
			else if ($dataType == 'F') {
				$formatStyle = $columnWidth = $styleSettings = '';
				$styleData = array();

				foreach ($rowData as $rowDatum) {
					switch ($rowDatum[0]) {
					case 'C':
					case 'X':
						$column = substr($rowDatum, 1);
						break;

					case 'R':
					case 'Y':
						$row = substr($rowDatum, 1);
						break;

					case 'P':
						$formatStyle = $rowDatum;
						break;

					case 'W':
						list($startCol, $endCol, $columnWidth) = explode(' ', substr($rowDatum, 1));
						break;

					case 'S':
						$styleSettings = substr($rowDatum, 1);

						for ($i = 0; $i < strlen($styleSettings); ++$i) {
							switch ($styleSettings[$i]) {
							case 'I':
								$styleData['font']['italic'] = true;
								break;

							case 'D':
								$styleData['font']['bold'] = true;
								break;

							case 'T':
								$styleData['borders']['top']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;

							case 'B':
								$styleData['borders']['bottom']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;

							case 'L':
								$styleData['borders']['left']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;

							case 'R':
								$styleData['borders']['right']['style'] = PHPExcel_Style_Border::BORDER_THIN;
								break;
							}
						}

						break;
					}
				}

				if (('' < $formatStyle) && ('' < $column) && ('' < $row)) {
					$columnLetter = PHPExcel_Cell::stringFromColumnIndex($column - 1);
					$objPHPExcel->getActiveSheet()->getStyle($columnLetter . $row)->applyFromArray($this->_formats[$formatStyle]);
				}

				if ((0 < count($styleData)) && ('' < $column) && ('' < $row)) {
					$columnLetter = PHPExcel_Cell::stringFromColumnIndex($column - 1);
					$objPHPExcel->getActiveSheet()->getStyle($columnLetter . $row)->applyFromArray($styleData);
				}

				if ('' < $columnWidth) {
					if ($startCol == $endCol) {
						$startCol = PHPExcel_Cell::stringFromColumnIndex($startCol - 1);
						$objPHPExcel->getActiveSheet()->getColumnDimension($startCol)->setWidth($columnWidth);
					}
					else {
						$startCol = PHPExcel_Cell::stringFromColumnIndex($startCol - 1);
						$endCol = PHPExcel_Cell::stringFromColumnIndex($endCol - 1);
						$objPHPExcel->getActiveSheet()->getColumnDimension($startCol)->setWidth($columnWidth);

						do {
							$objPHPExcel->getActiveSheet()->getColumnDimension(++$startCol)->setWidth($columnWidth);
						} while ($startCol != $endCol);
					}
				}
			}
			else {
				foreach ($rowData as $rowDatum) {
					switch ($rowDatum[0]) {
					case 'C':
					case 'X':
						$column = substr($rowDatum, 1);
						break;

					case 'R':
					case 'Y':
						$row = substr($rowDatum, 1);
						break;
					}
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

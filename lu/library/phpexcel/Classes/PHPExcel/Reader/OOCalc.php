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

class PHPExcel_Reader_OOCalc implements PHPExcel_Reader_IReader
{
	/**
	 * Read data only?
	 *
	 * @var boolean
	 */
	private $_readDataOnly = false;
	/**
	 * Restict which sheets should be loaded?
	 *
	 * @var array
	 */
	private $_loadSheetsOnly;
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
	private $_styles = array();
	/**
	 * PHPExcel_Reader_IReadFilter instance
	 *
	 * @var PHPExcel_Reader_IReadFilter
	 */
	private $_readFilter;

	public function getReadDataOnly()
	{
		return $this->_readDataOnly;
	}

	public function setReadDataOnly($pValue = false)
	{
		$this->_readDataOnly = $pValue;
		return $this;
	}

	public function getLoadSheetsOnly()
	{
		return $this->_loadSheetsOnly;
	}

	public function setLoadSheetsOnly($value = NULL)
	{
		$this->_loadSheetsOnly = is_array($value) ? $value : array($value);
		return $this;
	}

	public function setLoadAllSheets()
	{
		$this->_loadSheetsOnly = NULL;
		return $this;
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

	public function __construct()
	{
		$this->_sheetIndex = 0;
		$this->_readFilter = new PHPExcel_Reader_DefaultReadFilter();
	}

	public function canRead($pFilename)
	{
		if (!class_exists('ZipArchive')) {
			return false;
		}

		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		$zip = new ZipArchive();

		if ($zip->open($pFilename) === true) {
			$mimeType = $zip->getFromName('mimetype');
			$zip->close();
			return $mimeType === 'application/vnd.oasis.opendocument.spreadsheet';
		}

		return false;
	}

	public function load($pFilename)
	{
		$objPHPExcel = new PHPExcel();
		return $this->loadIntoExisting($pFilename, $objPHPExcel);
	}

	static private function identifyFixedStyleValue($styleList, &$styleAttributeValue)
	{
		$styleAttributeValue = strtolower($styleAttributeValue);

		foreach ($styleList as $style) {
			if ($styleAttributeValue == strtolower($style)) {
				$styleAttributeValue = $style;
				return true;
			}
		}

		return false;
	}

	public function loadIntoExisting($pFilename, PHPExcel $objPHPExcel)
	{
		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		$zip = new ZipArchive();

		if ($zip->open($pFilename) === true) {
			$xml = simplexml_load_string($zip->getFromName('meta.xml'));
			$namespacesMeta = $xml->getNamespaces(true);
			$docProps = $objPHPExcel->getProperties();
			$officeProperty = $xml->children($namespacesMeta['office']);

			foreach ($officeProperty as $officePropertyData) {
				$officePropertyDC = array();

				if (isset($namespacesMeta['dc'])) {
					$officePropertyDC = $officePropertyData->children($namespacesMeta['dc']);
				}

				foreach ($officePropertyDC as $propertyName => $propertyValue) {
					switch ($propertyName) {
					case 'title':
						$docProps->setTitle($propertyValue);
						break;

					case 'subject':
						$docProps->setSubject($propertyValue);
						break;

					case 'creator':
						$docProps->setCreator($propertyValue);
						break;

					case 'date':
						$creationDate = strtotime($propertyValue);
						$docProps->setCreated($creationDate);
						break;

					case 'description':
						$docProps->setDescription($propertyValue);
						break;
					}
				}

				$officePropertyMeta = array();

				if (isset($namespacesMeta['dc'])) {
					$officePropertyMeta = $officePropertyData->children($namespacesMeta['meta']);
				}

				foreach ($officePropertyMeta as $propertyName => $propertyValue) {
					$propertyValueAttributes = $propertyValue->attributes($namespacesMeta['meta']);

					switch ($propertyName) {
					case 'keyword':
						$docProps->setKeywords($propertyValue);
						break;
					}
				}
			}

			$xml = simplexml_load_string($zip->getFromName('content.xml'));
			$namespacesContent = $xml->getNamespaces(true);
			$workbook = $xml->children($namespacesContent['office']);

			foreach ($workbook->body->spreadsheet as $workbookData) {
				$workbookData = $workbookData->children($namespacesContent['table']);
				$worksheetID = 0;

				foreach ($workbookData->table as $worksheetDataSet) {
					$worksheetData = $worksheetDataSet->children($namespacesContent['table']);
					$worksheetDataAttributes = $worksheetDataSet->attributes($namespacesContent['table']);
					if (isset($this->_loadSheetsOnly) && isset($worksheetDataAttributes['name']) && !in_array($worksheetDataAttributes['name'], $this->_loadSheetsOnly)) {
						continue;
					}

					$objPHPExcel->createSheet();
					$objPHPExcel->setActiveSheetIndex($worksheetID);

					if (isset($worksheetDataAttributes['name'])) {
						$worksheetName = (string) $worksheetDataAttributes['name'];
						$objPHPExcel->getActiveSheet()->setTitle($worksheetName);
					}

					$rowID = 1;

					foreach ($worksheetData as $key => $rowData) {
						switch ($key) {
						case 'table-row':
							$columnID = 'A';

							foreach ($rowData as $key => $cellData) {
								$cellDataText = $cellData->children($namespacesContent['text']);
								$cellDataOfficeAttributes = $cellData->attributes($namespacesContent['office']);
								$cellDataTableAttributes = $cellData->attributes($namespacesContent['table']);
								$type = $formatting = $hyperlink = NULL;
								$hasCalculatedValue = false;
								$cellDataFormula = '';

								if (isset($cellDataTableAttributes['formula'])) {
									$cellDataFormula = $cellDataTableAttributes['formula'];
									$hasCalculatedValue = true;
								}

								if (isset($cellDataText->p)) {
									switch ($cellDataOfficeAttributes['value-type']) {
									case 'string':
										$type = PHPExcel_Cell_DataType::TYPE_STRING;
										$dataValue = $cellDataText->p;

										if (isset($dataValue->a)) {
											$dataValue = $dataValue->a;
											$cellXLinkAttributes = $dataValue->attributes($namespacesContent['xlink']);
											$hyperlink = $cellXLinkAttributes['href'];
										}

										break;

									case 'boolean':
										$type = PHPExcel_Cell_DataType::TYPE_BOOL;
										$dataValue = ($cellDataText->p == 'TRUE' ? true : false);
										break;

									case 'float':
										$type = PHPExcel_Cell_DataType::TYPE_NUMERIC;
										$dataValue = (double) $cellDataOfficeAttributes['value'];

										if (floor($dataValue) == $dataValue) {
											$dataValue = (int) $dataValue;
										}

										break;

									case 'date':
										$type = PHPExcel_Cell_DataType::TYPE_NUMERIC;
										$dateObj = date_create($cellDataOfficeAttributes['date-value']);
										list($year, $month, $day, $hour, $minute, $second) = explode(' ', $dateObj->format('Y m d H i s'));
										$dataValue = PHPExcel_Shared_Date::FormattedPHPToExcel($year, $month, $day, $hour, $minute, $second);

										if ($dataValue != floor($dataValue)) {
											$formatting = PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15 . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4;
										}
										else {
											$formatting = PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15;
										}

										break;

									case 'time':
										$type = PHPExcel_Cell_DataType::TYPE_NUMERIC;
										$dataValue = PHPExcel_Shared_Date::PHPToExcel(strtotime('01-01-1970 ' . implode(':', sscanf($cellDataOfficeAttributes['time-value'], 'PT%dH%dM%dS'))));
										$formatting = PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4;
										break;
									}
								}

								if ($hasCalculatedValue) {
									$type = PHPExcel_Cell_DataType::TYPE_FORMULA;
									$cellDataFormula = substr($cellDataFormula, strpos($cellDataFormula, ':=') + 1);
									$temp = explode('"', $cellDataFormula);

									foreach ($temp as $key => &$value) {
										if (($key % 2) == 0) {
											$value = preg_replace('/\\[\\.(.*):\\.(.*)\\]/Ui', '$1:$2', $value);
											$value = preg_replace('/\\[\\.(.*)\\]/Ui', '$1', $value);
											$value = PHPExcel_Calculation::_translateSeparator(';', ',', $value);
										}
									}

									unset($value);
									$cellDataFormula = implode('"', $temp);
								}

								if (!is_null($type)) {
									$objPHPExcel->getActiveSheet()->getCell($columnID . $rowID)->setValueExplicit($hasCalculatedValue ? $cellDataFormula : $dataValue, $type);

									if ($hasCalculatedValue) {
										$objPHPExcel->getActiveSheet()->getCell($columnID . $rowID)->setCalculatedValue($dataValue);
									}

									if (($cellDataOfficeAttributes['value-type'] == 'date') || ($cellDataOfficeAttributes['value-type'] == 'time')) {
										$objPHPExcel->getActiveSheet()->getStyle($columnID . $rowID)->getNumberFormat()->setFormatCode($formatting);
									}

									if (!is_null($hyperlink)) {
										$objPHPExcel->getActiveSheet()->getCell($columnID . $rowID)->getHyperlink()->setUrl($hyperlink);
									}
								}

								if (isset($cellDataTableAttributes['number-columns-spanned']) || isset($cellDataTableAttributes['number-rows-spanned'])) {
									$columnTo = $columnID;

									if (isset($cellDataTableAttributes['number-columns-spanned'])) {
										$columnTo = PHPExcel_Cell::stringFromColumnIndex((PHPExcel_Cell::columnIndexFromString($columnID) + $cellDataTableAttributes['number-columns-spanned']) - 2);
									}

									$rowTo = $rowID;

									if (isset($cellDataTableAttributes['number-rows-spanned'])) {
										$rowTo = ($rowTo + $cellDataTableAttributes['number-rows-spanned']) - 1;
									}

									$cellRange = $columnID . $rowID . ':' . $columnTo . $rowTo;
									$objPHPExcel->getActiveSheet()->mergeCells($cellRange);
								}

								if (isset($cellDataTableAttributes['number-columns-repeated'])) {
									$columnID = PHPExcel_Cell::stringFromColumnIndex((PHPExcel_Cell::columnIndexFromString($columnID) + $cellDataTableAttributes['number-columns-repeated']) - 2);
								}

								++$columnID;
							}

							++$rowID;
							break;
						}
					}

					++$worksheetID;
				}
			}
		}

		return $objPHPExcel;
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

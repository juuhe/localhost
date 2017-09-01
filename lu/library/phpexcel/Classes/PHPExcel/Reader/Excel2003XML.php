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

class PHPExcel_Reader_Excel2003XML implements PHPExcel_Reader_IReader
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
		$signature = array('<?xml version="1.0"?>', '<?mso-application progid="Excel.Sheet"?>');

		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		$fh = fopen($pFilename, 'r');
		$data = fread($fh, 2048);
		fclose($fh);
		$headers = explode("\n", $data);
		$valid = true;

		foreach ($signature as $key => $match) {
			if (isset($headers[$key])) {
				$line = trim(rtrim($headers[$key], "\r\n"));

				if ($line != $match) {
					$valid = false;
					break;
				}
			}
			else {
				$valid = false;
				break;
			}
		}

		return $valid;
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

	static private function _pixel2WidthUnits($pxs)
	{
		$UNIT_OFFSET_MAP = array(0, 36, 73, 109, 146, 182, 219);
		$widthUnits = 256 * ($pxs / 7);
		$widthUnits += $UNIT_OFFSET_MAP[$pxs % 7];
		return $widthUnits;
	}

	static private function _widthUnits2Pixel($widthUnits)
	{
		$pixels = ($widthUnits / 256) * 7;
		$offsetWidthUnits = $widthUnits % 256;
		$pixels += round($offsetWidthUnits / 256 / 7);
		return $pixels;
	}

	public function loadIntoExisting($pFilename, PHPExcel $objPHPExcel)
	{
		$fromFormats = array('\\-', '\\ ');
		$toFormats = array('-', ' ');
		$underlineStyles = array(PHPExcel_Style_Font::UNDERLINE_NONE, PHPExcel_Style_Font::UNDERLINE_DOUBLE, PHPExcel_Style_Font::UNDERLINE_DOUBLEACCOUNTING, PHPExcel_Style_Font::UNDERLINE_SINGLE, PHPExcel_Style_Font::UNDERLINE_SINGLEACCOUNTING);
		$verticalAlignmentStyles = array(PHPExcel_Style_Alignment::VERTICAL_BOTTOM, PHPExcel_Style_Alignment::VERTICAL_TOP, PHPExcel_Style_Alignment::VERTICAL_CENTER, PHPExcel_Style_Alignment::VERTICAL_JUSTIFY);
		$horizontalAlignmentStyles = array(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL, PHPExcel_Style_Alignment::HORIZONTAL_LEFT, PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, PHPExcel_Style_Alignment::HORIZONTAL_CENTER, PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS, PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		$xml = simplexml_load_file($pFilename);
		$namespaces = $xml->getNamespaces(true);
		$docProps = $objPHPExcel->getProperties();

		foreach ($xml->DocumentProperties[0] as $propertyName => $propertyValue) {
			switch ($propertyName) {
			case 'Title':
				$docProps->setTitle($propertyValue);
				break;

			case 'Subject':
				$docProps->setSubject($propertyValue);
				break;

			case 'Author':
				$docProps->setCreator($propertyValue);
				break;

			case 'Created':
				$creationDate = strtotime($propertyValue);
				$docProps->setCreated($creationDate);
				break;

			case 'LastAuthor':
				$docProps->setLastModifiedBy($propertyValue);
				break;

			case 'Company':
				$docProps->setCompany($propertyValue);
				break;

			case 'Category':
				$docProps->setCategory($propertyValue);
				break;

			case 'Keywords':
				$docProps->setKeywords($propertyValue);
				break;

			case 'Description':
				$docProps->setDescription($propertyValue);
				break;
			}
		}

		foreach ($xml->Styles[0] as $style) {
			$style_ss = $style->attributes($namespaces['ss']);
			$styleID = (string) $style_ss['ID'];

			if ($styleID == 'Default') {
				$this->_styles['Default'] = array();
			}
			else {
				$this->_styles[$styleID] = $this->_styles['Default'];
			}

			foreach ($style as $styleType => $styleData) {
				$styleAttributes = $styleData->attributes($namespaces['ss']);

				switch ($styleType) {
				case 'Alignment':
					foreach ($styleAttributes as $styleAttributeKey => $styleAttributeValue) {
						$styleAttributeValue = (string) $styleAttributeValue;

						switch ($styleAttributeKey) {
						case 'Vertical':
							if (self::identifyFixedStyleValue($verticalAlignmentStyles, $styleAttributeValue)) {
								$this->_styles[$styleID]['alignment']['vertical'] = $styleAttributeValue;
							}

							break;

						case 'Horizontal':
							if (self::identifyFixedStyleValue($horizontalAlignmentStyles, $styleAttributeValue)) {
								$this->_styles[$styleID]['alignment']['horizontal'] = $styleAttributeValue;
							}

							break;

						case 'WrapText':
							$this->_styles[$styleID]['alignment']['wrap'] = true;
							break;
						}
					}

					break;

				case 'Borders':
					foreach ($styleData->Border as $borderStyle) {
						$borderAttributes = $borderStyle->attributes($namespaces['ss']);
						$thisBorder = array();

						foreach ($borderAttributes as $borderStyleKey => $borderStyleValue) {
							switch ($borderStyleKey) {
							case 'LineStyle':
								$thisBorder['style'] = PHPExcel_Style_Border::BORDER_MEDIUM;
								break;

							case 'Weight':
								break;

							case 'Position':
								$borderPosition = strtolower($borderStyleValue);
								break;

							case 'Color':
								$borderColour = substr($borderStyleValue, 1);
								$thisBorder['color']['rgb'] = $borderColour;
								break;
							}
						}

						if (0 < count($thisBorder)) {
							if (($borderPosition == 'left') || ($borderPosition == 'right') || ($borderPosition == 'top') || ($borderPosition == 'bottom')) {
								$this->_styles[$styleID]['borders'][$borderPosition] = $thisBorder;
							}
						}
					}

					break;

				case 'Font':
					foreach ($styleAttributes as $styleAttributeKey => $styleAttributeValue) {
						$styleAttributeValue = (string) $styleAttributeValue;

						switch ($styleAttributeKey) {
						case 'FontName':
							$this->_styles[$styleID]['font']['name'] = $styleAttributeValue;
							break;

						case 'Size':
							$this->_styles[$styleID]['font']['size'] = $styleAttributeValue;
							break;

						case 'Color':
							$this->_styles[$styleID]['font']['color']['rgb'] = substr($styleAttributeValue, 1);
							break;

						case 'Bold':
							$this->_styles[$styleID]['font']['bold'] = true;
							break;

						case 'Italic':
							$this->_styles[$styleID]['font']['italic'] = true;
							break;

						case 'Underline':
							if (self::identifyFixedStyleValue($underlineStyles, $styleAttributeValue)) {
								$this->_styles[$styleID]['font']['underline'] = $styleAttributeValue;
							}

							break;
						}
					}

					break;

				case 'Interior':
					foreach ($styleAttributes as $styleAttributeKey => $styleAttributeValue) {
						switch ($styleAttributeKey) {
						case 'Color':
							$this->_styles[$styleID]['fill']['color']['rgb'] = substr($styleAttributeValue, 1);
							break;
						}
					}

					break;

				case 'NumberFormat':
					foreach ($styleAttributes as $styleAttributeKey => $styleAttributeValue) {
						$styleAttributeValue = str_replace($fromFormats, $toFormats, $styleAttributeValue);

						switch ($styleAttributeValue) {
						case 'Short Date':
							$styleAttributeValue = 'dd/mm/yyyy';
							break;
						}

						if ('' < $styleAttributeValue) {
							$this->_styles[$styleID]['numberformat']['code'] = $styleAttributeValue;
						}
					}

					break;

				case 'Protection':
					foreach ($styleAttributes as $styleAttributeKey => $styleAttributeValue) {
					}

					break;
				}
			}
		}

		$worksheetID = 0;

		foreach ($xml->Worksheet as $worksheet) {
			$worksheet_ss = $worksheet->attributes($namespaces['ss']);
			if (isset($this->_loadSheetsOnly) && isset($worksheet_ss['Name']) && !in_array($worksheet_ss['Name'], $this->_loadSheetsOnly)) {
				continue;
			}

			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($worksheetID);

			if (isset($worksheet_ss['Name'])) {
				$worksheetName = (string) $worksheet_ss['Name'];
				$objPHPExcel->getActiveSheet()->setTitle($worksheetName);
			}

			$columnID = 'A';

			foreach ($worksheet->Table->Column as $columnData) {
				$columnData_ss = $columnData->attributes($namespaces['ss']);

				if (isset($columnData_ss['Index'])) {
					$columnID = PHPExcel_Cell::stringFromColumnIndex($columnData_ss['Index'] - 1);
				}

				if (isset($columnData_ss['Width'])) {
					$columnWidth = $columnData_ss['Width'];
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth($columnWidth / 5.4000000000000004);
				}

				++$columnID;
			}

			$rowID = 1;

			foreach ($worksheet->Table->Row as $rowData) {
				$row_ss = $rowData->attributes($namespaces['ss']);

				if (isset($row_ss['Index'])) {
					$rowID = (int) $row_ss['Index'];
				}

				if (isset($row_ss['StyleID'])) {
					$rowStyle = $row_ss['StyleID'];
				}

				if (isset($row_ss['Height'])) {
					$rowHeight = $row_ss['Height'];
					$objPHPExcel->getActiveSheet()->getRowDimension($rowID)->setRowHeight($rowHeight);
				}

				$columnID = 'A';

				foreach ($rowData->Cell as $cell) {
					$cell_ss = $cell->attributes($namespaces['ss']);

					if (isset($cell_ss['Index'])) {
						$columnID = PHPExcel_Cell::stringFromColumnIndex($cell_ss['Index'] - 1);
					}

					$cellRange = $columnID . $rowID;
					if (isset($cell_ss['MergeAcross']) || isset($cell_ss['MergeDown'])) {
						$columnTo = $columnID;

						if (isset($cell_ss['MergeAcross'])) {
							$columnTo = PHPExcel_Cell::stringFromColumnIndex((PHPExcel_Cell::columnIndexFromString($columnID) + $cell_ss['MergeAcross']) - 1);
						}

						$rowTo = $rowID;

						if (isset($cell_ss['MergeDown'])) {
							$rowTo = $rowTo + $cell_ss['MergeDown'];
						}

						$cellRange .= ':' . $columnTo . $rowTo;
						$objPHPExcel->getActiveSheet()->mergeCells($cellRange);
					}

					$hasCalculatedValue = false;
					$cellDataFormula = '';

					if (isset($cell_ss['Formula'])) {
						$cellDataFormula = $cell_ss['Formula'];
						$hasCalculatedValue = true;
					}

					if (isset($cell->Data)) {
						$cellValue = $cellData = $cell->Data;
						$type = PHPExcel_Cell_DataType::TYPE_NULL;
						$cellData_ss = $cellData->attributes($namespaces['ss']);

						if (isset($cellData_ss['Type'])) {
							$cellDataType = $cellData_ss['Type'];

							switch ($cellDataType) {
							case 'String':
								$type = PHPExcel_Cell_DataType::TYPE_STRING;
								break;

							case 'Number':
								$type = PHPExcel_Cell_DataType::TYPE_NUMERIC;
								$cellValue = (double) $cellValue;

								if (floor($cellValue) == $cellValue) {
									$cellValue = (int) $cellValue;
								}

								break;

							case 'Boolean':
								$type = PHPExcel_Cell_DataType::TYPE_BOOL;
								$cellValue = $cellValue != 0;
								break;

							case 'DateTime':
								$type = PHPExcel_Cell_DataType::TYPE_NUMERIC;
								$cellValue = PHPExcel_Shared_Date::PHPToExcel(strtotime($cellValue));
								break;

							case 'Error':
								$type = PHPExcel_Cell_DataType::TYPE_ERROR;
								break;
							}
						}

						if ($hasCalculatedValue) {
							$type = PHPExcel_Cell_DataType::TYPE_FORMULA;
							$columnNumber = PHPExcel_Cell::columnIndexFromString($columnID);
							$temp = explode('"', $cellDataFormula);

							foreach ($temp as $key => &$value) {
								if (($key % 2) == 0) {
									preg_match_all('/(R(\\[?-?\\d*\\]?))(C(\\[?-?\\d*\\]?))/', $value, $cellReferences, PREG_SET_ORDER + PREG_OFFSET_CAPTURE);
									$cellReferences = array_reverse($cellReferences);

									foreach ($cellReferences as $cellReference) {
										$rowReference = $cellReference[2][0];

										if ($rowReference == '') {
											$rowReference = $rowID;
										}

										if ($rowReference[0] == '[') {
											$rowReference = $rowID + trim($rowReference, '[]');
										}

										$columnReference = $cellReference[4][0];

										if ($columnReference == '') {
											$columnReference = $columnNumber;
										}

										if ($columnReference[0] == '[') {
											$columnReference = $columnNumber + trim($columnReference, '[]');
										}

										$A1CellReference = PHPExcel_Cell::stringFromColumnIndex($columnReference - 1) . $rowReference;
										$value = substr_replace($value, $A1CellReference, $cellReference[0][1], strlen($cellReference[0][0]));
									}
								}
							}

							unset($value);
							$cellDataFormula = implode('"', $temp);
						}

						$objPHPExcel->getActiveSheet()->getCell($columnID . $rowID)->setValueExplicit($hasCalculatedValue ? $cellDataFormula : $cellValue, $type);

						if ($hasCalculatedValue) {
							$objPHPExcel->getActiveSheet()->getCell($columnID . $rowID)->setCalculatedValue($cellValue);
						}
					}

					if (isset($cell_ss['StyleID'])) {
						$style = (string) $cell_ss['StyleID'];
						if (isset($this->_styles[$style]) && (0 < count($this->_styles[$style]))) {
							if (!$objPHPExcel->getActiveSheet()->cellExists($columnID . $rowID)) {
								$objPHPExcel->getActiveSheet()->getCell($columnID . $rowID)->setValue(NULL);
							}

							$objPHPExcel->getActiveSheet()->getStyle($cellRange)->applyFromArray($this->_styles[$style]);
						}
					}

					++$columnID;
				}

				++$rowID;
			}

			++$worksheetID;
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

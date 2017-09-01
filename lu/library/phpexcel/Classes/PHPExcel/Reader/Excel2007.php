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

class PHPExcel_Reader_Excel2007 implements PHPExcel_Reader_IReader
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
			$rels = simplexml_load_string($this->_getFromZipArchive($zip, '_rels/.rels'));
			$zip->close();
			return $rels !== false;
		}

		return false;
	}

	private function _castToBool($c)
	{
		$value = (isset($c->v) ? (string) $c->v : NULL);

		if ($value == '0') {
			$value = false;
		}
		else if ($value == '1') {
			$value = true;
		}
		else {
			$value = (bool) $c->v;
		}

		return $value;
	}

	private function _castToError($c)
	{
		return isset($c->v) ? (string) $c->v : NULL;
	}

	private function _castToString($c)
	{
		return isset($c->v) ? (string) $c->v : NULL;
	}

	private function _castToFormula($c, $r, &$cellDataType, &$value, &$calculatedValue, &$sharedFormulas, $castBaseType)
	{
		$cellDataType = 'f';
		$value = '=' . $c->f;
		$calculatedValue = $this->$castBaseType($c);
		if (isset($c->f['t']) && (strtolower((string) $c->f['t']) == 'shared')) {
			$instance = (string) $c->f['si'];

			if (!isset($sharedFormulas[(string) $c->f['si']])) {
				$sharedFormulas[$instance] = array('master' => $r, 'formula' => $value);
			}
			else {
				$master = PHPExcel_Cell::coordinateFromString($sharedFormulas[$instance]['master']);
				$current = PHPExcel_Cell::coordinateFromString($r);
				$difference = array(0, 0);
				$difference[0] = PHPExcel_Cell::columnIndexFromString($current[0]) - PHPExcel_Cell::columnIndexFromString($master[0]);
				$difference[1] = $current[1] - $master[1];
				$helper = PHPExcel_ReferenceHelper::getInstance();
				$value = $helper->updateFormulaReferences($sharedFormulas[$instance]['formula'], 'A1', $difference[0], $difference[1]);
			}
		}
	}

	public function _getFromZipArchive(ZipArchive $archive, $fileName = '')
	{
		if (strpos($fileName, '//') !== false) {
			$fileName = substr($fileName, strpos($fileName, '//') + 1);
		}

		$fileName = PHPExcel_Shared_File::realpath($fileName);
		$contents = $archive->getFromName($fileName);

		if ($contents === false) {
			$contents = $archive->getFromName(substr($fileName, 1));
		}

		return $contents;
	}

	public function load($pFilename)
	{
		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		$excel = new PHPExcel();
		$excel->removeSheetByIndex(0);

		if (!$this->_readDataOnly) {
			$excel->removeCellStyleXfByIndex(0);
			$excel->removeCellXfByIndex(0);
		}

		$zip = new ZipArchive();
		$zip->open($pFilename);
		$rels = simplexml_load_string($this->_getFromZipArchive($zip, '_rels/.rels'));

		foreach ($rels->Relationship as $rel) {
			switch ($rel['Type']) {
			case 'http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties':
				$xmlCore = simplexml_load_string($this->_getFromZipArchive($zip, $rel['Target']));

				if ($xmlCore) {
					$xmlCore->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
					$xmlCore->registerXPathNamespace('dcterms', 'http://purl.org/dc/terms/');
					$xmlCore->registerXPathNamespace('cp', 'http://schemas.openxmlformats.org/package/2006/metadata/core-properties');
					$docProps = $excel->getProperties();
					$docProps->setCreator((string) self::array_item($xmlCore->xpath('dc:creator')));
					$docProps->setLastModifiedBy((string) self::array_item($xmlCore->xpath('cp:lastModifiedBy')));
					$docProps->setCreated(strtotime(self::array_item($xmlCore->xpath('dcterms:created'))));
					$docProps->setModified(strtotime(self::array_item($xmlCore->xpath('dcterms:modified'))));
					$docProps->setTitle((string) self::array_item($xmlCore->xpath('dc:title')));
					$docProps->setDescription((string) self::array_item($xmlCore->xpath('dc:description')));
					$docProps->setSubject((string) self::array_item($xmlCore->xpath('dc:subject')));
					$docProps->setKeywords((string) self::array_item($xmlCore->xpath('cp:keywords')));
					$docProps->setCategory((string) self::array_item($xmlCore->xpath('cp:category')));
				}

				break;

			case 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument':
				$dir = dirname($rel['Target']);
				$relsWorkbook = simplexml_load_string($this->_getFromZipArchive($zip, $dir . '/_rels/' . basename($rel['Target']) . '.rels'));
				$relsWorkbook->registerXPathNamespace('rel', 'http://schemas.openxmlformats.org/package/2006/relationships');
				$sharedStrings = array();
				$xpath = self::array_item($relsWorkbook->xpath('rel:Relationship[@Type=\'http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings\']'));
				$xmlStrings = simplexml_load_string($this->_getFromZipArchive($zip, $dir . '/' . $xpath['Target']));
				if (isset($xmlStrings) && isset($xmlStrings->si)) {
					foreach ($xmlStrings->si as $val) {
						if (isset($val->t)) {
							$sharedStrings[] = PHPExcel_Shared_String::ControlCharacterOOXML2PHP((string) $val->t);
						}
						else if (isset($val->r)) {
							$sharedStrings[] = $this->_parseRichText($val);
						}
					}
				}

				$worksheets = array();

				foreach ($relsWorkbook->Relationship as $ele) {
					if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet') {
						$worksheets[(string) $ele['Id']] = $ele['Target'];
					}
				}

				$styles = array();
				$cellStyles = array();
				$xpath = self::array_item($relsWorkbook->xpath('rel:Relationship[@Type=\'http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles\']'));
				$xmlStyles = simplexml_load_string($this->_getFromZipArchive($zip, $dir . '/' . $xpath['Target']));
				$numFmts = NULL;
				if ($xmlStyles && $xmlStyles->numFmts[0]) {
					$numFmts = $xmlStyles->numFmts[0];
				}

				if (isset($numFmts) && !is_null($numFmts)) {
					$numFmts->registerXPathNamespace('sml', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
				}

				if (!$this->_readDataOnly && $xmlStyles) {
					foreach ($xmlStyles->cellXfs->xf as $xf) {
						$numFmt = PHPExcel_Style_NumberFormat::FORMAT_GENERAL;

						if ($xf['numFmtId']) {
							if (isset($numFmts)) {
								$tmpNumFmt = self::array_item($numFmts->xpath('sml:numFmt[@numFmtId=' . $xf['numFmtId'] . ']'));

								if (isset($tmpNumFmt['formatCode'])) {
									$numFmt = (string) $tmpNumFmt['formatCode'];
								}
							}

							if ((int) $xf['numFmtId'] < 164) {
								$numFmt = PHPExcel_Style_NumberFormat::builtInFormatCode((int) $xf['numFmtId']);
							}
						}

						$style = (object) array('numFmt' => $numFmt, 'font' => $xmlStyles->fonts->font[intval($xf['fontId'])], 'fill' => $xmlStyles->fills->fill[intval($xf['fillId'])], 'border' => $xmlStyles->borders->border[intval($xf['borderId'])], 'alignment' => $xf->alignment, 'protection' => $xf->protection);
						$styles[] = $style;
						$objStyle = new PHPExcel_Style();
						$this->_readStyle($objStyle, $style);
						$excel->addCellXf($objStyle);
					}

					foreach ($xmlStyles->cellStyleXfs->xf as $xf) {
						$numFmt = PHPExcel_Style_NumberFormat::FORMAT_GENERAL;
						if ($numFmts && $xf['numFmtId']) {
							$tmpNumFmt = self::array_item($numFmts->xpath('sml:numFmt[@numFmtId=' . $xf['numFmtId'] . ']'));

							if (isset($tmpNumFmt['formatCode'])) {
								$numFmt = (string) $tmpNumFmt['formatCode'];
							}
							else if ((int) $xf['numFmtId'] < 165) {
								$numFmt = PHPExcel_Style_NumberFormat::builtInFormatCode((int) $xf['numFmtId']);
							}
						}

						$cellStyle = (object) array('numFmt' => $numFmt, 'font' => $xmlStyles->fonts->font[intval($xf['fontId'])], 'fill' => $xmlStyles->fills->fill[intval($xf['fillId'])], 'border' => $xmlStyles->borders->border[intval($xf['borderId'])], 'alignment' => $xf->alignment, 'protection' => $xf->protection);
						$cellStyles[] = $cellStyle;
						$objStyle = new PHPExcel_Style();
						$this->_readStyle($objStyle, $cellStyle);
						$excel->addCellStyleXf($objStyle);
					}
				}

				$dxfs = array();
				if (!$this->_readDataOnly && $xmlStyles) {
					if ($xmlStyles->dxfs) {
						foreach ($xmlStyles->dxfs->dxf as $dxf) {
							$style = new PHPExcel_Style();
							$this->_readStyle($style, $dxf);
							$dxfs[] = $style;
						}
					}

					if ($xmlStyles->cellStyles) {
						foreach ($xmlStyles->cellStyles->cellStyle as $cellStyle) {
							if (intval($cellStyle['builtinId']) == 0) {
								if (isset($cellStyles[intval($cellStyle['xfId'])])) {
									$style = new PHPExcel_Style();
									$this->_readStyle($style, $cellStyles[intval($cellStyle['xfId'])]);
								}
							}
						}
					}
				}

				$xmlWorkbook = simplexml_load_string($this->_getFromZipArchive($zip, $rel['Target']));

				if ($xmlWorkbook->workbookPr) {
					PHPExcel_Shared_Date::setExcelCalendar(PHPExcel_Shared_Date::CALENDAR_WINDOWS_1900);

					if (isset($xmlWorkbook->workbookPr['date1904'])) {
						$date1904 = (string) $xmlWorkbook->workbookPr['date1904'];
						if (($date1904 == 'true') || ($date1904 == '1')) {
							PHPExcel_Shared_Date::setExcelCalendar(PHPExcel_Shared_Date::CALENDAR_MAC_1904);
						}
					}
				}

				$sheetId = 0;
				$oldSheetId = -1;
				$countSkippedSheets = 0;
				$mapSheetId = array();

				if ($xmlWorkbook->sheets) {
					foreach ($xmlWorkbook->sheets->sheet as $eleSheet) {
						++$oldSheetId;
						if (isset($this->_loadSheetsOnly) && !in_array((string) $eleSheet['name'], $this->_loadSheetsOnly)) {
							++$countSkippedSheets;
							$mapSheetId[$oldSheetId] = NULL;
							continue;
						}

						$mapSheetId[$oldSheetId] = $oldSheetId - $countSkippedSheets;
						$docSheet = $excel->createSheet();
						$docSheet->setTitle((string) $eleSheet['name']);
						$fileWorksheet = $worksheets[(string) self::array_item($eleSheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships'), 'id')];
						$xmlSheet = simplexml_load_string($this->_getFromZipArchive($zip, $dir . '/' . $fileWorksheet));
						$sharedFormulas = array();
						if (isset($eleSheet['state']) && ((string) $eleSheet['state'] != '')) {
							$docSheet->setSheetState((string) $eleSheet['state']);
						}

						if (isset($xmlSheet->sheetViews) && isset($xmlSheet->sheetViews->sheetView)) {
							if (isset($xmlSheet->sheetViews->sheetView['zoomScale'])) {
								$docSheet->getSheetView()->setZoomScale(intval($xmlSheet->sheetViews->sheetView['zoomScale']));
							}

							if (isset($xmlSheet->sheetViews->sheetView['zoomScaleNormal'])) {
								$docSheet->getSheetView()->setZoomScaleNormal(intval($xmlSheet->sheetViews->sheetView['zoomScaleNormal']));
							}

							if (isset($xmlSheet->sheetViews->sheetView['showGridLines'])) {
								$docSheet->setShowGridLines((string) $xmlSheet->sheetViews->sheetView['showGridLines'] ? true : false);
							}

							if (isset($xmlSheet->sheetViews->sheetView['showRowColHeaders'])) {
								$docSheet->setShowRowColHeaders((string) $xmlSheet->sheetViews->sheetView['showRowColHeaders'] ? true : false);
							}

							if (isset($xmlSheet->sheetViews->sheetView['rightToLeft'])) {
								$docSheet->setRightToLeft((string) $xmlSheet->sheetViews->sheetView['rightToLeft'] ? true : false);
							}

							if (isset($xmlSheet->sheetViews->sheetView->pane)) {
								if (isset($xmlSheet->sheetViews->sheetView->pane['topLeftCell'])) {
									$docSheet->freezePane((string) $xmlSheet->sheetViews->sheetView->pane['topLeftCell']);
								}
								else {
									$xSplit = 0;
									$ySplit = 0;

									if (isset($xmlSheet->sheetViews->sheetView->pane['xSplit'])) {
										$xSplit = 1 + intval($xmlSheet->sheetViews->sheetView->pane['xSplit']);
									}

									if (isset($xmlSheet->sheetViews->sheetView->pane['ySplit'])) {
										$ySplit = 1 + intval($xmlSheet->sheetViews->sheetView->pane['ySplit']);
									}

									$docSheet->freezePaneByColumnAndRow($xSplit, $ySplit);
								}
							}

							if (isset($xmlSheet->sheetViews->sheetView->selection)) {
								if (isset($xmlSheet->sheetViews->sheetView->selection['sqref'])) {
									$sqref = (string) $xmlSheet->sheetViews->sheetView->selection['sqref'];
									$sqref = explode(' ', $sqref);
									$sqref = $sqref[0];
									$docSheet->setSelectedCells($sqref);
								}
							}
						}

						if (isset($xmlSheet->sheetPr) && isset($xmlSheet->sheetPr->tabColor)) {
							if (isset($xmlSheet->sheetPr->tabColor['rgb'])) {
								$docSheet->getTabColor()->setARGB((string) $xmlSheet->sheetPr->tabColor['rgb']);
							}
						}

						if (isset($xmlSheet->sheetPr) && isset($xmlSheet->sheetPr->outlinePr)) {
							if (isset($xmlSheet->sheetPr->outlinePr['summaryRight']) && ($xmlSheet->sheetPr->outlinePr['summaryRight'] == false)) {
								$docSheet->setShowSummaryRight(false);
							}
							else {
								$docSheet->setShowSummaryRight(true);
							}

							if (isset($xmlSheet->sheetPr->outlinePr['summaryBelow']) && ($xmlSheet->sheetPr->outlinePr['summaryBelow'] == false)) {
								$docSheet->setShowSummaryBelow(false);
							}
							else {
								$docSheet->setShowSummaryBelow(true);
							}
						}

						if (isset($xmlSheet->sheetPr) && isset($xmlSheet->sheetPr->pageSetUpPr)) {
							if (isset($xmlSheet->sheetPr->pageSetUpPr['fitToPage']) && ($xmlSheet->sheetPr->pageSetUpPr['fitToPage'] == false)) {
								$docSheet->getPageSetup()->setFitToPage(false);
							}
							else {
								$docSheet->getPageSetup()->setFitToPage(true);
							}
						}

						if (isset($xmlSheet->sheetFormatPr)) {
							if (isset($xmlSheet->sheetFormatPr['customHeight']) && (((string) $xmlSheet->sheetFormatPr['customHeight'] == '1') || (strtolower((string) $xmlSheet->sheetFormatPr['customHeight']) == 'true')) && isset($xmlSheet->sheetFormatPr['defaultRowHeight'])) {
								$docSheet->getDefaultRowDimension()->setRowHeight((double) $xmlSheet->sheetFormatPr['defaultRowHeight']);
							}

							if (isset($xmlSheet->sheetFormatPr['defaultColWidth'])) {
								$docSheet->getDefaultColumnDimension()->setWidth((double) $xmlSheet->sheetFormatPr['defaultColWidth']);
							}
						}

						if (isset($xmlSheet->cols) && !$this->_readDataOnly) {
							foreach ($xmlSheet->cols->col as $col) {
								for ($i = intval($col['min']) - 1; $i < intval($col['max']); ++$i) {
									if ($col['style'] && !$this->_readDataOnly) {
										$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setXfIndex(intval($col['style']));
									}

									if ($col['bestFit']) {
									}

									if ($col['hidden']) {
										$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setVisible(false);
									}

									if ($col['collapsed']) {
										$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setCollapsed(true);
									}

									if (0 < $col['outlineLevel']) {
										$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setOutlineLevel(intval($col['outlineLevel']));
									}

									$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setWidth(floatval($col['width']));

									if (intval($col['max']) == 16384) {
										break;
									}
								}
							}
						}

						if (isset($xmlSheet->printOptions) && !$this->_readDataOnly) {
							if (($xmlSheet->printOptions['gridLinesSet'] == 'true') && ($xmlSheet->printOptions['gridLinesSet'] == '1')) {
								$docSheet->setShowGridlines(true);
							}

							if (($xmlSheet->printOptions['gridLines'] == 'true') || ($xmlSheet->printOptions['gridLines'] == '1')) {
								$docSheet->setPrintGridlines(true);
							}

							if ($xmlSheet->printOptions['horizontalCentered']) {
								$docSheet->getPageSetup()->setHorizontalCentered(true);
							}

							if ($xmlSheet->printOptions['verticalCentered']) {
								$docSheet->getPageSetup()->setVerticalCentered(true);
							}
						}

						if ($xmlSheet && $xmlSheet->sheetData && $xmlSheet->sheetData->row) {
							foreach ($xmlSheet->sheetData->row as $row) {
								if ($row['ht'] && !$this->_readDataOnly) {
									$docSheet->getRowDimension(intval($row['r']))->setRowHeight(floatval($row['ht']));
								}

								if ($row['hidden'] && !$this->_readDataOnly) {
									$docSheet->getRowDimension(intval($row['r']))->setVisible(false);
								}

								if ($row['collapsed']) {
									$docSheet->getRowDimension(intval($row['r']))->setCollapsed(true);
								}

								if (0 < $row['outlineLevel']) {
									$docSheet->getRowDimension(intval($row['r']))->setOutlineLevel(intval($row['outlineLevel']));
								}

								if ($row['s'] && !$this->_readDataOnly) {
									$docSheet->getRowDimension(intval($row['r']))->setXfIndex(intval($row['s']));
								}

								foreach ($row->c as $c) {
									$r = (string) $c['r'];
									$cellDataType = (string) $c['t'];
									$value = NULL;
									$calculatedValue = NULL;

									if (!is_null($this->getReadFilter())) {
										$coordinates = PHPExcel_Cell::coordinateFromString($r);

										if (!$this->getReadFilter()->readCell($coordinates[0], $coordinates[1], $docSheet->getTitle())) {
											continue;
										}
									}

									switch ($cellDataType) {
									case 's':
										if ((string) $c->v != '') {
											$value = $sharedStrings[intval($c->v)];

											if ($value instanceof PHPExcel_RichText) {
												$value = clone $value;
											}
										}
										else {
											$value = '';
										}

										break;

									case 'b':
										if (!isset($c->f)) {
											$value = $this->_castToBool($c);
										}
										else {
											$this->_castToFormula($c, $r, $cellDataType, $value, $calculatedValue, $sharedFormulas, '_castToBool');
										}

										break;

									case 'inlineStr':
										$value = $this->_parseRichText($c->is);
										break;

									case 'e':
										if (!isset($c->f)) {
											$value = $this->_castToError($c);
										}
										else {
											$this->_castToFormula($c, $r, $cellDataType, $value, $calculatedValue, $sharedFormulas, '_castToError');
										}

										break;

									default:
										if (!isset($c->f)) {
											$value = $this->_castToString($c);
										}
										else {
											$this->_castToFormula($c, $r, $cellDataType, $value, $calculatedValue, $sharedFormulas, '_castToString');
										}

										break;
									}

									if (is_numeric($value) && ($cellDataType != 's')) {
										if ($value == (int) $value) {
											$value = (int) $value;
										}
										else if ($value == (double) $value) {
											$value = (double) $value;
										}
										else if ($value == (double) $value) {
											$value = (double) $value;
										}
									}

									if ($value instanceof PHPExcel_RichText && $this->_readDataOnly) {
										$value = $value->getPlainText();
									}

									$cell = $docSheet->getCell($r);

									if ($cellDataType != '') {
										$cell->setValueExplicit($value, $cellDataType);
									}
									else {
										$cell->setValue($value);
									}

									if (!is_null($calculatedValue)) {
										$cell->setCalculatedValue($calculatedValue);
									}

									if ($c['s'] && !$this->_readDataOnly) {
										$cell->setXfIndex(isset($styles[intval($c['s'])]) ? intval($c['s']) : 0);
									}
								}
							}
						}

						$conditionals = array();
						if (!$this->_readDataOnly && $xmlSheet && $xmlSheet->conditionalFormatting) {
							foreach ($xmlSheet->conditionalFormatting as $conditional) {
								foreach ($conditional->cfRule as $cfRule) {
									if ((((string) $cfRule['type'] == PHPExcel_Style_Conditional::CONDITION_NONE) || ((string) $cfRule['type'] == PHPExcel_Style_Conditional::CONDITION_CELLIS) || ((string) $cfRule['type'] == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT) || ((string) $cfRule['type'] == PHPExcel_Style_Conditional::CONDITION_EXPRESSION)) && isset($dxfs[intval($cfRule['dxfId'])])) {
										$conditionals[(string) $conditional['sqref']][intval($cfRule['priority'])] = $cfRule;
									}
								}
							}

							foreach ($conditionals as $ref => $cfRules) {
								ksort($cfRules);
								$conditionalStyles = array();

								foreach ($cfRules as $cfRule) {
									$objConditional = new PHPExcel_Style_Conditional();
									$objConditional->setConditionType((string) $cfRule['type']);
									$objConditional->setOperatorType((string) $cfRule['operator']);

									if ((string) $cfRule['text'] != '') {
										$objConditional->setText((string) $cfRule['text']);
									}

									if (1 < count($cfRule->formula)) {
										foreach ($cfRule->formula as $formula) {
											$objConditional->addCondition((string) $formula);
										}
									}
									else {
										$objConditional->addCondition((string) $cfRule->formula);
									}

									$objConditional->setStyle(clone $dxfs[intval($cfRule['dxfId'])]);
									$conditionalStyles[] = $objConditional;
								}

								$aReferences = PHPExcel_Cell::extractAllCellReferencesInRange($ref);

								foreach ($aReferences as $reference) {
									$docSheet->getStyle($reference)->setConditionalStyles($conditionalStyles);
								}
							}
						}

						$aKeys = array('sheet', 'objects', 'scenarios', 'formatCells', 'formatColumns', 'formatRows', 'insertColumns', 'insertRows', 'insertHyperlinks', 'deleteColumns', 'deleteRows', 'selectLockedCells', 'sort', 'autoFilter', 'pivotTables', 'selectUnlockedCells');
						if (!$this->_readDataOnly && $xmlSheet && $xmlSheet->sheetProtection) {
							foreach ($aKeys as $key) {
								$method = 'set' . ucfirst($key);
								$docSheet->getProtection()->$method($xmlSheet->sheetProtection[$key] == 'true');
							}
						}

						if (!$this->_readDataOnly && $xmlSheet && $xmlSheet->sheetProtection) {
							$docSheet->getProtection()->setPassword((string) $xmlSheet->sheetProtection['password'], true);

							if ($xmlSheet->protectedRanges->protectedRange) {
								foreach ($xmlSheet->protectedRanges->protectedRange as $protectedRange) {
									$docSheet->protectCells((string) $protectedRange['sqref'], (string) $protectedRange['password'], true);
								}
							}
						}

						if ($xmlSheet && $xmlSheet->autoFilter && !$this->_readDataOnly) {
							$docSheet->setAutoFilter((string) $xmlSheet->autoFilter['ref']);
						}

						if ($xmlSheet && $xmlSheet->mergeCells && $xmlSheet->mergeCells->mergeCell && !$this->_readDataOnly) {
							foreach ($xmlSheet->mergeCells->mergeCell as $mergeCell) {
								$docSheet->mergeCells((string) $mergeCell['ref']);
							}
						}

						if ($xmlSheet && $xmlSheet->pageMargins && !$this->_readDataOnly) {
							$docPageMargins = $docSheet->getPageMargins();
							$docPageMargins->setLeft(floatval($xmlSheet->pageMargins['left']));
							$docPageMargins->setRight(floatval($xmlSheet->pageMargins['right']));
							$docPageMargins->setTop(floatval($xmlSheet->pageMargins['top']));
							$docPageMargins->setBottom(floatval($xmlSheet->pageMargins['bottom']));
							$docPageMargins->setHeader(floatval($xmlSheet->pageMargins['header']));
							$docPageMargins->setFooter(floatval($xmlSheet->pageMargins['footer']));
						}

						if ($xmlSheet && $xmlSheet->pageSetup && !$this->_readDataOnly) {
							$docPageSetup = $docSheet->getPageSetup();

							if (isset($xmlSheet->pageSetup['orientation'])) {
								$docPageSetup->setOrientation((string) $xmlSheet->pageSetup['orientation']);
							}

							if (isset($xmlSheet->pageSetup['paperSize'])) {
								$docPageSetup->setPaperSize(intval($xmlSheet->pageSetup['paperSize']));
							}

							if (isset($xmlSheet->pageSetup['scale'])) {
								$docPageSetup->setScale(intval($xmlSheet->pageSetup['scale']), false);
							}

							if (isset($xmlSheet->pageSetup['fitToHeight']) && (0 <= intval($xmlSheet->pageSetup['fitToHeight']))) {
								$docPageSetup->setFitToHeight(intval($xmlSheet->pageSetup['fitToHeight']), false);
							}

							if (isset($xmlSheet->pageSetup['fitToWidth']) && (0 <= intval($xmlSheet->pageSetup['fitToWidth']))) {
								$docPageSetup->setFitToWidth(intval($xmlSheet->pageSetup['fitToWidth']), false);
							}

							if (isset($xmlSheet->pageSetup['firstPageNumber']) && isset($xmlSheet->pageSetup['useFirstPageNumber']) && (((string) $xmlSheet->pageSetup['useFirstPageNumber'] == 'true') || ((string) $xmlSheet->pageSetup['useFirstPageNumber'] == '1'))) {
								$docPageSetup->setFirstPageNumber(intval($xmlSheet->pageSetup['firstPageNumber']));
							}
						}

						if ($xmlSheet && $xmlSheet->headerFooter && !$this->_readDataOnly) {
							$docHeaderFooter = $docSheet->getHeaderFooter();
							if (isset($xmlSheet->headerFooter['differentOddEven']) && (((string) $xmlSheet->headerFooter['differentOddEven'] == 'true') || ((string) $xmlSheet->headerFooter['differentOddEven'] == '1'))) {
								$docHeaderFooter->setDifferentOddEven(true);
							}
							else {
								$docHeaderFooter->setDifferentOddEven(false);
							}

							if (isset($xmlSheet->headerFooter['differentFirst']) && (((string) $xmlSheet->headerFooter['differentFirst'] == 'true') || ((string) $xmlSheet->headerFooter['differentFirst'] == '1'))) {
								$docHeaderFooter->setDifferentFirst(true);
							}
							else {
								$docHeaderFooter->setDifferentFirst(false);
							}

							if (isset($xmlSheet->headerFooter['scaleWithDoc']) && (((string) $xmlSheet->headerFooter['scaleWithDoc'] == 'false') || ((string) $xmlSheet->headerFooter['scaleWithDoc'] == '0'))) {
								$docHeaderFooter->setScaleWithDocument(false);
							}
							else {
								$docHeaderFooter->setScaleWithDocument(true);
							}

							if (isset($xmlSheet->headerFooter['alignWithMargins']) && (((string) $xmlSheet->headerFooter['alignWithMargins'] == 'false') || ((string) $xmlSheet->headerFooter['alignWithMargins'] == '0'))) {
								$docHeaderFooter->setAlignWithMargins(false);
							}
							else {
								$docHeaderFooter->setAlignWithMargins(true);
							}

							$docHeaderFooter->setOddHeader((string) $xmlSheet->headerFooter->oddHeader);
							$docHeaderFooter->setOddFooter((string) $xmlSheet->headerFooter->oddFooter);
							$docHeaderFooter->setEvenHeader((string) $xmlSheet->headerFooter->evenHeader);
							$docHeaderFooter->setEvenFooter((string) $xmlSheet->headerFooter->evenFooter);
							$docHeaderFooter->setFirstHeader((string) $xmlSheet->headerFooter->firstHeader);
							$docHeaderFooter->setFirstFooter((string) $xmlSheet->headerFooter->firstFooter);
						}

						if ($xmlSheet && $xmlSheet->rowBreaks && $xmlSheet->rowBreaks->brk && !$this->_readDataOnly) {
							foreach ($xmlSheet->rowBreaks->brk as $brk) {
								if ($brk['man']) {
									$docSheet->setBreak('A' . $brk['id'], PHPExcel_Worksheet::BREAK_ROW);
								}
							}
						}

						if ($xmlSheet && $xmlSheet->colBreaks && $xmlSheet->colBreaks->brk && !$this->_readDataOnly) {
							foreach ($xmlSheet->colBreaks->brk as $brk) {
								if ($brk['man']) {
									$docSheet->setBreak(PHPExcel_Cell::stringFromColumnIndex($brk['id']) . '1', PHPExcel_Worksheet::BREAK_COLUMN);
								}
							}
						}

						if ($xmlSheet && $xmlSheet->dataValidations && !$this->_readDataOnly) {
							foreach ($xmlSheet->dataValidations->dataValidation as $dataValidation) {
								$range = strtoupper($dataValidation['sqref']);
								$rangeSet = explode(' ', $range);

								foreach ($rangeSet as $range) {
									$stRange = $docSheet->shrinkRangeToFit($range);
									$aReferences = PHPExcel_Cell::extractAllCellReferencesInRange($stRange);

									foreach ($aReferences as $reference) {
										$docValidation = $docSheet->getCell($reference)->getDataValidation();
										$docValidation->setType((string) $dataValidation['type']);
										$docValidation->setErrorStyle((string) $dataValidation['errorStyle']);
										$docValidation->setOperator((string) $dataValidation['operator']);
										$docValidation->setAllowBlank($dataValidation['allowBlank'] != 0);
										$docValidation->setShowDropDown($dataValidation['showDropDown'] == 0);
										$docValidation->setShowInputMessage($dataValidation['showInputMessage'] != 0);
										$docValidation->setShowErrorMessage($dataValidation['showErrorMessage'] != 0);
										$docValidation->setErrorTitle((string) $dataValidation['errorTitle']);
										$docValidation->setError((string) $dataValidation['error']);
										$docValidation->setPromptTitle((string) $dataValidation['promptTitle']);
										$docValidation->setPrompt((string) $dataValidation['prompt']);
										$docValidation->setFormula1((string) $dataValidation->formula1);
										$docValidation->setFormula2((string) $dataValidation->formula2);
									}
								}
							}
						}

						$hyperlinks = array();

						if (!$this->_readDataOnly) {
							if ($zip->locateName(dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels')) {
								$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip, dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels'));

								foreach ($relsWorksheet->Relationship as $ele) {
									if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink') {
										$hyperlinks[(string) $ele['Id']] = (string) $ele['Target'];
									}
								}
							}

							if ($xmlSheet && $xmlSheet->hyperlinks) {
								foreach ($xmlSheet->hyperlinks->hyperlink as $hyperlink) {
									$linkRel = $hyperlink->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');

									foreach (PHPExcel_Cell::extractAllCellReferencesInRange($hyperlink['ref']) as $cellReference) {
										$cell = $docSheet->getCell($cellReference);

										if (isset($linkRel['id'])) {
											$cell->getHyperlink()->setUrl($hyperlinks[(string) $linkRel['id']]);
										}

										if (isset($hyperlink['location'])) {
											$cell->getHyperlink()->setUrl('sheet://' . (string) $hyperlink['location']);
										}

										if (isset($hyperlink['tooltip'])) {
											$cell->getHyperlink()->setTooltip((string) $hyperlink['tooltip']);
										}
									}
								}
							}
						}

						$comments = array();
						$vmlComments = array();

						if (!$this->_readDataOnly) {
							if ($zip->locateName(dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels')) {
								$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip, dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels'));

								foreach ($relsWorksheet->Relationship as $ele) {
									if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/comments') {
										$comments[(string) $ele['Id']] = (string) $ele['Target'];
									}

									if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/vmlDrawing') {
										$vmlComments[(string) $ele['Id']] = (string) $ele['Target'];
									}
								}
							}

							foreach ($comments as $relName => $relPath) {
								$relPath = PHPExcel_Shared_File::realpath(dirname($dir . '/' . $fileWorksheet) . '/' . $relPath);
								$commentsFile = simplexml_load_string($this->_getFromZipArchive($zip, $relPath));
								$authors = array();

								foreach ($commentsFile->authors->author as $author) {
									$authors[] = (string) $author;
								}

								foreach ($commentsFile->commentList->comment as $comment) {
									$docSheet->getComment((string) $comment['ref'])->setAuthor($authors[(string) $comment['authorId']]);
									$docSheet->getComment((string) $comment['ref'])->setText($this->_parseRichText($comment->text));
								}
							}

							foreach ($vmlComments as $relName => $relPath) {
								$relPath = PHPExcel_Shared_File::realpath(dirname($dir . '/' . $fileWorksheet) . '/' . $relPath);
								$vmlCommentsFile = simplexml_load_string($this->_getFromZipArchive($zip, $relPath));
								$vmlCommentsFile->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');
								$shapes = $vmlCommentsFile->xpath('//v:shape');

								foreach ($shapes as $shape) {
									$shape->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');

									if (isset($shape['style'])) {
										$style = (string) $shape['style'];
										$fillColor = strtoupper(substr((string) $shape['fillcolor'], 1));
										$column = NULL;
										$row = NULL;
										$clientData = $shape->xpath('.//x:ClientData');
										if (is_array($clientData) && (0 < count($clientData))) {
											$clientData = $clientData[0];
											if (isset($clientData['ObjectType']) && ((string) $clientData['ObjectType'] == 'Note')) {
												$temp = $clientData->xpath('.//x:Row');

												if (is_array($temp)) {
													$row = $temp[0];
												}

												$temp = $clientData->xpath('.//x:Column');

												if (is_array($temp)) {
													$column = $temp[0];
												}
											}
										}

										if (!is_null($column) && !is_null($row)) {
											$comment = $docSheet->getCommentByColumnAndRow($column, $row + 1);
											$comment->getFillColor()->setRGB($fillColor);
											$styleArray = explode(';', str_replace(' ', '', $style));

											foreach ($styleArray as $stylePair) {
												$stylePair = explode(':', $stylePair);

												if ($stylePair[0] == 'margin-left') {
													$comment->setMarginLeft($stylePair[1]);
												}

												if ($stylePair[0] == 'margin-top') {
													$comment->setMarginTop($stylePair[1]);
												}

												if ($stylePair[0] == 'width') {
													$comment->setWidth($stylePair[1]);
												}

												if ($stylePair[0] == 'height') {
													$comment->setHeight($stylePair[1]);
												}

												if ($stylePair[0] == 'visibility') {
													$comment->setVisible($stylePair[1] == 'visible');
												}
											}
										}
									}
								}
							}

							if ($xmlSheet && $xmlSheet->legacyDrawingHF && !$this->_readDataOnly) {
								if ($zip->locateName(dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels')) {
									$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip, dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels'));
									$vmlRelationship = '';

									foreach ($relsWorksheet->Relationship as $ele) {
										if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/vmlDrawing') {
											$vmlRelationship = self::dir_add($dir . '/' . $fileWorksheet, $ele['Target']);
										}
									}

									if ($vmlRelationship != '') {
										$relsVML = simplexml_load_string($this->_getFromZipArchive($zip, dirname($vmlRelationship) . '/_rels/' . basename($vmlRelationship) . '.rels'));
										$drawings = array();

										foreach ($relsVML->Relationship as $ele) {
											if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image') {
												$drawings[(string) $ele['Id']] = self::dir_add($vmlRelationship, $ele['Target']);
											}
										}

										$vmlDrawing = simplexml_load_string($this->_getFromZipArchive($zip, $vmlRelationship));
										$vmlDrawing->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');
										$hfImages = array();
										$shapes = $vmlDrawing->xpath('//v:shape');

										foreach ($shapes as $shape) {
											$shape->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');
											$imageData = $shape->xpath('//v:imagedata');
											$imageData = $imageData[0];
											$imageData = $imageData->attributes('urn:schemas-microsoft-com:office:office');
											$style = self::toCSSArray((string) $shape['style']);
											$hfImages[(string) $shape['id']] = new PHPExcel_Worksheet_HeaderFooterDrawing();

											if (isset($imageData['title'])) {
												$hfImages[(string) $shape['id']]->setName((string) $imageData['title']);
											}

											$hfImages[(string) $shape['id']]->setPath('zip://' . $pFilename . '#' . $drawings[(string) $imageData['relid']], false);
											$hfImages[(string) $shape['id']]->setResizeProportional(false);
											$hfImages[(string) $shape['id']]->setWidth($style['width']);
											$hfImages[(string) $shape['id']]->setHeight($style['height']);
											$hfImages[(string) $shape['id']]->setOffsetX($style['margin-left']);
											$hfImages[(string) $shape['id']]->setOffsetY($style['margin-top']);
											$hfImages[(string) $shape['id']]->setResizeProportional(true);
										}

										$docSheet->getHeaderFooter()->setImages($hfImages);
									}
								}
							}
						}

						if ($zip->locateName(dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels')) {
							$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip, dirname($dir . '/' . $fileWorksheet) . '/_rels/' . basename($fileWorksheet) . '.rels'));
							$drawings = array();

							foreach ($relsWorksheet->Relationship as $ele) {
								if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing') {
									$drawings[(string) $ele['Id']] = self::dir_add($dir . '/' . $fileWorksheet, $ele['Target']);
								}
							}

							if ($xmlSheet->drawing && !$this->_readDataOnly) {
								foreach ($xmlSheet->drawing as $drawing) {
									$fileDrawing = $drawings[(string) self::array_item($drawing->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships'), 'id')];
									$relsDrawing = simplexml_load_string($this->_getFromZipArchive($zip, dirname($fileDrawing) . '/_rels/' . basename($fileDrawing) . '.rels'));
									$images = array();
									if ($relsDrawing && $relsDrawing->Relationship) {
										foreach ($relsDrawing->Relationship as $ele) {
											if ($ele['Type'] == 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image') {
												$images[(string) $ele['Id']] = self::dir_add($fileDrawing, $ele['Target']);
											}
										}
									}

									$xmlDrawing = simplexml_load_string($this->_getFromZipArchive($zip, $fileDrawing))->children('http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing');

									if ($xmlDrawing->oneCellAnchor) {
										foreach ($xmlDrawing->oneCellAnchor as $oneCellAnchor) {
											if ($oneCellAnchor->pic->blipFill) {
												$blip = $oneCellAnchor->pic->blipFill->children('http://schemas.openxmlformats.org/drawingml/2006/main')->blip;
												$xfrm = $oneCellAnchor->pic->spPr->children('http://schemas.openxmlformats.org/drawingml/2006/main')->xfrm;
												$outerShdw = $oneCellAnchor->pic->spPr->children('http://schemas.openxmlformats.org/drawingml/2006/main')->effectLst->outerShdw;
												$objDrawing = new PHPExcel_Worksheet_Drawing();
												$objDrawing->setName((string) self::array_item($oneCellAnchor->pic->nvPicPr->cNvPr->attributes(), 'name'));
												$objDrawing->setDescription((string) self::array_item($oneCellAnchor->pic->nvPicPr->cNvPr->attributes(), 'descr'));
												$objDrawing->setPath('zip://' . $pFilename . '#' . $images[(string) self::array_item($blip->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships'), 'embed')], false);
												$objDrawing->setCoordinates(PHPExcel_Cell::stringFromColumnIndex($oneCellAnchor->from->col) . ($oneCellAnchor->from->row + 1));
												$objDrawing->setOffsetX(PHPExcel_Shared_Drawing::EMUToPixels($oneCellAnchor->from->colOff));
												$objDrawing->setOffsetY(PHPExcel_Shared_Drawing::EMUToPixels($oneCellAnchor->from->rowOff));
												$objDrawing->setResizeProportional(false);
												$objDrawing->setWidth(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($oneCellAnchor->ext->attributes(), 'cx')));
												$objDrawing->setHeight(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($oneCellAnchor->ext->attributes(), 'cy')));

												if ($xfrm) {
													$objDrawing->setRotation(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($xfrm->attributes(), 'rot')));
												}

												if ($outerShdw) {
													$shadow = $objDrawing->getShadow();
													$shadow->setVisible(true);
													$shadow->setBlurRadius(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), 'blurRad')));
													$shadow->setDistance(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), 'dist')));
													$shadow->setDirection(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($outerShdw->attributes(), 'dir')));
													$shadow->setAlignment((string) self::array_item($outerShdw->attributes(), 'algn'));
													$shadow->getColor()->setRGB(self::array_item($outerShdw->srgbClr->attributes(), 'val'));
													$shadow->setAlpha(self::array_item($outerShdw->srgbClr->alpha->attributes(), 'val') / 1000);
												}

												$objDrawing->setWorksheet($docSheet);
											}
										}
									}

									if ($xmlDrawing->twoCellAnchor) {
										foreach ($xmlDrawing->twoCellAnchor as $twoCellAnchor) {
											if ($twoCellAnchor->pic->blipFill) {
												$blip = $twoCellAnchor->pic->blipFill->children('http://schemas.openxmlformats.org/drawingml/2006/main')->blip;
												$xfrm = $twoCellAnchor->pic->spPr->children('http://schemas.openxmlformats.org/drawingml/2006/main')->xfrm;
												$outerShdw = $twoCellAnchor->pic->spPr->children('http://schemas.openxmlformats.org/drawingml/2006/main')->effectLst->outerShdw;
												$objDrawing = new PHPExcel_Worksheet_Drawing();
												$objDrawing->setName((string) self::array_item($twoCellAnchor->pic->nvPicPr->cNvPr->attributes(), 'name'));
												$objDrawing->setDescription((string) self::array_item($twoCellAnchor->pic->nvPicPr->cNvPr->attributes(), 'descr'));
												$objDrawing->setPath('zip://' . $pFilename . '#' . $images[(string) self::array_item($blip->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships'), 'embed')], false);
												$objDrawing->setCoordinates(PHPExcel_Cell::stringFromColumnIndex($twoCellAnchor->from->col) . ($twoCellAnchor->from->row + 1));
												$objDrawing->setOffsetX(PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->from->colOff));
												$objDrawing->setOffsetY(PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->from->rowOff));
												$objDrawing->setResizeProportional(false);
												$objDrawing->setWidth(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($xfrm->ext->attributes(), 'cx')));
												$objDrawing->setHeight(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($xfrm->ext->attributes(), 'cy')));

												if ($xfrm) {
													$objDrawing->setRotation(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($xfrm->attributes(), 'rot')));
												}

												if ($outerShdw) {
													$shadow = $objDrawing->getShadow();
													$shadow->setVisible(true);
													$shadow->setBlurRadius(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), 'blurRad')));
													$shadow->setDistance(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), 'dist')));
													$shadow->setDirection(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($outerShdw->attributes(), 'dir')));
													$shadow->setAlignment((string) self::array_item($outerShdw->attributes(), 'algn'));
													$shadow->getColor()->setRGB(self::array_item($outerShdw->srgbClr->attributes(), 'val'));
													$shadow->setAlpha(self::array_item($outerShdw->srgbClr->alpha->attributes(), 'val') / 1000);
												}

												$objDrawing->setWorksheet($docSheet);
											}
										}
									}
								}
							}
						}

						if ($xmlWorkbook->definedNames) {
							foreach ($xmlWorkbook->definedNames->definedName as $definedName) {
								$extractedRange = (string) $definedName;
								$extractedRange = preg_replace('/\'(\\w+)\'\\!/', '', $extractedRange);
								$extractedRange = str_replace('$', '', $extractedRange);
								if ((stripos((string) $definedName, '#REF!') !== false) || ($extractedRange == '')) {
									continue;
								}

								if (((string) $definedName['localSheetId'] != '') && ((string) $definedName['localSheetId'] == $sheetId)) {
									switch ((string) $definedName['name']) {
									case '_xlnm._FilterDatabase':
										$docSheet->setAutoFilter($extractedRange);
										break;

									case '_xlnm.Print_Titles':
										$extractedRange = explode(',', $extractedRange);

										foreach ($extractedRange as $range) {
											$matches = array();

											if (preg_match('/^([A-Z]+)\\:([A-Z]+)$/', $range, $matches)) {
												$docSheet->getPageSetup()->setColumnsToRepeatAtLeft(array($matches[1], $matches[2]));
											}
											else if (preg_match('/^(\\d+)\\:(\\d+)$/', $range, $matches)) {
												$docSheet->getPageSetup()->setRowsToRepeatAtTop(array($matches[1], $matches[2]));
											}
										}

										break;

									case '_xlnm.Print_Area':
										$range = explode('!', $extractedRange);
										$extractedRange = (isset($range[1]) ? $range[1] : $range[0]);
										$docSheet->getPageSetup()->setPrintArea($extractedRange);
										break;

									default:
										break;
									}
								}
							}
						}

						++$sheetId;
					}

					if ($xmlWorkbook->definedNames) {
						foreach ($xmlWorkbook->definedNames->definedName as $definedName) {
							$extractedRange = (string) $definedName;
							$extractedRange = preg_replace('/\'(\\w+)\'\\!/', '', $extractedRange);
							$extractedRange = str_replace('$', '', $extractedRange);
							if ((stripos((string) $definedName, '#REF!') !== false) || ($extractedRange == '')) {
								continue;
							}

							if ((string) $definedName['localSheetId'] != '') {
								switch ((string) $definedName['name']) {
								case '_xlnm._FilterDatabase':
								case '_xlnm.Print_Titles':
								case '_xlnm.Print_Area':
									break;

								default:
									$range = explode('!', (string) $definedName);

									if (count($range) == 2) {
										$range[0] = str_replace('\'\'', '\'', $range[0]);
										$range[0] = str_replace('\'', '', $range[0]);

										if ($worksheet = $docSheet->getParent()->getSheetByName($range[0])) {
											$extractedRange = str_replace('$', '', $range[1]);
											$scope = $docSheet->getParent()->getSheet((string) $definedName['localSheetId']);
											$excel->addNamedRange(new PHPExcel_NamedRange((string) $definedName['name'], $worksheet, $extractedRange, true, $scope));
										}
									}

									break;
								}
							}
							else if (!isset($definedName['localSheetId'])) {
								$locatedSheet = NULL;
								$extractedSheetName = '';

								if (strpos((string) $definedName, '!') !== false) {
									$extractedSheetName = PHPExcel_Worksheet::extractSheetTitle((string) $definedName, true);
									$extractedSheetName = $extractedSheetName[0];
									$locatedSheet = $excel->getSheetByName($extractedSheetName);
									$range = explode('!', $extractedRange);
									$extractedRange = (isset($range[1]) ? $range[1] : $range[0]);
								}

								if (!is_null($locatedSheet)) {
									$excel->addNamedRange(new PHPExcel_NamedRange((string) $definedName['name'], $locatedSheet, $extractedRange, false));
								}
							}
						}
					}
				}

				if (!$this->_readDataOnly) {
					$activeTab = intval($xmlWorkbook->bookViews->workbookView['activeTab']);
					if (isset($mapSheetId[$activeTab]) && ($mapSheetId[$activeTab] !== NULL)) {
						$excel->setActiveSheetIndex($mapSheetId[$activeTab]);
					}
					else {
						if ($excel->getSheetCount() == 0) {
							$excel->createSheet();
						}

						$excel->setActiveSheetIndex(0);
					}
				}

				break;
			}
		}

		return $excel;
	}

	private function _readColor($color)
	{
		if (isset($color['rgb'])) {
			return (string) $color['rgb'];
		}
		else if (isset($color['indexed'])) {
			return PHPExcel_Style_Color::indexedColor($color['indexed'])->getARGB();
		}
	}

	private function _readStyle($docStyle, $style)
	{
		if (isset($style->numFmt)) {
			$docStyle->getNumberFormat()->setFormatCode($style->numFmt);
		}

		if (isset($style->font)) {
			$docStyle->getFont()->setName((string) $style->font->name['val']);
			$docStyle->getFont()->setSize((string) $style->font->sz['val']);

			if (isset($style->font->b)) {
				$docStyle->getFont()->setBold(!isset($style->font->b['val']) || ($style->font->b['val'] == 'true') || ($style->font->b['val'] == '1'));
			}

			if (isset($style->font->i)) {
				$docStyle->getFont()->setItalic(!isset($style->font->i['val']) || ($style->font->i['val'] == 'true') || ($style->font->i['val'] == '1'));
			}

			if (isset($style->font->strike)) {
				$docStyle->getFont()->setStrikethrough(!isset($style->font->strike['val']) || ($style->font->strike['val'] == 'true') || ($style->font->strike['val'] == '1'));
			}

			$docStyle->getFont()->getColor()->setARGB($this->_readColor($style->font->color));
			if (isset($style->font->u) && !isset($style->font->u['val'])) {
				$docStyle->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
			}
			else {
				if (isset($style->font->u) && isset($style->font->u['val'])) {
					$docStyle->getFont()->setUnderline((string) $style->font->u['val']);
				}
			}

			if (isset($style->font->vertAlign) && isset($style->font->vertAlign['val'])) {
				$vertAlign = strtolower((string) $style->font->vertAlign['val']);

				if ($vertAlign == 'superscript') {
					$docStyle->getFont()->setSuperScript(true);
				}

				if ($vertAlign == 'subscript') {
					$docStyle->getFont()->setSubScript(true);
				}
			}
		}

		if (isset($style->fill)) {
			if ($style->fill->gradientFill) {
/* [31m * TODO SEPARATE[0m */
/* [31m * TODO SEPARATE[0m */
				$gradientFill = $style->fill->gradientFill[0];
				$docStyle->getFill()->setFillType((string) $gradientFill['type']);
				$docStyle->getFill()->setRotation(floatval($gradientFill['degree']));
				$gradientFill->registerXPathNamespace('sml', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
				$docStyle->getFill()->getStartColor()->setARGB($this->_readColor(self::array_item($gradientFill->xpath('sml:stop[@position=0]'))->color));
				$docStyle->getFill()->getEndColor()->setARGB($this->_readColor(self::array_item($gradientFill->xpath('sml:stop[@position=1]'))->color));
			}
			else if ($style->fill->patternFill) {
				$patternType = ((string) $style->fill->patternFill['patternType'] != '' ? (string) $style->fill->patternFill['patternType'] : 'solid');
				$docStyle->getFill()->setFillType($patternType);

				if ($style->fill->patternFill->fgColor) {
					$docStyle->getFill()->getStartColor()->setARGB($this->_readColor($style->fill->patternFill->fgColor));
				}
				else {
					$docStyle->getFill()->getStartColor()->setARGB('FF000000');
				}

				if ($style->fill->patternFill->bgColor) {
					$docStyle->getFill()->getEndColor()->setARGB($this->_readColor($style->fill->patternFill->bgColor));
				}
			}
		}

		if (isset($style->border)) {
			$diagonalUp = false;
			$diagonalDown = false;
			if (($style->border['diagonalUp'] == 'true') || ($style->border['diagonalUp'] == 1)) {
				$diagonalUp = true;
			}

			if (($style->border['diagonalDown'] == 'true') || ($style->border['diagonalDown'] == 1)) {
				$diagonalDown = true;
			}

			if (($diagonalUp == false) && ($diagonalDown == false)) {
				$docStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_NONE);
			}
			else {
				if (($diagonalUp == true) && ($diagonalDown == false)) {
					$docStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_UP);
				}
				else {
					if (($diagonalUp == false) && ($diagonalDown == true)) {
						$docStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_DOWN);
					}
					else {
						if (($diagonalUp == true) && ($diagonalDown == true)) {
							$docStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_BOTH);
						}
					}
				}
			}

			$this->_readBorder($docStyle->getBorders()->getLeft(), $style->border->left);
			$this->_readBorder($docStyle->getBorders()->getRight(), $style->border->right);
			$this->_readBorder($docStyle->getBorders()->getTop(), $style->border->top);
			$this->_readBorder($docStyle->getBorders()->getBottom(), $style->border->bottom);
			$this->_readBorder($docStyle->getBorders()->getDiagonal(), $style->border->diagonal);
		}

		if (isset($style->alignment)) {
			$docStyle->getAlignment()->setHorizontal((string) $style->alignment['horizontal']);
			$docStyle->getAlignment()->setVertical((string) $style->alignment['vertical']);
			$textRotation = 0;

			if ((int) $style->alignment['textRotation'] <= 90) {
				$textRotation = (int) $style->alignment['textRotation'];
			}
			else if (90 < (int) $style->alignment['textRotation']) {
				$textRotation = 90 - (int) $style->alignment['textRotation'];
			}

			$docStyle->getAlignment()->setTextRotation(intval($textRotation));
			$docStyle->getAlignment()->setWrapText(((string) $style->alignment['wrapText'] == 'true') || ((string) $style->alignment['wrapText'] == '1'));
			$docStyle->getAlignment()->setShrinkToFit(((string) $style->alignment['shrinkToFit'] == 'true') || ((string) $style->alignment['shrinkToFit'] == '1'));
			$docStyle->getAlignment()->setIndent(0 < intval((string) $style->alignment['indent']) ? intval((string) $style->alignment['indent']) : 0);
		}

		if (isset($style->protection)) {
			if (isset($style->protection['locked'])) {
				if ((string) $style->protection['locked'] == 'true') {
					$docStyle->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
				}
				else {
					$docStyle->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
				}
			}

			if (isset($style->protection['hidden'])) {
				if ((string) $style->protection['hidden'] == 'true') {
					$docStyle->getProtection()->setHidden(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
				}
				else {
					$docStyle->getProtection()->setHidden(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
				}
			}
		}
	}

	private function _readBorder($docBorder, $eleBorder)
	{
		if (isset($eleBorder['style'])) {
			$docBorder->setBorderStyle((string) $eleBorder['style']);
		}

		if (isset($eleBorder->color)) {
			$docBorder->getColor()->setARGB($this->_readColor($eleBorder->color));
		}
	}

	private function _parseRichText($is = NULL)
	{
		$value = new PHPExcel_RichText();

		if (isset($is->t)) {
			$value->createText(PHPExcel_Shared_String::ControlCharacterOOXML2PHP((string) $is->t));
		}
		else {
			foreach ($is->r as $run) {
				if (!isset($run->rPr)) {
					$objText = $value->createText(PHPExcel_Shared_String::ControlCharacterOOXML2PHP((string) $run->t));
				}
				else {
					$objText = $value->createTextRun(PHPExcel_Shared_String::ControlCharacterOOXML2PHP((string) $run->t));

					if (isset($run->rPr->rFont['val'])) {
						$objText->getFont()->setName((string) $run->rPr->rFont['val']);
					}

					if (isset($run->rPr->sz['val'])) {
						$objText->getFont()->setSize((string) $run->rPr->sz['val']);
					}

					if (isset($run->rPr->color)) {
						$objText->getFont()->setColor(new PHPExcel_Style_Color($this->_readColor($run->rPr->color)));
					}

					if ((isset($run->rPr->b['val']) && (((string) $run->rPr->b['val'] == 'true') || ((string) $run->rPr->b['val'] == '1'))) || (isset($run->rPr->b) && !isset($run->rPr->b['val']))) {
						$objText->getFont()->setBold(true);
					}

					if ((isset($run->rPr->i['val']) && (((string) $run->rPr->i['val'] == 'true') || ((string) $run->rPr->i['val'] == '1'))) || (isset($run->rPr->i) && !isset($run->rPr->i['val']))) {
						$objText->getFont()->setItalic(true);
					}

					if (isset($run->rPr->vertAlign) && isset($run->rPr->vertAlign['val'])) {
						$vertAlign = strtolower((string) $run->rPr->vertAlign['val']);

						if ($vertAlign == 'superscript') {
							$objText->getFont()->setSuperScript(true);
						}

						if ($vertAlign == 'subscript') {
							$objText->getFont()->setSubScript(true);
						}
					}

					if (isset($run->rPr->u) && !isset($run->rPr->u['val'])) {
						$objText->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
					}
					else {
						if (isset($run->rPr->u) && isset($run->rPr->u['val'])) {
							$objText->getFont()->setUnderline((string) $run->rPr->u['val']);
						}
					}

					if ((isset($run->rPr->strike['val']) && (((string) $run->rPr->strike['val'] == 'true') || ((string) $run->rPr->strike['val'] == '1'))) || (isset($run->rPr->strike) && !isset($run->rPr->strike['val']))) {
						$objText->getFont()->setStrikethrough(true);
					}
				}
			}
		}

		return $value;
	}

	static private function array_item($array, $key = 0)
	{
		return isset($array[$key]) ? $array[$key] : NULL;
	}

	static private function dir_add($base, $add)
	{
		return preg_replace('~[^/]+/\\.\\./~', '', dirname($base) . '/' . $add);
	}

	static private function toCSSArray($style)
	{
		$style = str_replace("\r", '', $style);
		$style = str_replace("\n", '', $style);
		$temp = explode(';', $style);
		$style = array();

		foreach ($temp as $item) {
			$item = explode(':', $item);

			if (strpos($item[1], 'px') !== false) {
				$item[1] = str_replace('px', '', $item[1]);
			}

			if (strpos($item[1], 'pt') !== false) {
				$item[1] = str_replace('pt', '', $item[1]);
				$item[1] = PHPExcel_Shared_Font::fontSizeToPixels($item[1]);
			}

			if (strpos($item[1], 'in') !== false) {
				$item[1] = str_replace('in', '', $item[1]);
				$item[1] = PHPExcel_Shared_Font::inchSizeToPixels($item[1]);
			}

			if (strpos($item[1], 'cm') !== false) {
				$item[1] = str_replace('cm', '', $item[1]);
				$item[1] = PHPExcel_Shared_Font::centimeterSizeToPixels($item[1]);
			}

			$style[$item[0]] = $item[1];
		}

		return $style;
	}
}

?>

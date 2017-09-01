<?php

class PHPExcel_Writer_Excel2007_Worksheet extends PHPExcel_Writer_Excel2007_WriterPart
{
	public function writeWorksheet($pSheet = NULL, $pStringTable = NULL)
	{
		if (!is_null($pSheet)) {
			$objWriter = NULL;

			if ($this->getParentWriter()->getUseDiskCaching()) {
				$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
			}
			else {
				$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
			}

			$objWriter->startDocument('1.0', 'UTF-8', 'yes');
			$objWriter->startElement('worksheet');
			$objWriter->writeAttribute('xml:space', 'preserve');
			$objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
			$objWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
			$this->_writeSheetPr($objWriter, $pSheet);
			$this->_writeDimension($objWriter, $pSheet);
			$this->_writeSheetViews($objWriter, $pSheet);
			$this->_writeSheetFormatPr($objWriter, $pSheet);
			$this->_writeCols($objWriter, $pSheet);
			$this->_writeSheetData($objWriter, $pSheet, $pStringTable);
			$this->_writeSheetProtection($objWriter, $pSheet);
			$this->_writeProtectedRanges($objWriter, $pSheet);
			$this->_writeAutoFilter($objWriter, $pSheet);
			$this->_writeMergeCells($objWriter, $pSheet);
			$this->_writeConditionalFormatting($objWriter, $pSheet);
			$this->_writeDataValidations($objWriter, $pSheet);
			$this->_writeHyperlinks($objWriter, $pSheet);
			$this->_writePrintOptions($objWriter, $pSheet);
			$this->_writePageMargins($objWriter, $pSheet);
			$this->_writePageSetup($objWriter, $pSheet);
			$this->_writeHeaderFooter($objWriter, $pSheet);
			$this->_writeBreaks($objWriter, $pSheet);
			$this->_writeDrawings($objWriter, $pSheet);
			$this->_writeLegacyDrawing($objWriter, $pSheet);
			$this->_writeLegacyDrawingHF($objWriter, $pSheet);
			$objWriter->endElement();
			return $objWriter->getData();
		}
		else {
			throw new Exception('Invalid PHPExcel_Worksheet object passed.');
		}
	}

	private function _writeSheetPr(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('sheetPr');

		if ($pSheet->isTabColorSet()) {
			$objWriter->startElement('tabColor');
			$objWriter->writeAttribute('rgb', $pSheet->getTabColor()->getARGB());
			$objWriter->endElement();
		}

		$objWriter->startElement('outlinePr');
		$objWriter->writeAttribute('summaryBelow', $pSheet->getShowSummaryBelow() ? '1' : '0');
		$objWriter->writeAttribute('summaryRight', $pSheet->getShowSummaryRight() ? '1' : '0');
		$objWriter->endElement();

		if ($pSheet->getPageSetup()->getFitToPage()) {
			$objWriter->startElement('pageSetUpPr');
			$objWriter->writeAttribute('fitToPage', '1');
			$objWriter->endElement();
		}

		$objWriter->endElement();
	}

	private function _writeDimension(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('dimension');
		$objWriter->writeAttribute('ref', $pSheet->calculateWorksheetDimension());
		$objWriter->endElement();
	}

	private function _writeSheetViews(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('sheetViews');
		$sheetSelected = false;

		if ($this->getParentWriter()->getPHPExcel()->getIndex($pSheet) == $this->getParentWriter()->getPHPExcel()->getActiveSheetIndex()) {
			$sheetSelected = true;
		}

		$objWriter->startElement('sheetView');
		$objWriter->writeAttribute('tabSelected', $sheetSelected ? '1' : '0');
		$objWriter->writeAttribute('workbookViewId', '0');

		if ($pSheet->getSheetView()->getZoomScale() != 100) {
			$objWriter->writeAttribute('zoomScale', $pSheet->getSheetView()->getZoomScale());
		}

		if ($pSheet->getSheetView()->getZoomScaleNormal() != 100) {
			$objWriter->writeAttribute('zoomScaleNormal', $pSheet->getSheetView()->getZoomScaleNormal());
		}

		if ($pSheet->getShowGridlines()) {
			$objWriter->writeAttribute('showGridLines', 'true');
		}
		else {
			$objWriter->writeAttribute('showGridLines', 'false');
		}

		if ($pSheet->getShowRowColHeaders()) {
			$objWriter->writeAttribute('showRowColHeaders', '1');
		}
		else {
			$objWriter->writeAttribute('showRowColHeaders', '0');
		}

		if ($pSheet->getRightToLeft()) {
			$objWriter->writeAttribute('rightToLeft', 'true');
		}

		if ($pSheet->getFreezePane() != '') {
			$xSplit = 0;
			$ySplit = 0;
			$topLeftCell = $pSheet->getFreezePane();
			list($xSplit, $ySplit) = PHPExcel_Cell::coordinateFromString($pSheet->getFreezePane());
			$xSplit = PHPExcel_Cell::columnIndexFromString($xSplit);
			$objWriter->startElement('pane');
			$objWriter->writeAttribute('xSplit', $xSplit - 1);
			$objWriter->writeAttribute('ySplit', $ySplit - 1);
			$objWriter->writeAttribute('topLeftCell', $topLeftCell);
			$objWriter->writeAttribute('activePane', 'bottomRight');
			$objWriter->writeAttribute('state', 'frozen');
			$objWriter->endElement();
		}

		$objWriter->startElement('selection');
		$objWriter->writeAttribute('activeCell', $pSheet->getActiveCell());
		$objWriter->writeAttribute('sqref', $pSheet->getSelectedCells());
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->endElement();
	}

	private function _writeSheetFormatPr(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('sheetFormatPr');

		if (0 <= $pSheet->getDefaultRowDimension()->getRowHeight()) {
			$objWriter->writeAttribute('customHeight', 'true');
			$objWriter->writeAttribute('defaultRowHeight', PHPExcel_Shared_String::FormatNumber($pSheet->getDefaultRowDimension()->getRowHeight()));
		}
		else {
			$objWriter->writeAttribute('defaultRowHeight', '12.75');
		}

		if (0 <= $pSheet->getDefaultColumnDimension()->getWidth()) {
			$objWriter->writeAttribute('defaultColWidth', PHPExcel_Shared_String::FormatNumber($pSheet->getDefaultColumnDimension()->getWidth()));
		}

		$outlineLevelRow = 0;

		foreach ($pSheet->getRowDimensions() as $dimension) {
			if ($outlineLevelRow < $dimension->getOutlineLevel()) {
				$outlineLevelRow = $dimension->getOutlineLevel();
			}
		}

		$objWriter->writeAttribute('outlineLevelRow', (int) $outlineLevelRow);
		$outlineLevelCol = 0;

		foreach ($pSheet->getColumnDimensions() as $dimension) {
			if ($outlineLevelCol < $dimension->getOutlineLevel()) {
				$outlineLevelCol = $dimension->getOutlineLevel();
			}
		}

		$objWriter->writeAttribute('outlineLevelCol', (int) $outlineLevelCol);
		$objWriter->endElement();
	}

	private function _writeCols(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		if (0 < count($pSheet->getColumnDimensions())) {
			$objWriter->startElement('cols');
			$pSheet->calculateColumnWidths();

			foreach ($pSheet->getColumnDimensions() as $colDimension) {
				$objWriter->startElement('col');
				$objWriter->writeAttribute('min', PHPExcel_Cell::columnIndexFromString($colDimension->getColumnIndex()));
				$objWriter->writeAttribute('max', PHPExcel_Cell::columnIndexFromString($colDimension->getColumnIndex()));

				if ($colDimension->getWidth() < 0) {
					$objWriter->writeAttribute('width', '9.10');
				}
				else {
					$objWriter->writeAttribute('width', PHPExcel_Shared_String::FormatNumber($colDimension->getWidth()));
				}

				if ($colDimension->getVisible() == false) {
					$objWriter->writeAttribute('hidden', 'true');
				}

				if ($colDimension->getAutoSize()) {
					$objWriter->writeAttribute('bestFit', 'true');
				}

				if ($colDimension->getWidth() != $pSheet->getDefaultColumnDimension()->getWidth()) {
					$objWriter->writeAttribute('customWidth', 'true');
				}

				if ($colDimension->getCollapsed() == true) {
					$objWriter->writeAttribute('collapsed', 'true');
				}

				if (0 < $colDimension->getOutlineLevel()) {
					$objWriter->writeAttribute('outlineLevel', $colDimension->getOutlineLevel());
				}

				$objWriter->writeAttribute('style', $colDimension->getXfIndex());
				$objWriter->endElement();
			}

			$objWriter->endElement();
		}
	}

	private function _writeSheetProtection(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('sheetProtection');

		if ($pSheet->getProtection()->getPassword() != '') {
			$objWriter->writeAttribute('password', $pSheet->getProtection()->getPassword());
		}

		$objWriter->writeAttribute('sheet', $pSheet->getProtection()->getSheet() ? 'true' : 'false');
		$objWriter->writeAttribute('objects', $pSheet->getProtection()->getObjects() ? 'true' : 'false');
		$objWriter->writeAttribute('scenarios', $pSheet->getProtection()->getScenarios() ? 'true' : 'false');
		$objWriter->writeAttribute('formatCells', $pSheet->getProtection()->getFormatCells() ? 'true' : 'false');
		$objWriter->writeAttribute('formatColumns', $pSheet->getProtection()->getFormatColumns() ? 'true' : 'false');
		$objWriter->writeAttribute('formatRows', $pSheet->getProtection()->getFormatRows() ? 'true' : 'false');
		$objWriter->writeAttribute('insertColumns', $pSheet->getProtection()->getInsertColumns() ? 'true' : 'false');
		$objWriter->writeAttribute('insertRows', $pSheet->getProtection()->getInsertRows() ? 'true' : 'false');
		$objWriter->writeAttribute('insertHyperlinks', $pSheet->getProtection()->getInsertHyperlinks() ? 'true' : 'false');
		$objWriter->writeAttribute('deleteColumns', $pSheet->getProtection()->getDeleteColumns() ? 'true' : 'false');
		$objWriter->writeAttribute('deleteRows', $pSheet->getProtection()->getDeleteRows() ? 'true' : 'false');
		$objWriter->writeAttribute('selectLockedCells', $pSheet->getProtection()->getSelectLockedCells() ? 'true' : 'false');
		$objWriter->writeAttribute('sort', $pSheet->getProtection()->getSort() ? 'true' : 'false');
		$objWriter->writeAttribute('autoFilter', $pSheet->getProtection()->getAutoFilter() ? 'true' : 'false');
		$objWriter->writeAttribute('pivotTables', $pSheet->getProtection()->getPivotTables() ? 'true' : 'false');
		$objWriter->writeAttribute('selectUnlockedCells', $pSheet->getProtection()->getSelectUnlockedCells() ? 'true' : 'false');
		$objWriter->endElement();
	}

	private function _writeConditionalFormatting(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$id = 1;

		foreach ($pSheet->getConditionalStylesCollection() as $cellCoordinate => $conditionalStyles) {
			foreach ($conditionalStyles as $conditional) {
				if ($conditional->getConditionType() != PHPExcel_Style_Conditional::CONDITION_NONE) {
					$objWriter->startElement('conditionalFormatting');
					$objWriter->writeAttribute('sqref', $cellCoordinate);
					$objWriter->startElement('cfRule');
					$objWriter->writeAttribute('type', $conditional->getConditionType());
					$objWriter->writeAttribute('dxfId', $this->getParentWriter()->getStylesConditionalHashTable()->getIndexForHashCode($conditional->getHashCode()));
					$objWriter->writeAttribute('priority', $id++);
					if ((($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CELLIS) || ($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT)) && ($conditional->getOperatorType() != PHPExcel_Style_Conditional::OPERATOR_NONE)) {
						$objWriter->writeAttribute('operator', $conditional->getOperatorType());
					}

					if (($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT) && !is_null($conditional->getText())) {
						$objWriter->writeAttribute('text', $conditional->getText());
					}

					if (($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT) && ($conditional->getOperatorType() == PHPExcel_Style_Conditional::OPERATOR_CONTAINSTEXT) && !is_null($conditional->getText())) {
						$objWriter->writeElement('formula', 'NOT(ISERROR(SEARCH("' . $conditional->getText() . '",' . $cellCoordinate . ')))');
					}
					else {
						if (($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT) && ($conditional->getOperatorType() == PHPExcel_Style_Conditional::OPERATOR_BEGINSWITH) && !is_null($conditional->getText())) {
							$objWriter->writeElement('formula', 'LEFT(' . $cellCoordinate . ',' . strlen($conditional->getText()) . ')="' . $conditional->getText() . '"');
						}
						else {
							if (($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT) && ($conditional->getOperatorType() == PHPExcel_Style_Conditional::OPERATOR_ENDSWITH) && !is_null($conditional->getText())) {
								$objWriter->writeElement('formula', 'RIGHT(' . $cellCoordinate . ',' . strlen($conditional->getText()) . ')="' . $conditional->getText() . '"');
							}
							else {
								if (($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT) && ($conditional->getOperatorType() == PHPExcel_Style_Conditional::OPERATOR_NOTCONTAINS) && !is_null($conditional->getText())) {
									$objWriter->writeElement('formula', 'ISERROR(SEARCH("' . $conditional->getText() . '",' . $cellCoordinate . '))');
								}
								else {
									if (($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CELLIS) || ($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT) || ($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_EXPRESSION)) {
										foreach ($conditional->getConditions() as $formula) {
											$objWriter->writeElement('formula', $formula);
										}
									}
								}
							}
						}
					}

					$objWriter->endElement();
					$objWriter->endElement();
				}
			}
		}
	}

	private function _writeDataValidations(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$dataValidationCollection = $pSheet->getDataValidationCollection();

		if (0 < count($dataValidationCollection)) {
			$objWriter->startElement('dataValidations');
			$objWriter->writeAttribute('count', count($dataValidationCollection));

			foreach ($dataValidationCollection as $coordinate => $dv) {
				$objWriter->startElement('dataValidation');

				if ($dv->getType() != '') {
					$objWriter->writeAttribute('type', $dv->getType());
				}

				if ($dv->getErrorStyle() != '') {
					$objWriter->writeAttribute('errorStyle', $dv->getErrorStyle());
				}

				if ($dv->getOperator() != '') {
					$objWriter->writeAttribute('operator', $dv->getOperator());
				}

				$objWriter->writeAttribute('allowBlank', $dv->getAllowBlank() ? '1' : '0');
				$objWriter->writeAttribute('showDropDown', !$dv->getShowDropDown() ? '1' : '0');
				$objWriter->writeAttribute('showInputMessage', $dv->getShowInputMessage() ? '1' : '0');
				$objWriter->writeAttribute('showErrorMessage', $dv->getShowErrorMessage() ? '1' : '0');

				if ($dv->getErrorTitle() !== '') {
					$objWriter->writeAttribute('errorTitle', $dv->getErrorTitle());
				}

				if ($dv->getError() !== '') {
					$objWriter->writeAttribute('error', $dv->getError());
				}

				if ($dv->getPromptTitle() !== '') {
					$objWriter->writeAttribute('promptTitle', $dv->getPromptTitle());
				}

				if ($dv->getPrompt() !== '') {
					$objWriter->writeAttribute('prompt', $dv->getPrompt());
				}

				$objWriter->writeAttribute('sqref', $coordinate);

				if ($dv->getFormula1() !== '') {
					$objWriter->writeElement('formula1', $dv->getFormula1());
				}

				if ($dv->getFormula2() !== '') {
					$objWriter->writeElement('formula2', $dv->getFormula2());
				}

				$objWriter->endElement();
			}

			$objWriter->endElement();
		}
	}

	private function _writeHyperlinks(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$hyperlinkCollection = $pSheet->getHyperlinkCollection();
		$relationId = 1;

		if (0 < count($hyperlinkCollection)) {
			$objWriter->startElement('hyperlinks');

			foreach ($hyperlinkCollection as $coordinate => $hyperlink) {
				$objWriter->startElement('hyperlink');
				$objWriter->writeAttribute('ref', $coordinate);

				if (!$hyperlink->isInternal()) {
					$objWriter->writeAttribute('r:id', 'rId_hyperlink_' . $relationId);
					++$relationId;
				}
				else {
					$objWriter->writeAttribute('location', str_replace('sheet://', '', $hyperlink->getUrl()));
				}

				if ($hyperlink->getTooltip() != '') {
					$objWriter->writeAttribute('tooltip', $hyperlink->getTooltip());
				}

				$objWriter->endElement();
			}

			$objWriter->endElement();
		}
	}

	private function _writeProtectedRanges(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		if (0 < count($pSheet->getProtectedCells())) {
			$objWriter->startElement('protectedRanges');

			foreach ($pSheet->getProtectedCells() as $protectedCell => $passwordHash) {
				$objWriter->startElement('protectedRange');
				$objWriter->writeAttribute('name', 'p' . md5($protectedCell));
				$objWriter->writeAttribute('sqref', $protectedCell);
				$objWriter->writeAttribute('password', $passwordHash);
				$objWriter->endElement();
			}

			$objWriter->endElement();
		}
	}

	private function _writeMergeCells(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		if (0 < count($pSheet->getMergeCells())) {
			$objWriter->startElement('mergeCells');

			foreach ($pSheet->getMergeCells() as $mergeCell) {
				$objWriter->startElement('mergeCell');
				$objWriter->writeAttribute('ref', $mergeCell);
				$objWriter->endElement();
			}

			$objWriter->endElement();
		}
	}

	private function _writePrintOptions(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('printOptions');
		$objWriter->writeAttribute('gridLines', $pSheet->getPrintGridlines() ? 'true' : 'false');
		$objWriter->writeAttribute('gridLinesSet', 'true');

		if ($pSheet->getPageSetup()->getHorizontalCentered()) {
			$objWriter->writeAttribute('horizontalCentered', 'true');
		}

		if ($pSheet->getPageSetup()->getVerticalCentered()) {
			$objWriter->writeAttribute('verticalCentered', 'true');
		}

		$objWriter->endElement();
	}

	private function _writePageMargins(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('pageMargins');
		$objWriter->writeAttribute('left', PHPExcel_Shared_String::FormatNumber($pSheet->getPageMargins()->getLeft()));
		$objWriter->writeAttribute('right', PHPExcel_Shared_String::FormatNumber($pSheet->getPageMargins()->getRight()));
		$objWriter->writeAttribute('top', PHPExcel_Shared_String::FormatNumber($pSheet->getPageMargins()->getTop()));
		$objWriter->writeAttribute('bottom', PHPExcel_Shared_String::FormatNumber($pSheet->getPageMargins()->getBottom()));
		$objWriter->writeAttribute('header', PHPExcel_Shared_String::FormatNumber($pSheet->getPageMargins()->getHeader()));
		$objWriter->writeAttribute('footer', PHPExcel_Shared_String::FormatNumber($pSheet->getPageMargins()->getFooter()));
		$objWriter->endElement();
	}

	private function _writeAutoFilter(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		if ($pSheet->getAutoFilter() != '') {
			$objWriter->startElement('autoFilter');
			$objWriter->writeAttribute('ref', $pSheet->getAutoFilter());
			$objWriter->endElement();
		}
	}

	private function _writePageSetup(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('pageSetup');
		$objWriter->writeAttribute('paperSize', $pSheet->getPageSetup()->getPaperSize());
		$objWriter->writeAttribute('orientation', $pSheet->getPageSetup()->getOrientation());

		if (!is_null($pSheet->getPageSetup()->getScale())) {
			$objWriter->writeAttribute('scale', $pSheet->getPageSetup()->getScale());
		}

		if (!is_null($pSheet->getPageSetup()->getFitToHeight())) {
			$objWriter->writeAttribute('fitToHeight', $pSheet->getPageSetup()->getFitToHeight());
		}
		else {
			$objWriter->writeAttribute('fitToHeight', '0');
		}

		if (!is_null($pSheet->getPageSetup()->getFitToWidth())) {
			$objWriter->writeAttribute('fitToWidth', $pSheet->getPageSetup()->getFitToWidth());
		}
		else {
			$objWriter->writeAttribute('fitToWidth', '0');
		}

		if (!is_null($pSheet->getPageSetup()->getFirstPageNumber())) {
			$objWriter->writeAttribute('firstPageNumber', $pSheet->getPageSetup()->getFirstPageNumber());
			$objWriter->writeAttribute('useFirstPageNumber', '1');
		}

		$objWriter->endElement();
	}

	private function _writeHeaderFooter(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$objWriter->startElement('headerFooter');
		$objWriter->writeAttribute('differentOddEven', $pSheet->getHeaderFooter()->getDifferentOddEven() ? 'true' : 'false');
		$objWriter->writeAttribute('differentFirst', $pSheet->getHeaderFooter()->getDifferentFirst() ? 'true' : 'false');
		$objWriter->writeAttribute('scaleWithDoc', $pSheet->getHeaderFooter()->getScaleWithDocument() ? 'true' : 'false');
		$objWriter->writeAttribute('alignWithMargins', $pSheet->getHeaderFooter()->getAlignWithMargins() ? 'true' : 'false');
		$objWriter->writeElement('oddHeader', $pSheet->getHeaderFooter()->getOddHeader());
		$objWriter->writeElement('oddFooter', $pSheet->getHeaderFooter()->getOddFooter());
		$objWriter->writeElement('evenHeader', $pSheet->getHeaderFooter()->getEvenHeader());
		$objWriter->writeElement('evenFooter', $pSheet->getHeaderFooter()->getEvenFooter());
		$objWriter->writeElement('firstHeader', $pSheet->getHeaderFooter()->getFirstHeader());
		$objWriter->writeElement('firstFooter', $pSheet->getHeaderFooter()->getFirstFooter());
		$objWriter->endElement();
	}

	private function _writeBreaks(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$aRowBreaks = array();
		$aColumnBreaks = array();

		foreach ($pSheet->getBreaks() as $cell => $breakType) {
			if ($breakType == PHPExcel_Worksheet::BREAK_ROW) {
				$aRowBreaks[] = $cell;
			}
			else if ($breakType == PHPExcel_Worksheet::BREAK_COLUMN) {
				$aColumnBreaks[] = $cell;
			}
		}

		if (0 < count($aRowBreaks)) {
			$objWriter->startElement('rowBreaks');
			$objWriter->writeAttribute('count', count($aRowBreaks));
			$objWriter->writeAttribute('manualBreakCount', count($aRowBreaks));

			foreach ($aRowBreaks as $cell) {
				$coords = PHPExcel_Cell::coordinateFromString($cell);
				$objWriter->startElement('brk');
				$objWriter->writeAttribute('id', $coords[1]);
				$objWriter->writeAttribute('man', '1');
				$objWriter->endElement();
			}

			$objWriter->endElement();
		}

		if (0 < count($aColumnBreaks)) {
			$objWriter->startElement('colBreaks');
			$objWriter->writeAttribute('count', count($aColumnBreaks));
			$objWriter->writeAttribute('manualBreakCount', count($aColumnBreaks));

			foreach ($aColumnBreaks as $cell) {
				$coords = PHPExcel_Cell::coordinateFromString($cell);
				$objWriter->startElement('brk');
				$objWriter->writeAttribute('id', PHPExcel_Cell::columnIndexFromString($coords[0]) - 1);
				$objWriter->writeAttribute('man', '1');
				$objWriter->endElement();
			}

			$objWriter->endElement();
		}
	}

	private function _writeSheetData(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL, $pStringTable = NULL)
	{
		if (is_array($pStringTable)) {
			$aFlippedStringTable = $this->getParentWriter()->getWriterPart('stringtable')->flipStringTable($pStringTable);
			$objWriter->startElement('sheetData');
			$colCount = PHPExcel_Cell::columnIndexFromString($pSheet->getHighestColumn());
			$highestRow = $pSheet->getHighestRow();
			$cellsByRow = array();

			foreach ($pSheet->getCellCollection() as $cellID) {
				$cellAddress = PHPExcel_Cell::coordinateFromString($cellID);
				$cellsByRow[$cellAddress[1]][] = $cellID;
			}

			for ($currentRow = 1; $currentRow <= $highestRow; ++$currentRow) {
				$rowDimension = $pSheet->getRowDimension($currentRow);
				$writeCurrentRow = isset($cellsByRow[$currentRow]) || (0 <= $rowDimension->getRowHeight()) || ($rowDimension->getVisible() == false) || ($rowDimension->getCollapsed() == true) || (0 < $rowDimension->getOutlineLevel()) || ($rowDimension->getXfIndex() !== NULL);

				if ($writeCurrentRow) {
					$objWriter->startElement('row');
					$objWriter->writeAttribute('r', $currentRow);
					$objWriter->writeAttribute('spans', '1:' . $colCount);

					if (0 <= $rowDimension->getRowHeight()) {
						$objWriter->writeAttribute('customHeight', '1');
						$objWriter->writeAttribute('ht', PHPExcel_Shared_String::FormatNumber($rowDimension->getRowHeight()));
					}

					if ($rowDimension->getVisible() == false) {
						$objWriter->writeAttribute('hidden', 'true');
					}

					if ($rowDimension->getCollapsed() == true) {
						$objWriter->writeAttribute('collapsed', 'true');
					}

					if (0 < $rowDimension->getOutlineLevel()) {
						$objWriter->writeAttribute('outlineLevel', $rowDimension->getOutlineLevel());
					}

					if ($rowDimension->getXfIndex() !== NULL) {
						$objWriter->writeAttribute('s', $rowDimension->getXfIndex());
						$objWriter->writeAttribute('customFormat', '1');
					}

					if (isset($cellsByRow[$currentRow])) {
						foreach ($cellsByRow[$currentRow] as $cellAddress) {
							$this->_writeCell($objWriter, $pSheet, $cellAddress, $pStringTable, $aFlippedStringTable);
						}
					}

					$objWriter->endElement();
				}
			}

			$objWriter->endElement();
		}
		else {
			throw new Exception('Invalid parameters passed.');
		}
	}

	private function _writeCell(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL, $pCellAddress = NULL, $pStringTable = NULL, $pFlippedStringTable = NULL)
	{
		$pCell = $pSheet->getCell($pCellAddress);
		if (is_array($pStringTable) && is_array($pFlippedStringTable)) {
			$objWriter->startElement('c');
			$objWriter->writeAttribute('r', $pCell->getCoordinate());

			if ($pCell->getXfIndex() != '') {
				$objWriter->writeAttribute('s', $pCell->getXfIndex());
			}

			if (is_object($pCell->getValue()) || ($pCell->getValue() !== '')) {
				$mappedType = $pCell->getDataType();

				switch (strtolower($mappedType)) {
				case 'inlinestr':
					$objWriter->writeAttribute('t', $mappedType);
					break;

				case 's':
					$objWriter->writeAttribute('t', $mappedType);
					break;

				case 'b':
					$objWriter->writeAttribute('t', $mappedType);
					break;

				case 'f':
					$calculatedValue = NULL;

					if ($this->getParentWriter()->getPreCalculateFormulas()) {
						$pCell->attach($pSheet);
						$calculatedValue = $pCell->getCalculatedValue();
					}
					else {
						$pCell->attach($pSheet);
						$calculatedValue = $pCell->getValue();
					}

					if (is_string($calculatedValue)) {
						$objWriter->writeAttribute('t', 'str');
					}

					break;

				case 'e':
					$objWriter->writeAttribute('t', $mappedType);
				}

				switch (strtolower($mappedType)) {
				case 'inlinestr':
					if (!$pCell->getValue() instanceof PHPExcel_RichText) {
						$objWriter->writeElement('t', PHPExcel_Shared_String::ControlCharacterPHP2OOXML(htmlspecialchars($pCell->getValue())));
					}
					else if ($pCell->getValue() instanceof PHPExcel_RichText) {
						$objWriter->startElement('is');
						$this->getParentWriter()->getWriterPart('stringtable')->writeRichText($objWriter, $pCell->getValue());
						$objWriter->endElement();
					}

					break;

				case 's':
					if (!$pCell->getValue() instanceof PHPExcel_RichText) {
						if (isset($pFlippedStringTable[$pCell->getValue()])) {
							$objWriter->writeElement('v', $pFlippedStringTable[$pCell->getValue()]);
						}
					}
					else if ($pCell->getValue() instanceof PHPExcel_RichText) {
						$objWriter->writeElement('v', $pFlippedStringTable[$pCell->getValue()->getHashCode()]);
					}

					break;

				case 'f':
					$objWriter->writeElement('f', substr($pCell->getValue(), 1));

					if ($this->getParentWriter()->getOffice2003Compatibility() === false) {
						if ($this->getParentWriter()->getPreCalculateFormulas()) {
							$calculatedValue = $pCell->getCalculatedValue();
							if (!is_array($calculatedValue) && (substr($calculatedValue, 0, 1) != '#')) {
								$v = PHPExcel_Shared_String::FormatNumber($calculatedValue);
								$objWriter->writeElement('v', $v);
							}
							else {
								$objWriter->writeElement('v', '0');
							}
						}
						else {
							$objWriter->writeElement('v', '0');
						}
					}

					break;

				case 'n':
					$v = str_replace(',', '.', $pCell->getValue());
					$objWriter->writeElement('v', $v);
					break;

				case 'b':
					$objWriter->writeElement('v', $pCell->getValue() ? '1' : '0');
					break;

				case 'e':
					if (substr($pCell->getValue(), 0, 1) == '=') {
						$objWriter->writeElement('f', substr($pCell->getValue(), 1));
						$objWriter->writeElement('v', substr($pCell->getValue(), 1));
					}
					else {
						$objWriter->writeElement('v', $pCell->getValue());
					}

					break;
				}
			}

			$objWriter->endElement();
		}
		else {
			throw new Exception('Invalid parameters passed.');
		}
	}

	private function _writeDrawings(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		if (0 < $pSheet->getDrawingCollection()->count()) {
			$objWriter->startElement('drawing');
			$objWriter->writeAttribute('r:id', 'rId1');
			$objWriter->endElement();
		}
	}

	private function _writeLegacyDrawing(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		if (0 < count($pSheet->getComments())) {
			$objWriter->startElement('legacyDrawing');
			$objWriter->writeAttribute('r:id', 'rId_comments_vml1');
			$objWriter->endElement();
		}
	}

	private function _writeLegacyDrawingHF(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		if (0 < count($pSheet->getHeaderFooter()->getImages())) {
			$objWriter->startElement('legacyDrawingHF');
			$objWriter->writeAttribute('r:id', 'rId_headerfooter_vml1');
			$objWriter->endElement();
		}
	}
}

?>

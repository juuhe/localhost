<?php

class PHPExcel_Writer_HTML implements PHPExcel_Writer_IWriter
{
	/**
	 * PHPExcel object
	 *
	 * @var PHPExcel
	 */
	protected $_phpExcel;
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
	 * Images root
	 *
	 * @var string
	 */
	private $_imagesRoot = '.';
	/**
	 * Use inline CSS?
	 *
	 * @var boolean
	 */
	private $_useInlineCss = false;
	/**
	 * Array of CSS styles
	 *
	 * @var array
	 */
	private $_cssStyles;
	/**
	 * Array of column widths in points
	 *
	 * @var array
	 */
	private $_columnWidths;
	/**
	 * Default font
	 *
	 * @var PHPExcel_Style_Font
	 */
	private $_defaultFont;
	/**
	 * Flag whether spans have been calculated
	 *
	 * @var boolean
	 */
	private $_spansAreCalculated;
	/**
	 * Excel cells that should not be written as HTML cells
	 *
	 * @var array
	 */
	private $_isSpannedCell;
	/**
	 * Excel cells that are upper-left corner in a cell merge
	 *
	 * @var array
	 */
	private $_isBaseCell;
	/**
	 * Excel rows that should not be written as HTML rows
	 *
	 * @var array
	 */
	private $_isSpannedRow;
	/**
	 * Is the current writer creating PDF?
	 *
	 * @var boolean
	 */
	protected $_isPdf = false;

	public function __construct(PHPExcel $phpExcel)
	{
		$this->_phpExcel = $phpExcel;
		$this->_defaultFont = $this->_phpExcel->getDefaultStyle()->getFont();
		$this->_sheetIndex = 0;
		$this->_imagesRoot = '.';
		$this->_spansAreCalculated = false;
		$this->_isSpannedCell = array();
		$this->_isBaseCell = array();
		$this->_isSpannedRow = array();
	}

	public function save($pFilename = NULL)
	{
		$this->_phpExcel->garbageCollect();
		$saveArrayReturnType = PHPExcel_Calculation::getArrayReturnType();
		PHPExcel_Calculation::setArrayReturnType(PHPExcel_Calculation::RETURN_ARRAY_AS_VALUE);
		$this->buildCSS(!$this->_useInlineCss);
		$fileHandle = fopen($pFilename, 'w');

		if ($fileHandle === false) {
			throw new Exception('Could not open file ' . $pFilename . ' for writing.');
		}

		fwrite($fileHandle, $this->generateHTMLHeader(!$this->_useInlineCss));

		if (!$this->_isPdf) {
			fwrite($fileHandle, $this->generateNavigation());
		}

		fwrite($fileHandle, $this->generateSheetData());
		fwrite($fileHandle, $this->generateHTMLFooter());
		fclose($fileHandle);
		PHPExcel_Calculation::setArrayReturnType($saveArrayReturnType);
	}

	private function _mapVAlign($vAlign)
	{
		switch ($vAlign) {
		case PHPExcel_Style_Alignment::VERTICAL_BOTTOM:
			return 'bottom';
		case PHPExcel_Style_Alignment::VERTICAL_TOP:
			return 'top';
		case PHPExcel_Style_Alignment::VERTICAL_CENTER:
		case PHPExcel_Style_Alignment::VERTICAL_JUSTIFY:
			return 'middle';
		default:
			return 'baseline';
		}
	}

	private function _mapHAlign($hAlign)
	{
		switch ($hAlign) {
		case PHPExcel_Style_Alignment::HORIZONTAL_GENERAL:
			return false;
		case PHPExcel_Style_Alignment::HORIZONTAL_LEFT:
			return 'left';
		case PHPExcel_Style_Alignment::HORIZONTAL_RIGHT:
			return 'right';
		case PHPExcel_Style_Alignment::HORIZONTAL_CENTER:
			return 'center';
		case PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY:
			return 'justify';
		default:
			return false;
		}
	}

	private function _mapBorderStyle($borderStyle)
	{
		switch ($borderStyle) {
		case PHPExcel_Style_Border::BORDER_NONE:
			return '0px';
		case PHPExcel_Style_Border::BORDER_DASHED:
			return '1px dashed';
		case PHPExcel_Style_Border::BORDER_DOTTED:
			return '1px dotted';
		case PHPExcel_Style_Border::BORDER_DOUBLE:
			return '3px double';
		case PHPExcel_Style_Border::BORDER_THICK:
			return '2px solid';
		default:
			return '1px solid';
		}
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

	public function writeAllSheets()
	{
		$this->_sheetIndex = NULL;
	}

	public function generateHTMLHeader($pIncludeStyles = false)
	{
		if (is_null($this->_phpExcel)) {
			throw new Exception('Internal PHPExcel object not set to an instance of an object.');
		}

		$html = '';
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' . "\r\n";
		$html .= '<!-- Generated by PHPExcel - http://www.phpexcel.net -->' . "\r\n";
		$html .= '<html>' . "\r\n";
		$html .= '  <head>' . "\r\n";
		$html .= '    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\r\n";
		$html .= '    <title>' . htmlspecialchars($this->_phpExcel->getProperties()->getTitle()) . '</title>' . "\r\n";

		if ($pIncludeStyles) {
			$html .= $this->generateStyles(true);
		}

		$html .= '  </head>' . "\r\n";
		$html .= '' . "\r\n";
		$html .= '  <body>' . "\r\n";
		return $html;
	}

	public function generateSheetData()
	{
		if (is_null($this->_phpExcel)) {
			throw new Exception('Internal PHPExcel object not set to an instance of an object.');
		}

		if (!$this->_spansAreCalculated) {
			$this->_calculateSpans();
		}

		$sheets = array();

		if (is_null($this->_sheetIndex)) {
			$sheets = $this->_phpExcel->getAllSheets();
		}
		else {
			$sheets[] = $this->_phpExcel->getSheet($this->_sheetIndex);
		}

		$html = '';
		$sheetId = 0;

		foreach ($sheets as $sheet) {
			$html .= $this->_generateTableHeader($sheet);
			$dimension = explode(':', $sheet->calculateWorksheetDimension());
			$dimension[0] = PHPExcel_Cell::coordinateFromString($dimension[0]);
			$dimension[0][0] = PHPExcel_Cell::columnIndexFromString($dimension[0][0]) - 1;
			$dimension[1] = PHPExcel_Cell::coordinateFromString($dimension[1]);
			$dimension[1][0] = PHPExcel_Cell::columnIndexFromString($dimension[1][0]) - 1;
			$rowMin = $dimension[0][1];
			$rowMax = $dimension[1][1];
			$tbodyStart = $rowMin;
			$tbodyEnd = $rowMax;
			$theadStart = 0;
			$theadEnd = 0;

			if ($sheet->getPageSetup()->isRowsToRepeatAtTopSet()) {
				$rowsToRepeatAtTop = $sheet->getPageSetup()->getRowsToRepeatAtTop();

				if ($rowsToRepeatAtTop[0] == 1) {
					$theadStart = $rowsToRepeatAtTop[0];
					$theadEnd = $rowsToRepeatAtTop[1];
					$tbodyStart = $rowsToRepeatAtTop[1] + 1;
				}
			}

			$rowData = NULL;

			for ($row = $rowMin; $row <= $rowMax; ++$row) {
				$rowData = array();

				for ($column = $dimension[0][0]; $column <= $dimension[1][0]; ++$column) {
					if ($sheet->cellExistsByColumnAndRow($column, $row)) {
						$rowData[$column] = $cell = $sheet->getCellByColumnAndRow($column, $row);
					}
					else {
						$rowData[$column] = '';
					}
				}

				if ($row == $theadStart) {
					$html .= '        <thead>' . "\r\n";
				}

				if ($row == $tbodyStart) {
					$html .= '        <tbody>' . "\r\n";
				}

				if (!isset($this->_isSpannedRow[$sheet->getParent()->getIndex($sheet)][$row])) {
					$html .= $this->_generateRow($sheet, $rowData, $row - 1);
				}

				if ($row == $theadEnd) {
					$html .= '        </thead>' . "\r\n";
				}

				if ($row == $tbodyEnd) {
					$html .= '        </tbody>' . "\r\n";
				}
			}

			$html .= $this->_generateTableFooter();

			if ($this->_isPdf) {
				if (is_null($this->_sheetIndex) && (($sheetId + 1) < $this->_phpExcel->getSheetCount())) {
					$html .= '<tcpdf method="AddPage" />';
				}
			}

			++$sheetId;
		}

		return $html;
	}

	public function generateNavigation()
	{
		if (is_null($this->_phpExcel)) {
			throw new Exception('Internal PHPExcel object not set to an instance of an object.');
		}

		$sheets = array();

		if (is_null($this->_sheetIndex)) {
			$sheets = $this->_phpExcel->getAllSheets();
		}
		else {
			$sheets[] = $this->_phpExcel->getSheet($this->_sheetIndex);
		}

		$html = '';

		if (1 < count($sheets)) {
			$sheetId = 0;
			$html .= '<ul class="navigation">' . "\r\n";

			foreach ($sheets as $sheet) {
				$html .= '  <li class="sheet' . $sheetId . '"><a href="#sheet' . $sheetId . '">' . $sheet->getTitle() . '</a></li>' . "\r\n";
				++$sheetId;
			}

			$html .= '</ul>' . "\r\n";
		}

		return $html;
	}

	private function _writeImageTagInCell(PHPExcel_Worksheet $pSheet, $coordinates)
	{
		$html = '';

		foreach ($pSheet->getDrawingCollection() as $drawing) {
			if ($drawing instanceof PHPExcel_Worksheet_Drawing) {
				if ($drawing->getCoordinates() == $coordinates) {
					$filename = $drawing->getPath();

					if (substr($filename, 0, 1) == '.') {
						$filename = substr($filename, 1);
					}

					$filename = $this->getImagesRoot() . $filename;
					if ((substr($filename, 0, 1) == '.') && (substr($filename, 0, 2) != './')) {
						$filename = substr($filename, 1);
					}

					$filename = htmlspecialchars($filename);
					$html .= "\r\n";
					$html .= '        <img style="position: relative; left: ' . $drawing->getOffsetX() . 'px; top: ' . $drawing->getOffsetY() . 'px; width: ' . $drawing->getWidth() . 'px; height: ' . $drawing->getHeight() . 'px;" src="' . $filename . '" border="0" width="' . $drawing->getWidth() . '" height="' . $drawing->getHeight() . '" />' . "\r\n";
				}
			}
		}

		return $html;
	}

	public function generateStyles($generateSurroundingHTML = true)
	{
		if (is_null($this->_phpExcel)) {
			throw new Exception('Internal PHPExcel object not set to an instance of an object.');
		}

		$css = $this->buildCSS($generateSurroundingHTML);
		$html = '';

		if ($generateSurroundingHTML) {
			$html .= '    <style type="text/css">' . "\r\n";
			$html .= '      html { ' . $this->_assembleCSS($css['html']) . ' }' . "\r\n";
		}

		foreach ($css as $styleName => $styleDefinition) {
			if ($styleName != 'html') {
				$html .= '      ' . $styleName . ' { ' . $this->_assembleCSS($styleDefinition) . ' }' . "\r\n";
			}
		}

		if ($generateSurroundingHTML) {
			$html .= '    </style>' . "\r\n";
		}

		return $html;
	}

	public function buildCSS($generateSurroundingHTML = true)
	{
		if (is_null($this->_phpExcel)) {
			throw new Exception('Internal PHPExcel object not set to an instance of an object.');
		}

		if (!is_null($this->_cssStyles)) {
			return $this->_cssStyles;
		}

		if (!$this->_spansAreCalculated) {
			$this->_calculateSpans();
		}

		$css = array();

		if ($generateSurroundingHTML) {
			$css['html']['font-family'] = 'Calibri, Arial, Helvetica, sans-serif';
			$css['html']['font-size'] = '11pt';
			$css['html']['background-color'] = 'white';
		}

		$css['table']['border-collapse'] = 'collapse';
		$css['table']['page-break-after'] = 'always';
		$css['.gridlines td']['border'] = '1px dotted black';
		$css['.b']['text-align'] = 'center';
		$css['.e']['text-align'] = 'center';
		$css['.f']['text-align'] = 'right';
		$css['.inlineStr']['text-align'] = 'left';
		$css['.n']['text-align'] = 'right';
		$css['.s']['text-align'] = 'left';

		foreach ($this->_phpExcel->getCellXfCollection() as $index => $style) {
			$css['td.style' . $index] = $this->_createCSSStyle($style);
		}

		$sheets = array();

		if (is_null($this->_sheetIndex)) {
			$sheets = $this->_phpExcel->getAllSheets();
		}
		else {
			$sheets[] = $this->_phpExcel->getSheet($this->_sheetIndex);
		}

		foreach ($sheets as $sheet) {
			$sheetIndex = $sheet->getParent()->getIndex($sheet);
			$sheet->calculateColumnWidths();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn()) - 1;

			for ($column = 0; $column <= $highestColumnIndex; ++$column) {
				$this->_columnWidths[$sheetIndex][$column] = 42;
				$css['table.sheet' . $sheetIndex . ' col.col' . $column]['width'] = '42pt';
			}

			foreach ($sheet->getColumnDimensions() as $columnDimension) {
				if (0 <= $width = PHPExcel_Shared_Drawing::cellDimensionToPixels($columnDimension->getWidth(), $this->_defaultFont)) {
					$width = PHPExcel_Shared_Drawing::pixelsToPoints($width);
					$column = PHPExcel_Cell::columnIndexFromString($columnDimension->getColumnIndex()) - 1;
					$this->_columnWidths[$sheetIndex][$column] = $width;
					$css['table.sheet' . $sheetIndex . ' col.col' . $column]['width'] = $width . 'pt';

					if ($columnDimension->getVisible() === false) {
						$css['table.sheet' . $sheetIndex . ' col.col' . $column]['visibility'] = 'collapse';
						$css['table.sheet' . $sheetIndex . ' col.col' . $column]['*display'] = 'none';
					}
				}
			}

			$rowDimension = $sheet->getDefaultRowDimension();
			$css['table.sheet' . $sheetIndex . ' tr'] = array();

			if ($rowDimension->getRowHeight() == -1) {
				$pt_height = PHPExcel_Shared_Font::getDefaultRowHeightByFont($this->_phpExcel->getDefaultStyle()->getFont());
			}
			else {
				$pt_height = $rowDimension->getRowHeight();
			}

			$css['table.sheet' . $sheetIndex . ' tr']['height'] = $pt_height . 'pt';

			if ($rowDimension->getVisible() === false) {
				$css['table.sheet' . $sheetIndex . ' tr']['display'] = 'none';
				$css['table.sheet' . $sheetIndex . ' tr']['visibility'] = 'hidden';
			}

			foreach ($sheet->getRowDimensions() as $rowDimension) {
				$row = $rowDimension->getRowIndex() - 1;
				$css['table.sheet' . $sheetIndex . ' tr.row' . $row] = array();

				if ($rowDimension->getRowHeight() == -1) {
					$pt_height = PHPExcel_Shared_Font::getDefaultRowHeightByFont($this->_phpExcel->getDefaultStyle()->getFont());
				}
				else {
					$pt_height = $rowDimension->getRowHeight();
				}

				$css['table.sheet' . $sheetIndex . ' tr.row' . $row]['height'] = $pt_height . 'pt';

				if ($rowDimension->getVisible() === false) {
					$css['table.sheet' . $sheetIndex . ' tr.row' . $row]['display'] = 'none';
					$css['table.sheet' . $sheetIndex . ' tr.row' . $row]['visibility'] = 'hidden';
				}
			}
		}

		if (is_null($this->_cssStyles)) {
			$this->_cssStyles = $css;
		}

		return $css;
	}

	private function _createCSSStyle(PHPExcel_Style $pStyle)
	{
		$css = '';
		$css = array_merge($this->_createCSSStyleAlignment($pStyle->getAlignment()), $this->_createCSSStyleBorders($pStyle->getBorders()), $this->_createCSSStyleFont($pStyle->getFont()), $this->_createCSSStyleFill($pStyle->getFill()));
		return $css;
	}

	private function _createCSSStyleAlignment(PHPExcel_Style_Alignment $pStyle)
	{
		$css = array();
		$css['vertical-align'] = $this->_mapVAlign($pStyle->getVertical());

		if ($textAlign = $this->_mapHAlign($pStyle->getHorizontal())) {
			$css['text-align'] = $textAlign;
		}

		return $css;
	}

	private function _createCSSStyleFont(PHPExcel_Style_Font $pStyle)
	{
		$css = array();

		if ($pStyle->getBold()) {
			$css['font-weight'] = 'bold';
		}

		if (($pStyle->getUnderline() != PHPExcel_Style_Font::UNDERLINE_NONE) && $pStyle->getStrikethrough()) {
			$css['text-decoration'] = 'underline line-through';
		}
		else if ($pStyle->getUnderline() != PHPExcel_Style_Font::UNDERLINE_NONE) {
			$css['text-decoration'] = 'underline';
		}
		else if ($pStyle->getStrikethrough()) {
			$css['text-decoration'] = 'line-through';
		}

		if ($pStyle->getItalic()) {
			$css['font-style'] = 'italic';
		}

		$css['color'] = '#' . $pStyle->getColor()->getRGB();
		$css['font-family'] = '\'' . $pStyle->getName() . '\'';
		$css['font-size'] = $pStyle->getSize() . 'pt';
		return $css;
	}

	private function _createCSSStyleBorders(PHPExcel_Style_Borders $pStyle)
	{
		$css = array();
		$css['border-bottom'] = $this->_createCSSStyleBorder($pStyle->getBottom());
		$css['border-top'] = $this->_createCSSStyleBorder($pStyle->getTop());
		$css['border-left'] = $this->_createCSSStyleBorder($pStyle->getLeft());
		$css['border-right'] = $this->_createCSSStyleBorder($pStyle->getRight());
		return $css;
	}

	private function _createCSSStyleBorder(PHPExcel_Style_Border $pStyle)
	{
		$css = '';
		$css .= $this->_mapBorderStyle($pStyle->getBorderStyle()) . ' #' . $pStyle->getColor()->getRGB();
		return $css;
	}

	private function _createCSSStyleFill(PHPExcel_Style_Fill $pStyle)
	{
		$css = array();
		$value = ($pStyle->getFillType() == PHPExcel_Style_Fill::FILL_NONE ? 'white' : '#' . $pStyle->getStartColor()->getRGB());
		$css['background-color'] = $value;
		return $css;
	}

	public function generateHTMLFooter()
	{
		$html = '';
		$html .= '  </body>' . "\r\n";
		$html .= '</html>' . "\r\n";
		return $html;
	}

	private function _generateTableHeader($pSheet)
	{
		$sheetIndex = $pSheet->getParent()->getIndex($pSheet);
		$html = '';

		if (!$this->_useInlineCss) {
			$gridlines = ($pSheet->getShowGridLines() ? ' gridlines' : '');
			$html .= '    <table border="0" cellpadding="0" cellspacing="0" id="sheet' . $sheetIndex . '" class="sheet' . $sheetIndex . $gridlines . '">' . "\r\n";
		}
		else {
			$style = (isset($this->_cssStyles['table']) ? $this->_assembleCSS($this->_cssStyles['table']) : '');
			if ($this->_isPdf && $pSheet->getShowGridLines()) {
				$html .= '    <table border="1" cellpadding="0" id="sheet' . $sheetIndex . '" cellspacing="0" style="' . $style . '">' . "\r\n";
			}
			else {
				$html .= '    <table border="0" cellpadding="0" id="sheet' . $sheetIndex . '" cellspacing="0" style="' . $style . '">' . "\r\n";
			}
		}

		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($pSheet->getHighestColumn()) - 1;

		for ($i = 0; $i <= $highestColumnIndex; ++$i) {
			if (!$this->_useInlineCss) {
				$html .= '        <col class="col' . $i . '">' . "\r\n";
			}
			else {
				$style = (isset($this->_cssStyles['table.sheet' . $sheetIndex . ' col.col' . $i]) ? $this->_assembleCSS($this->_cssStyles['table.sheet' . $sheetIndex . ' col.col' . $i]) : '');
				$html .= '        <col style="' . $style . '">' . "\r\n";
			}
		}

		return $html;
	}

	private function _generateTableFooter()
	{
		$html = '';
		$html .= '    </table>' . "\r\n";
		return $html;
	}

	private function _generateRow(PHPExcel_Worksheet $pSheet, $pValues = NULL, $pRow = 0)
	{
		if (is_array($pValues)) {
			$html = '';
			$sheetIndex = $pSheet->getParent()->getIndex($pSheet);
			if ($this->_isPdf && (0 < count($pSheet->getBreaks()))) {
				$breaks = $pSheet->getBreaks();

				if (isset($breaks['A' . $pRow])) {
					$html .= $this->_generateTableFooter();
					$html .= '<tcpdf method="AddPage" />';
					$html .= $this->_generateTableHeader($pSheet);
				}
			}

			if (!$this->_useInlineCss) {
				$html .= '          <tr class="row' . $pRow . '">' . "\r\n";
			}
			else {
				$style = (isset($this->_cssStyles['table.sheet' . $sheetIndex . ' tr.row' . $pRow]) ? $this->_assembleCSS($this->_cssStyles['table.sheet' . $sheetIndex . ' tr.row' . $pRow]) : '');
				$html .= '          <tr style="' . $style . '">' . "\r\n";
			}

			$colNum = 0;

			foreach ($pValues as $cell) {
				$coordinate = PHPExcel_Cell::stringFromColumnIndex($colNum) . ($pRow + 1);

				if (!$this->_useInlineCss) {
					$cssClass = '';
					$cssClass = 'column' . $colNum;
				}
				else {
					$cssClass = array();

					if (isset($this->_cssStyles['table.sheet' . $sheetIndex . ' td.column' . $colNum])) {
					}
				}

				$colSpan = 1;
				$rowSpan = 1;
				$writeCell = true;
				$cellData = '';

				if ($cell instanceof PHPExcel_Cell) {
					if (is_null($cell->getParent())) {
						$cell->attach($pSheet);
					}

					if ($cell->getValue() instanceof PHPExcel_RichText) {
						$elements = $cell->getValue()->getRichTextElements();

						foreach ($elements as $element) {
							if ($element instanceof PHPExcel_RichText_Run) {
								$cellData .= '<span style="' . $this->_assembleCSS($this->_createCSSStyleFont($element->getFont())) . '">';

								if ($element->getFont()->getSuperScript()) {
									$cellData .= '<sup>';
								}
								else if ($element->getFont()->getSubScript()) {
									$cellData .= '<sub>';
								}
							}

							$cellText = $element->getText();
							$cellData .= htmlspecialchars($cellText);

							if ($element instanceof PHPExcel_RichText_Run) {
								if ($element->getFont()->getSuperScript()) {
									$cellData .= '</sup>';
								}
								else if ($element->getFont()->getSubScript()) {
									$cellData .= '</sub>';
								}

								$cellData .= '</span>';
							}
						}
					}
					else if ($this->_preCalculateFormulas) {
						$cellData = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), $pSheet->getParent()->getCellXfByIndex($cell->getXfIndex())->getNumberFormat()->getFormatCode(), array($this, 'formatColor'));
					}
					else {
						$cellData = PHPExcel_Style_NumberFormat::ToFormattedString($cell->getValue(), $pSheet->getParent()->getCellXfByIndex($cell->getXfIndex())->getNumberFormat()->getFormatCode(), array($this, 'formatColor'));
					}

					$cellData = $this->_convertNbsp($cellData);
					$cellData = str_replace("\n", '<br/>', $cellData);

					if (!$this->_useInlineCss) {
						$cssClass .= ' style' . $cell->getXfIndex();
						$cssClass .= ' ' . $cell->getDataType();
					}
					else {
						if (isset($this->_cssStyles['td.style' . $cell->getXfIndex()])) {
							$cssClass = array_merge($cssClass, $this->_cssStyles['td.style' . $cell->getXfIndex()]);
						}

						$sharedStyle = $pSheet->getParent()->getCellXfByIndex($cell->getXfIndex());
						if (($sharedStyle->getAlignment()->getHorizontal() == PHPExcel_Style_Alignment::HORIZONTAL_GENERAL) && isset($this->_cssStyles['.' . $cell->getDataType()]['text-align'])) {
							$cssClass['text-align'] = $this->_cssStyles['.' . $cell->getDataType()]['text-align'];
						}
					}
				}

				if ($pSheet->hyperlinkExists($coordinate) && !$pSheet->getHyperlink($coordinate)->isInternal()) {
					$cellData = '<a href="' . htmlspecialchars($pSheet->getHyperlink($coordinate)->getUrl()) . '" title="' . htmlspecialchars($pSheet->getHyperlink($coordinate)->getTooltip()) . '">' . $cellData . '</a>';
				}

				$writeCell = !(isset($this->_isSpannedCell[$pSheet->getParent()->getIndex($pSheet)][$pRow + 1][$colNum]) && $this->_isSpannedCell[$pSheet->getParent()->getIndex($pSheet)][$pRow + 1][$colNum]);
				$colspan = 1;
				$rowspan = 1;

				if (isset($this->_isBaseCell[$pSheet->getParent()->getIndex($pSheet)][$pRow + 1][$colNum])) {
					$spans = $this->_isBaseCell[$pSheet->getParent()->getIndex($pSheet)][$pRow + 1][$colNum];
					$rowSpan = $spans['rowspan'];
					$colSpan = $spans['colspan'];
				}

				if ($writeCell) {
					$html .= '            <td';

					if (!$this->_useInlineCss) {
						$html .= ' class="' . $cssClass . '"';
					}
					else {
						$width = 0;

						for ($i = $colNum; $i < ($colNum + $colSpan); ++$i) {
							if (isset($this->_columnWidths[$sheetIndex][$i])) {
								$width += $this->_columnWidths[$sheetIndex][$i];
							}
						}

						$cssClass['width'] = $width . 'pt';

						if (isset($this->_cssStyles['table.sheet' . $sheetIndex . ' tr.row' . $pRow]['height'])) {
							$height = $this->_cssStyles['table.sheet' . $sheetIndex . ' tr.row' . $pRow]['height'];
							$cssClass['height'] = $height;
						}

						$html .= ' style="' . $this->_assembleCSS($cssClass) . '"';
					}

					if (1 < $colSpan) {
						$html .= ' colspan="' . $colSpan . '"';
					}

					if (1 < $rowSpan) {
						$html .= ' rowspan="' . $rowSpan . '"';
					}

					$html .= '>';
					$html .= $this->_writeImageTagInCell($pSheet, $coordinate);
					$html .= $cellData;
					$html .= '</td>' . "\r\n";
				}

				++$colNum;
			}

			$html .= '          </tr>' . "\r\n";
			return $html;
		}
		else {
			throw new Exception('Invalid parameters passed.');
		}
	}

	private function _assembleCSS($pValue = array())
	{
		$pairs = array();

		foreach ($pValue as $property => $value) {
			$pairs[] = $property . ':' . $value;
		}

		$string = implode('; ', $pairs);
		return $string;
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

	public function getImagesRoot()
	{
		return $this->_imagesRoot;
	}

	public function setImagesRoot($pValue = '.')
	{
		$this->_imagesRoot = $pValue;
		return $this;
	}

	public function getUseInlineCss()
	{
		return $this->_useInlineCss;
	}

	public function setUseInlineCss($pValue = false)
	{
		$this->_useInlineCss = $pValue;
		return $this;
	}

	private function _convertNbsp($pValue = '')
	{
		$explodes = explode("\n", $pValue);

		foreach ($explodes as $explode) {
			$matches = array();

			if (preg_match('/^( )+/', $explode, $matches)) {
				$explode = str_repeat('&nbsp;', strlen($matches[0])) . substr($explode, strlen($matches[0]));
			}

			$implodes[] = $explode;
		}

		$string = implode("\n", $implodes);
		return $string;
	}

	public function formatColor($pValue, $pFormat)
	{
		$color = NULL;
		$matches = array();
		$color_regex = '/^\\[[a-zA-Z]+\\]/';

		if (preg_match($color_regex, $pFormat, $matches)) {
			$color = str_replace('[', '', $matches[0]);
			$color = str_replace(']', '', $color);
			$color = strtolower($color);
		}

		$value = htmlspecialchars($pValue);

		if ($color !== NULL) {
			$value = '<span style="color:' . $color . '">' . $value . '</span>';
		}

		return $value;
	}

	private function _calculateSpans()
	{
		$sheetIndexes = ($this->_sheetIndex !== NULL ? array($this->_sheetIndex) : range(0, $this->_phpExcel->getSheetCount() - 1));

		foreach ($sheetIndexes as $sheetIndex) {
			$sheet = $this->_phpExcel->getSheet($sheetIndex);
			$candidateSpannedRow = array();

			foreach ($sheet->getMergeCells() as $cells) {
				list($cells) = PHPExcel_Cell::splitRange($cells);
				$first = $cells[0];
				$last = $cells[1];
				list($fc, $fr) = PHPExcel_Cell::coordinateFromString($first);
				$fc = PHPExcel_Cell::columnIndexFromString($fc) - 1;
				list($lc, $lr) = PHPExcel_Cell::coordinateFromString($last);
				$lc = PHPExcel_Cell::columnIndexFromString($lc) - 1;

				for ($r = $fr; $r <= $lr; ++$r) {
					$candidateSpannedRow[$r] = $r;

					for ($c = $fc; $c <= $lc; ++$c) {
						if (!(($c == $fc) && ($r == $fr))) {
							$this->_isSpannedCell[$sheetIndex][$r][$c] = array(
	'baseCell' => array($fr, $fc)
	);
						}
						else {
							$this->_isBaseCell[$sheetIndex][$r][$c] = array('xlrowspan' => ($lr - $fr) + 1, 'rowspan' => ($lr - $fr) + 1, 'xlcolspan' => ($lc - $fc) + 1, 'colspan' => ($lc - $fc) + 1);
						}
					}
				}
			}

			$countColumns = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());

			foreach ($candidateSpannedRow as $rowIndex) {
				if (isset($this->_isSpannedCell[$sheetIndex][$rowIndex])) {
					if (count($this->_isSpannedCell[$sheetIndex][$rowIndex]) == $countColumns) {
						$this->_isSpannedRow[$sheetIndex][$rowIndex] = $rowIndex;
					}
				}
			}

			if (isset($this->_isSpannedRow[$sheetIndex])) {
				foreach ($this->_isSpannedRow[$sheetIndex] as $rowIndex) {
					$adjustedBaseCells = array();

					for ($c = 0; $c < $countColumns; ++$c) {
						$baseCell = $this->_isSpannedCell[$sheetIndex][$rowIndex][$c]['baseCell'];

						if (!in_array($baseCell, $adjustedBaseCells)) {
							--$this->_isBaseCell[$sheetIndex][$baseCell[0]][$baseCell[1]]['rowspan'];
							$adjustedBaseCells[] = $baseCell;
						}
					}
				}
			}
		}

		$this->_spansAreCalculated = true;
	}
}

?>
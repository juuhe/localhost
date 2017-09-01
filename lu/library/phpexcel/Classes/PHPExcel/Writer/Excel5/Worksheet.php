<?php

class PHPExcel_Writer_Excel5_Worksheet extends PHPExcel_Writer_Excel5_BIFFwriter
{
	/**
	 * Formula parser
	 *
	 * @var PHPExcel_Writer_Excel5_Parser
	 */
	private $_parser;
	/**
	 * Maximum number of characters for a string (LABEL record in BIFF5)
	 * @var integer
	 */
	public $_xls_strmax;
	/**
	 * Array containing format information for columns
	 * @var array
	 */
	public $_colinfo;
	/**
	 * Array containing the selected area for the worksheet
	 * @var array
	 */
	public $_selection;
	/**
	 * The active pane for the worksheet
	 * @var integer
	 */
	public $_active_pane;
	/**
	 * Whether to use outline.
	 * @var integer
	 */
	public $_outline_on;
	/**
	 * Auto outline styles.
	 * @var bool
	 */
	public $_outline_style;
	/**
	 * Whether to have outline summary below.
	 * @var bool
	 */
	public $_outline_below;
	/**
	 * Whether to have outline summary at the right.
	 * @var bool
	 */
	public $_outline_right;
	/**
	 * Reference to the total number of strings in the workbook
	 * @var integer
	 */
	public $_str_total;
	/**
	 * Reference to the number of unique strings in the workbook
	 * @var integer
	 */
	public $_str_unique;
	/**
	 * Reference to the array containing all the unique strings in the workbook
	 * @var array
	 */
	public $_str_table;
	/**
	 * Color cache
	 */
	private $_colors;
	/**
	 * Index of first used row (at least 0)
	 * @var int
	 */
	private $_firstRowIndex;
	/**
	 * Index of last used row. (no used rows means -1)
	 * @var int
	 */
	private $_lastRowIndex;
	/**
	 * Index of first used column (at least 0)
	 * @var int
	 */
	private $_firstColumnIndex;
	/**
	 * Index of last used column (no used columns means -1)
	 * @var int
	 */
	private $_lastColumnIndex;
	/**
	 * Sheet object
	 * @var PHPExcel_Worksheet
	 */
	private $_phpSheet;
	/**
	 * Count cell style Xfs
	 *
	 * @var int
	 */
	private $_countCellStyleXfs;

	public function __construct($BIFF_version, &$str_total, &$str_unique, &$str_table, &$colors, $parser, $preCalculateFormulas, $phpSheet)
	{
		parent::__construct();
		$this->_BIFF_version = $BIFF_version;

		if ($BIFF_version == 1536) {
			$this->_limit = 8224;
		}

		$this->_preCalculateFormulas = $preCalculateFormulas;
		$this->_str_total = &$str_total;
		$this->_str_unique = &$str_unique;
		$this->_str_table = &$str_table;
		$this->_colors = &$colors;
		$this->_parser = $parser;
		$this->_phpSheet = $phpSheet;
		$this->_xls_strmax = 255;
		$this->_colinfo = array();
		$this->_selection = array(0, 0, 0, 0);
		$this->_active_pane = 3;
		$this->_print_headers = 0;
		$this->_outline_style = 0;
		$this->_outline_below = 1;
		$this->_outline_right = 1;
		$this->_outline_on = 1;
		$this->_firstRowIndex = 0;
		$this->_lastRowIndex = -1;
		$this->_firstColumnIndex = 0;
		$this->_lastColumnIndex = -1;

		foreach ($this->_phpSheet->getCellCollection(false) as $cellID) {
			preg_match('/^(\\w+)(\\d+)$/U', $cellID, $matches);
			$column = PHPExcel_Cell::columnIndexFromString($col) - 1;
			if ((65536 < ($row + 1)) || (256 < ($column + 1))) {
				break;
			}

			$this->_firstRowIndex = min($this->_firstRowIndex, $row);
			$this->_lastRowIndex = max($this->_lastRowIndex, $row);
			$this->_firstColumnIndex = min($this->_firstColumnIndex, $column);
			$this->_lastColumnIndex = max($this->_lastColumnIndex, $column);
		}

		$this->_countCellStyleXfs = count($phpSheet->getParent()->getCellStyleXfCollection());
	}

	public function close()
	{
		$num_sheets = $this->_phpSheet->getParent()->getSheetCount();
		$this->_storeBof(16);
		$this->_writePrintHeaders();
		$this->_writePrintGridlines();
		$this->_writeGridset();
		$this->_phpSheet->calculateColumnWidths();
		$columnDimensions = $this->_phpSheet->getColumnDimensions();

		for ($i = 0; $i < 256; ++$i) {
			$hidden = 0;
			$level = 0;
			$xfIndex = 15;

			if (0 <= $this->_phpSheet->getDefaultColumnDimension()->getWidth()) {
				$width = $this->_phpSheet->getDefaultColumnDimension()->getWidth();
			}
			else {
				$width = PHPExcel_Shared_Font::getDefaultColumnWidthByFont($this->_phpSheet->getParent()->getDefaultStyle()->getFont());
			}

			$columnLetter = PHPExcel_Cell::stringFromColumnIndex($i);

			if (isset($columnDimensions[$columnLetter])) {
				$columnDimension = $columnDimensions[$columnLetter];

				if (0 <= $columnDimension->getWidth()) {
					$width = $columnDimension->getWidth();
				}

				$hidden = ($columnDimension->getVisible() ? 0 : 1);
				$level = $columnDimension->getOutlineLevel();
				$xfIndex = $columnDimension->getXfIndex() + 15;
			}

			$this->_colinfo[] = array($i, $i, $width, $xfIndex, $hidden, $level);
		}

		$this->_writeGuts();

		if ($this->_BIFF_version == 1536) {
			$this->_writeDefaultRowHeight();
		}

		$this->_writeWsbool();
		$this->_writeBreaks();
		$this->_writeHeader();
		$this->_writeFooter();
		$this->_writeHcenter();
		$this->_writeVcenter();
		$this->_writeMarginLeft();
		$this->_writeMarginRight();
		$this->_writeMarginTop();
		$this->_writeMarginBottom();
		$this->_writeSetup();
		$this->_writeProtect();
		$this->_writeScenProtect();
		$this->_writeObjectProtect();
		$this->_writePassword();
		$this->_writeDefcol();

		if (!empty($this->_colinfo)) {
			$colcount = count($this->_colinfo);

			for ($i = 0; $i < $colcount; ++$i) {
				$this->_writeColinfo($this->_colinfo[$i]);
			}
		}

		if ($this->_BIFF_version == 1280) {
			$this->_writeExterncount($num_sheets);
		}

		if ($this->_BIFF_version == 1280) {
			for ($i = 0; $i < $num_sheets; ++$i) {
				$this->_writeExternsheet($this->_phpSheet->getParent()->getSheet($i)->getTitle());
			}
		}

		$this->_writeDimensions();

		foreach ($this->_phpSheet->getRowDimensions() as $rowDimension) {
			$xfIndex = $rowDimension->getXfIndex() + 15;
			$this->_writeRow($rowDimension->getRowIndex() - 1, $rowDimension->getRowHeight(), $xfIndex, $rowDimension->getVisible() ? '0' : '1', $rowDimension->getOutlineLevel());
		}

		foreach ($this->_phpSheet->getCellCollection() as $cellID) {
			$cell = $this->_phpSheet->getCell($cellID);
			$row = $cell->getRow() - 1;
			$column = PHPExcel_Cell::columnIndexFromString($cell->getColumn()) - 1;
			if ((65536 < ($row + 1)) || (256 < ($column + 1))) {
				break;
			}

			$xfIndex = $cell->getXfIndex() + 15;

			if ($cell->getValue() instanceof PHPExcel_RichText) {
				$this->_writeString($row, $column, $cell->getValue()->getPlainText(), $xfIndex);
			}
			else {
				switch ($cell->getDatatype()) {
				case PHPExcel_Cell_DataType::TYPE_STRING:
					if (($cell->getValue() === '') || ($cell->getValue() === NULL)) {
						$this->_writeBlank($row, $column, $xfIndex);
					}
					else {
						$this->_writeString($row, $column, $cell->getValue(), $xfIndex);
					}

					break;

				case PHPExcel_Cell_DataType::TYPE_FORMULA:
					$calculatedValue = ($this->_preCalculateFormulas ? $cell->getCalculatedValue() : NULL);
					$this->_writeFormula($row, $column, $cell->getValue(), $xfIndex, $calculatedValue);
					break;

				case PHPExcel_Cell_DataType::TYPE_BOOL:
					$this->_writeBoolErr($row, $column, $cell->getValue(), 0, $xfIndex);
					break;

				case PHPExcel_Cell_DataType::TYPE_ERROR:
					$this->_writeBoolErr($row, $column, $this->_mapErrorCode($cell->getValue()), 1, $xfIndex);
					break;

				case PHPExcel_Cell_DataType::TYPE_NUMERIC:
					$this->_writeNumber($row, $column, $cell->getValue(), $xfIndex);
					break;
				}
			}
		}

		if ($this->_BIFF_version == 1536) {
			$this->_writeMsoDrawing();
		}

		$this->_writeWindow2();
		$this->_writeZoom();

		if ($this->_phpSheet->getFreezePane()) {
			$this->_writePanes();
		}

		$this->_writeSelection();
		$this->_writeMergedCells();

		if ($this->_BIFF_version == 1536) {
			foreach ($this->_phpSheet->getHyperLinkCollection() as $coordinate => $hyperlink) {
				list($column, $row) = PHPExcel_Cell::coordinateFromString($coordinate);
				$url = $hyperlink->getUrl();

				if (strpos($url, 'sheet://') !== false) {
					$url = str_replace('sheet://', 'internal:', $url);
				}
				else if (preg_match('/^(http:|https:|ftp:|mailto:)/', $url)) {
				}
				else {
					$url = 'external:' . $url;
				}

				$this->_writeUrl($row - 1, PHPExcel_Cell::columnIndexFromString($column) - 1, $url);
			}
		}

		if ($this->_BIFF_version == 1536) {
			$this->_writeDataValidity();
			$this->_writeSheetLayout();
			$this->_writeSheetProtection();
			$this->_writeRangeProtection();
		}

		$this->_storeEof();
	}

	private function _writeBIFF8CellRangeAddressFixed($range = 'A1')
	{
		$explodes = explode(':', $range);
		$firstCell = $explodes[0];

		if (count($explodes) == 1) {
			$lastCell = $firstCell;
		}
		else {
			$lastCell = $explodes[1];
		}

		$firstCellCoordinates = PHPExcel_Cell::coordinateFromString($firstCell);
		$lastCellCoordinates = PHPExcel_Cell::coordinateFromString($lastCell);
		$data = pack('vvvv', $firstCellCoordinates[1] - 1, $lastCellCoordinates[1] - 1, PHPExcel_Cell::columnIndexFromString($firstCellCoordinates[0]) - 1, PHPExcel_Cell::columnIndexFromString($lastCellCoordinates[0]) - 1);
		return $data;
	}

	public function getData()
	{
		$buffer = 4096;

		if (isset($this->_data)) {
			$tmp = $this->_data;
			unset($this->_data);
			return $tmp;
		}

		return false;
	}

	public function printRowColHeaders($print = 1)
	{
		$this->_print_headers = $print;
	}

	public function setOutline($visible = true, $symbols_below = true, $symbols_right = true, $auto_style = false)
	{
		$this->_outline_on = $visible;
		$this->_outline_below = $symbols_below;
		$this->_outline_right = $symbols_right;
		$this->_outline_style = $auto_style;

		if ($this->_outline_on) {
			$this->_outline_on = 1;
		}
	}

	private function _writeNumber($row, $col, $num, $xfIndex)
	{
		$record = 515;
		$length = 14;
		$header = pack('vv', $record, $length);
		$data = pack('vvv', $row, $col, $xfIndex);
		$xl_double = pack('d', $num);

		if (PHPExcel_Writer_Excel5_BIFFwriter::getByteOrder()) {
			$xl_double = strrev($xl_double);
		}

		$this->_append($header . $data . $xl_double);
		return 0;
	}

	private function _writeString($row, $col, $str, $xfIndex)
	{
		if ($this->_BIFF_version == 1536) {
			$this->_writeLabelSst($row, $col, $str, $xfIndex);
		}
		else {
			$this->_writeLabel($row, $col, $str, $xfIndex);
		}
	}

	private function _writeLabel($row, $col, $str, $xfIndex)
	{
		$strlen = strlen($str);
		$record = 516;
		$length = 8 + $strlen;
		$str_error = 0;

		if ($this->_xls_strmax < $strlen) {
			$str = substr($str, 0, $this->_xls_strmax);
			$length = 8 + $this->_xls_strmax;
			$strlen = $this->_xls_strmax;
			$str_error = -3;
		}

		$header = pack('vv', $record, $length);
		$data = pack('vvvv', $row, $col, $xfIndex, $strlen);
		$this->_append($header . $data . $str);
		return $str_error;
	}

	private function _writeLabelSst($row, $col, $str, $xfIndex)
	{
		$record = 253;
		$length = 10;
		$str = PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($str);

		if (!isset($this->_str_table[$str])) {
			$this->_str_table[$str] = $this->_str_unique++;
		}

		$this->_str_total++;
		$header = pack('vv', $record, $length);
		$data = pack('vvvV', $row, $col, $xfIndex, $this->_str_table[$str]);
		$this->_append($header . $data);
	}

	private function _writeNote($row, $col, $note)
	{
		$note_length = strlen($note);
		$record = 28;
		$max_length = 2048;
		$length = 6 + min($note_length, 2048);
		$header = pack('vv', $record, $length);
		$data = pack('vvv', $row, $col, $note_length);
		$this->_append($header . $data . substr($note, 0, 2048));

		for ($i = $max_length; $i < $note_length; $i += $max_length) {
			$chunk = substr($note, $i, $max_length);
			$length = 6 + strlen($chunk);
			$header = pack('vv', $record, $length);
			$data = pack('vvv', -1, 0, strlen($chunk));
			$this->_append($header . $data . $chunk);
		}

		return 0;
	}

	public function _writeBlank($row, $col, $xfIndex)
	{
		$record = 513;
		$length = 6;
		$header = pack('vv', $record, $length);
		$data = pack('vvv', $row, $col, $xfIndex);
		$this->_append($header . $data);
		return 0;
	}

	private function _writeBoolErr($row, $col, $value, $isError, $xfIndex)
	{
		$record = 517;
		$length = 8;
		$header = pack('vv', $record, $length);
		$data = pack('vvvCC', $row, $col, $xfIndex, $value, $isError);
		$this->_append($header . $data);
		return 0;
	}

	private function _writeFormula($row, $col, $formula, $xfIndex, $calculatedValue)
	{
		$record = 6;
		$stringValue = NULL;

		if (isset($calculatedValue)) {
			if (is_bool($calculatedValue)) {
				$num = pack('CCCvCv', 1, 0, (int) $calculatedValue, 0, 0, 65535);
			}
			else {
				if (is_int($calculatedValue) || is_float($calculatedValue)) {
					$num = pack('d', $calculatedValue);
				}
				else if (is_string($calculatedValue)) {
					if (array_key_exists($calculatedValue, PHPExcel_Cell_DataType::getErrorCodes())) {
						$num = pack('CCCvCv', 2, 0, $this->_mapErrorCode($calculatedValue), 0, 0, 65535);
					}
					else {
						if (($calculatedValue === '') && ($this->_BIFF_version == 1536)) {
							$num = pack('CCCvCv', 3, 0, 0, 0, 0, 65535);
						}
						else {
							$stringValue = $calculatedValue;
							$num = pack('CCCvCv', 0, 0, 0, 0, 0, 65535);
						}
					}
				}
				else {
					$num = pack('d', 0);
				}
			}
		}
		else {
			$num = pack('d', 0);
		}

		$grbit = 3;
		$unknown = 0;

		if (preg_match('/^=/', $formula)) {
			$formula = preg_replace('/(^=)/', '', $formula);
		}
		else {
			$this->_writeString($row, $col, 'Unrecognised character for formula');
			return -1;
		}

		try {
			$error = $this->_parser->parse($formula);
			$formula = $this->_parser->toReversePolish();
			$formlen = strlen($formula);
			$length = 22 + $formlen;
			$header = pack('vv', $record, $length);
			$data = pack('vvv', $row, $col, $xfIndex) . $num . pack('vVv', $grbit, $unknown, $formlen);
			$this->_append($header . $data . $formula);

			if ($stringValue !== NULL) {
				$this->_writeStringRecord($stringValue);
			}

			return 0;
		}
		catch (Exception $e) {
		}
	}

	private function _writeStringRecord($stringValue)
	{
		$record = 519;
		$data = PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($stringValue);
		$length = strlen($data);
		$header = pack('vv', $record, $length);
		$this->_append($header . $data);
	}

	private function _writeUrl($row, $col, $url)
	{
		return $this->_writeUrlRange($row, $col, $row, $col, $url);
	}

	public function _writeUrlRange($row1, $col1, $row2, $col2, $url)
	{
		if (preg_match('[^internal:]', $url)) {
			return $this->_writeUrlInternal($row1, $col1, $row2, $col2, $url);
		}

		if (preg_match('[^external:]', $url)) {
			return $this->_writeUrlExternal($row1, $col1, $row2, $col2, $url);
		}

		return $this->_writeUrlWeb($row1, $col1, $row2, $col2, $url);
	}

	public function _writeUrlWeb($row1, $col1, $row2, $col2, $url)
	{
		$record = 440;
		$length = 0;
		$unknown1 = pack('H*', 'D0C9EA79F9BACE118C8200AA004BA90B02000000');
		$unknown2 = pack('H*', 'E0C9EA79F9BACE118C8200AA004BA90B');
		$options = pack('V', 3);
		$url = join("\x00", preg_split('\'\'', $url, -1, PREG_SPLIT_NO_EMPTY));
		$url = $url . "\x00\x00\x00";
		$url_len = pack('V', strlen($url));
		$length = 52 + strlen($url);
		$header = pack('vv', $record, $length);
		$data = pack('vvvv', $row1, $row2, $col1, $col2);
		$this->_append($header . $data . $unknown1 . $options . $unknown2 . $url_len . $url);
		return 0;
	}

	public function _writeUrlInternal($row1, $col1, $row2, $col2, $url)
	{
		$record = 440;
		$length = 0;
		$url = preg_replace('/^internal:/', '', $url);
		$unknown1 = pack('H*', 'D0C9EA79F9BACE118C8200AA004BA90B02000000');
		$options = pack('V', 8);
		$url .= "\x00";
		$url_len = PHPExcel_Shared_String::CountCharacters($url);
		$url_len = pack('V', $url_len);
		$url = PHPExcel_Shared_String::ConvertEncoding($url, 'UTF-16LE', 'UTF-8');
		$length = 36 + strlen($url);
		$header = pack('vv', $record, $length);
		$data = pack('vvvv', $row1, $row2, $col1, $col2);
		$this->_append($header . $data . $unknown1 . $options . $url_len . $url);
		return 0;
	}

	public function _writeUrlExternal($row1, $col1, $row2, $col2, $url)
	{
		if (preg_match('[^external:\\\\]', $url)) {
			return NULL;
		}

		$record = 440;
		$length = 0;
		$url = preg_replace('/^external:/', '', $url);
		$url = preg_replace('/\\//', '\\', $url);
		$absolute = 0;

		if (preg_match('/^[A-Z]:/', $url)) {
			$absolute = 2;
		}

		$link_type = 1 | $absolute;
		$dir_long = $url;

		if (preg_match('/\\#/', $url)) {
			$link_type |= 8;
		}

		$link_type = pack('V', $link_type);
		$up_count = preg_match_all('/\\.\\.\\\\/', $dir_long, $useless);
		$up_count = pack('v', $up_count);
		$dir_short = preg_replace('/\\.\\.\\\\/', '', $dir_long) . "\x00";
		$dir_long = $dir_long . "\x00";
		$dir_short_len = pack('V', strlen($dir_short));
		$dir_long_len = pack('V', strlen($dir_long));
		$stream_len = pack('V', 0);
		$unknown1 = pack('H*', 'D0C9EA79F9BACE118C8200AA004BA90B02000000');
		$unknown2 = pack('H*', '0303000000000000C000000000000046');
		$unknown3 = pack('H*', 'FFFFADDE000000000000000000000000000000000000000');
		$unknown4 = pack('v', 3);
		$data = pack('vvvv', $row1, $row2, $col1, $col2) . $unknown1 . $link_type . $unknown2 . $up_count . $dir_short_len . $dir_short . $unknown3 . $stream_len;
		$length = strlen($data);
		$header = pack('vv', $record, $length);
		$this->_append($header . $data);
		return 0;
	}

	private function _writeRow($row, $height, $xfIndex, $hidden = false, $level = 0)
	{
		$record = 520;
		$length = 16;
		$colMic = 0;
		$colMac = 0;
		$irwMac = 0;
		$reserved = 0;
		$grbit = 0;
		$ixfe = $xfIndex;

		if ($height < 0) {
			$height = NULL;
		}

		if ($height != NULL) {
			$miyRw = $height * 20;
		}
		else {
			$miyRw = 255;
		}

		$grbit |= $level;

		if ($hidden) {
			$grbit |= 32;
		}

		if ($height !== NULL) {
			$grbit |= 64;
		}

		if ($xfIndex !== 15) {
			$grbit |= 128;
		}

		$grbit |= 256;
		$header = pack('vv', $record, $length);
		$data = pack('vvvvvvvv', $row, $colMic, $colMac, $miyRw, $irwMac, $reserved, $grbit, $ixfe);
		$this->_append($header . $data);
	}

	private function _writeDimensions()
	{
		$record = 512;

		if ($this->_BIFF_version == 1280) {
			$length = 10;
			$data = pack('vvvvv', $this->_firstRowIndex, $this->_lastRowIndex + 1, $this->_firstColumnIndex, $this->_lastColumnIndex + 1, 0);
		}
		else if ($this->_BIFF_version == 1536) {
			$length = 14;
			$data = pack('VVvvv', $this->_firstRowIndex, $this->_lastRowIndex + 1, $this->_firstColumnIndex, $this->_lastColumnIndex + 1, 0);
		}

		$header = pack('vv', $record, $length);
		$this->_append($header . $data);
	}

	private function _writeWindow2()
	{
		$record = 574;

		if ($this->_BIFF_version == 1280) {
			$length = 10;
		}
		else if ($this->_BIFF_version == 1536) {
			$length = 18;
		}

		$grbit = 182;
		$rwTop = 0;
		$colLeft = 0;
		$fDspFmla = 0;
		$fDspGrid = ($this->_phpSheet->getShowGridlines() ? 1 : 0);
		$fDspRwCol = ($this->_phpSheet->getShowRowColHeaders() ? 1 : 0);
		$fFrozen = ($this->_phpSheet->getFreezePane() ? 1 : 0);
		$fDspZeros = 1;
		$fDefaultHdr = 1;
		$fArabic = ($this->_phpSheet->getRightToLeft() ? 1 : 0);
		$fDspGuts = $this->_outline_on;
		$fFrozenNoSplit = 0;
		$fSelected = ($this->_phpSheet === $this->_phpSheet->getParent()->getActiveSheet() ? 1 : 0);
		$fPaged = 1;
		$grbit = $fDspFmla;
		$grbit |= $fDspGrid << 1;
		$grbit |= $fDspRwCol << 2;
		$grbit |= $fFrozen << 3;
		$grbit |= $fDspZeros << 4;
		$grbit |= $fDefaultHdr << 5;
		$grbit |= $fArabic << 6;
		$grbit |= $fDspGuts << 7;
		$grbit |= $fFrozenNoSplit << 8;
		$grbit |= $fSelected << 9;
		$grbit |= $fPaged << 10;
		$header = pack('vv', $record, $length);
		$data = pack('vvv', $grbit, $rwTop, $colLeft);

		if ($this->_BIFF_version == 1280) {
			$rgbHdr = 0;
			$data .= pack('V', $rgbHdr);
		}
		else if ($this->_BIFF_version == 1536) {
			$rgbHdr = 64;
			$zoom_factor_page_break = 0;
			$zoom_factor_normal = 0;
			$data .= pack('vvvvV', $rgbHdr, 0, $zoom_factor_page_break, $zoom_factor_normal, 0);
		}

		$this->_append($header . $data);
	}

	private function _writeDefaultRowHeight()
	{
		$defaultRowHeight = $this->_phpSheet->getDefaultRowDimension()->getRowHeight();

		if ($defaultRowHeight < 0) {
			return NULL;
		}

		$defaultRowHeight = (int) 20 * $defaultRowHeight;
		$record = 549;
		$length = 4;
		$header = pack('vv', $record, $length);
		$data = pack('vv', 1, $defaultRowHeight);
		$this->_append($header . $data);
	}

	private function _writeDefcol()
	{
		$defaultColWidth = 8;
		$record = 85;
		$length = 2;
		$header = pack('vv', $record, $length);
		$data = pack('v', $defaultColWidth);
		$this->_append($header . $data);
	}

	private function _writeColinfo($col_array)
	{
		if (isset($col_array[0])) {
			$colFirst = $col_array[0];
		}

		if (isset($col_array[1])) {
			$colLast = $col_array[1];
		}

		if (isset($col_array[2])) {
			$coldx = $col_array[2];
		}
		else {
			$coldx = 8.4299999999999997;
		}

		if (isset($col_array[3])) {
			$xfIndex = $col_array[3];
		}
		else {
			$xfIndex = 15;
		}

		if (isset($col_array[4])) {
			$grbit = $col_array[4];
		}
		else {
			$grbit = 0;
		}

		if (isset($col_array[5])) {
			$level = $col_array[5];
		}
		else {
			$level = 0;
		}

		$record = 125;
		$length = 12;
		$coldx *= 256;
		$ixfe = $xfIndex;
		$reserved = 0;
		$level = max(0, min($level, 7));
		$grbit |= $level << 8;
		$header = pack('vv', $record, $length);
		$data = pack('vvvvvv', $colFirst, $colLast, $coldx, $ixfe, $grbit, $reserved);
		$this->_append($header . $data);
	}

	private function _writeSelection()
	{
		$selectedCells = $this->_phpSheet->getSelectedCells();
		$selectedCells = PHPExcel_Cell::splitRange($this->_phpSheet->getSelectedCells());
		$selectedCells = $selectedCells[0];

		if (count($selectedCells) == 2) {
			list($first, $last) = $selectedCells;
		}
		else {
			$first = $selectedCells[0];
			$last = $selectedCells[0];
		}

		list($colFirst, $rwFirst) = PHPExcel_Cell::coordinateFromString($first);
		$colFirst = PHPExcel_Cell::columnIndexFromString($colFirst) - 1;
		--$rwFirst;
		list($colLast, $rwLast) = PHPExcel_Cell::coordinateFromString($last);
		$colLast = PHPExcel_Cell::columnIndexFromString($colLast) - 1;
		--$rwLast;
		$colFirst = min($colFirst, 255);
		$colLast = min($colLast, 255);

		if ($this->_BIFF_version == 1536) {
			$rwFirst = min($rwFirst, 65535);
			$rwLast = min($rwLast, 65535);
		}
		else {
			$rwFirst = min($rwFirst, 16383);
			$rwLast = min($rwLast, 16383);
		}

		$record = 29;
		$length = 15;
		$pnn = $this->_active_pane;
		$rwAct = $rwFirst;
		$colAct = $colFirst;
		$irefAct = 0;
		$cref = 1;

		if (!isset($rwLast)) {
			$rwLast = $rwFirst;
		}

		if (!isset($colLast)) {
			$colLast = $colFirst;
		}

		if ($rwLast < $rwFirst) {
			list($rwFirst, $rwLast) = array($rwLast, $rwFirst);
		}

		if ($colLast < $colFirst) {
			list($colFirst, $colLast) = array($colLast, $colFirst);
		}

		$header = pack('vv', $record, $length);
		$data = pack('CvvvvvvCC', $pnn, $rwAct, $colAct, $irefAct, $cref, $rwFirst, $rwLast, $colFirst, $colLast);
		$this->_append($header . $data);
	}

	private function _writeMergedCells()
	{
		$mergeCells = $this->_phpSheet->getMergeCells();
		$countMergeCells = count($mergeCells);

		if ($countMergeCells == 0) {
			return NULL;
		}

		if ($this->_BIFF_version == 1536) {
			$maxCountMergeCellsPerRecord = 1027;
		}
		else {
			$maxCountMergeCellsPerRecord = 259;
		}

		$record = 229;
		$i = 0;
		$j = 0;
		$recordData = '';

		foreach ($mergeCells as $mergeCell) {
			++$i;
			++$j;
			$range = PHPExcel_Cell::splitRange($mergeCell);
			list($first, $last) = $range[0];
			list($firstColumn, $firstRow) = PHPExcel_Cell::coordinateFromString($first);
			list($lastColumn, $lastRow) = PHPExcel_Cell::coordinateFromString($last);
			$recordData .= pack('vvvv', $firstRow - 1, $lastRow - 1, PHPExcel_Cell::columnIndexFromString($firstColumn) - 1, PHPExcel_Cell::columnIndexFromString($lastColumn) - 1);
			if (($j == $maxCountMergeCellsPerRecord) || ($i == $countMergeCells)) {
				$recordData = pack('v', $j) . $recordData;
				$length = strlen($recordData);
				$header = pack('vv', $record, $length);
				$this->_append($header . $recordData);
				$recordData = '';
				$j = 0;
			}
		}
	}

	private function _writeSheetLayout()
	{
		if (!$this->_phpSheet->isTabColorSet()) {
			return NULL;
		}

		$recordData = pack('vvVVVvv', 2146, 0, 0, 0, 20, $this->_colors[$this->_phpSheet->getTabColor()->getRGB()], 0);
		$length = strlen($recordData);
		$record = 2146;
		$header = pack('vv', $record, $length);
		$this->_append($header . $recordData);
	}

	private function _writeSheetProtection()
	{
		$record = 2151;
		$options = (int) !$this->_phpSheet->getProtection()->getObjects() | ((int) !$this->_phpSheet->getProtection()->getScenarios() << 1) | ((int) !$this->_phpSheet->getProtection()->getFormatCells() << 2) | ((int) !$this->_phpSheet->getProtection()->getFormatColumns() << 3) | ((int) !$this->_phpSheet->getProtection()->getFormatRows() << 4) | ((int) !$this->_phpSheet->getProtection()->getInsertColumns() << 5) | ((int) !$this->_phpSheet->getProtection()->getInsertRows() << 6) | ((int) !$this->_phpSheet->getProtection()->getInsertHyperlinks() << 7) | ((int) !$this->_phpSheet->getProtection()->getDeleteColumns() << 8) | ((int) !$this->_phpSheet->getProtection()->getDeleteRows() << 9) | ((int) !$this->_phpSheet->getProtection()->getSelectLockedCells() << 10) | ((int) !$this->_phpSheet->getProtection()->getSort() << 11) | ((int) !$this->_phpSheet->getProtection()->getAutoFilter() << 12) | ((int) !$this->_phpSheet->getProtection()->getPivotTables() << 13) | ((int) !$this->_phpSheet->getProtection()->getSelectUnlockedCells() << 14);
		$recordData = pack('vVVCVVvv', 2151, 0, 0, 0, 16777728, 4294967295, $options, 0);
		$length = strlen($recordData);
		$header = pack('vv', $record, $length);
		$this->_append($header . $recordData);
	}

	private function _writeRangeProtection()
	{
		foreach ($this->_phpSheet->getProtectedCells() as $range => $password) {
			$cellRanges = explode(' ', $range);
			$cref = count($cellRanges);
			$recordData = pack('vvVVvCVvVv', 2152, 0, 0, 0, 2, 0, 0, $cref, 0, 0);

			foreach ($cellRanges as $cellRange) {
				$recordData .= $this->_writeBIFF8CellRangeAddressFixed($cellRange);
			}

			$recordData .= pack('VV', 0, hexdec($password));
			$recordData .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong('p' . md5($recordData));
			$length = strlen($recordData);
			$record = 2152;
			$header = pack('vv', $record, $length);
			$this->_append($header . $recordData);
		}
	}

	private function _writeExterncount($count)
	{
		$record = 22;
		$length = 2;
		$header = pack('vv', $record, $length);
		$data = pack('v', $count);
		$this->_append($header . $data);
	}

	private function _writeExternsheet($sheetname)
	{
		$record = 23;

		if ($this->_phpSheet->getTitle() == $sheetname) {
			$sheetname = '';
			$length = 2;
			$cch = 1;
			$rgch = 2;
		}
		else {
			$length = 2 + strlen($sheetname);
			$cch = strlen($sheetname);
			$rgch = 3;
		}

		$header = pack('vv', $record, $length);
		$data = pack('CC', $cch, $rgch);
		$this->_append($header . $data . $sheetname);
	}

	private function _writePanes()
	{
		$panes = array();

		if ($freezePane = $this->_phpSheet->getFreezePane()) {
			list($column, $row) = PHPExcel_Cell::coordinateFromString($freezePane);
			$panes[0] = $row - 1;
			$panes[1] = PHPExcel_Cell::columnIndexFromString($column) - 1;
		}
		else {
			return NULL;
		}

		$y = (isset($panes[0]) ? $panes[0] : NULL);
		$x = (isset($panes[1]) ? $panes[1] : NULL);
		$rwTop = (isset($panes[2]) ? $panes[2] : NULL);
		$colLeft = (isset($panes[3]) ? $panes[3] : NULL);

		if (4 < count($panes)) {
			$pnnAct = $panes[4];
		}
		else {
			$pnnAct = NULL;
		}

		$record = 65;
		$length = 10;

		if ($this->_phpSheet->getFreezePane()) {
			if (!isset($rwTop)) {
				$rwTop = $y;
			}

			if (!isset($colLeft)) {
				$colLeft = $x;
			}
		}
		else {
			if (!isset($rwTop)) {
				$rwTop = 0;
			}

			if (!isset($colLeft)) {
				$colLeft = 0;
			}

			$y = (20 * $y) + 255;
			$x = (113.879 * $x) + 390;
		}

		if (!isset($pnnAct)) {
			if (($x != 0) && ($y != 0)) {
				$pnnAct = 0;
			}

			if (($x != 0) && ($y == 0)) {
				$pnnAct = 1;
			}

			if (($x == 0) && ($y != 0)) {
				$pnnAct = 2;
			}

			if (($x == 0) && ($y == 0)) {
				$pnnAct = 3;
			}
		}

		$this->_active_pane = $pnnAct;
		$header = pack('vv', $record, $length);
		$data = pack('vvvvv', $x, $y, $rwTop, $colLeft, $pnnAct);
		$this->_append($header . $data);
	}

	private function _writeSetup()
	{
		$record = 161;
		$length = 34;
		$iPaperSize = $this->_phpSheet->getPageSetup()->getPaperSize();
		$iScale = ($this->_phpSheet->getPageSetup()->getScale() ? $this->_phpSheet->getPageSetup()->getScale() : 100);
		$iPageStart = 1;
		$iFitWidth = (int) $this->_phpSheet->getPageSetup()->getFitToWidth();
		$iFitHeight = (int) $this->_phpSheet->getPageSetup()->getFitToHeight();
		$grbit = 0;
		$iRes = 600;
		$iVRes = 600;
		$numHdr = $this->_phpSheet->getPageMargins()->getHeader();
		$numFtr = $this->_phpSheet->getPageMargins()->getFooter();
		$iCopies = 1;
		$fLeftToRight = 0;
		$fLandscape = ($this->_phpSheet->getPageSetup()->getOrientation() == PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE ? 0 : 1);
		$fNoPls = 0;
		$fNoColor = 0;
		$fDraft = 0;
		$fNotes = 0;
		$fNoOrient = 0;
		$fUsePage = 0;
		$grbit = $fLeftToRight;
		$grbit |= $fLandscape << 1;
		$grbit |= $fNoPls << 2;
		$grbit |= $fNoColor << 3;
		$grbit |= $fDraft << 4;
		$grbit |= $fNotes << 5;
		$grbit |= $fNoOrient << 6;
		$grbit |= $fUsePage << 7;
		$numHdr = pack('d', $numHdr);
		$numFtr = pack('d', $numFtr);

		if (PHPExcel_Writer_Excel5_BIFFwriter::getByteOrder()) {
			$numHdr = strrev($numHdr);
			$numFtr = strrev($numFtr);
		}

		$header = pack('vv', $record, $length);
		$data1 = pack('vvvvvvvv', $iPaperSize, $iScale, $iPageStart, $iFitWidth, $iFitHeight, $grbit, $iRes, $iVRes);
		$data2 = $numHdr . $numFtr;
		$data3 = pack('v', $iCopies);
		$this->_append($header . $data1 . $data2 . $data3);
	}

	private function _writeHeader()
	{
		$record = 20;

		if ($this->_BIFF_version == 1536) {
			$recordData = PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($this->_phpSheet->getHeaderFooter()->getOddHeader());
			$length = strlen($recordData);
		}
		else {
			$cch = strlen($this->_phpSheet->getHeaderFooter()->getOddHeader());
			$length = 1 + $cch;
			$data = pack('C', $cch);
			$recordData = $data . $this->_phpSheet->getHeaderFooter()->getOddHeader();
		}

		$header = pack('vv', $record, $length);
		$this->_append($header . $recordData);
	}

	private function _writeFooter()
	{
		$record = 21;

		if ($this->_BIFF_version == 1536) {
			$recordData = PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($this->_phpSheet->getHeaderFooter()->getOddFooter());
			$length = strlen($recordData);
		}
		else {
			$cch = strlen($this->_phpSheet->getHeaderFooter()->getOddFooter());
			$length = 1 + $cch;
			$data = pack('C', $cch);
			$recordData = $data . $this->_phpSheet->getHeaderFooter()->getOddFooter();
		}

		$header = pack('vv', $record, $length);
		$this->_append($header . $recordData);
	}

	private function _writeHcenter()
	{
		$record = 131;
		$length = 2;
		$fHCenter = ($this->_phpSheet->getPageSetup()->getHorizontalCentered() ? 1 : 0);
		$header = pack('vv', $record, $length);
		$data = pack('v', $fHCenter);
		$this->_append($header . $data);
	}

	private function _writeVcenter()
	{
		$record = 132;
		$length = 2;
		$fVCenter = ($this->_phpSheet->getPageSetup()->getVerticalCentered() ? 1 : 0);
		$header = pack('vv', $record, $length);
		$data = pack('v', $fVCenter);
		$this->_append($header . $data);
	}

	private function _writeMarginLeft()
	{
		$record = 38;
		$length = 8;
		$margin = $this->_phpSheet->getPageMargins()->getLeft();
		$header = pack('vv', $record, $length);
		$data = pack('d', $margin);

		if (PHPExcel_Writer_Excel5_BIFFwriter::getByteOrder()) {
			$data = strrev($data);
		}

		$this->_append($header . $data);
	}

	private function _writeMarginRight()
	{
		$record = 39;
		$length = 8;
		$margin = $this->_phpSheet->getPageMargins()->getRight();
		$header = pack('vv', $record, $length);
		$data = pack('d', $margin);

		if (PHPExcel_Writer_Excel5_BIFFwriter::getByteOrder()) {
			$data = strrev($data);
		}

		$this->_append($header . $data);
	}

	private function _writeMarginTop()
	{
		$record = 40;
		$length = 8;
		$margin = $this->_phpSheet->getPageMargins()->getTop();
		$header = pack('vv', $record, $length);
		$data = pack('d', $margin);

		if (PHPExcel_Writer_Excel5_BIFFwriter::getByteOrder()) {
			$data = strrev($data);
		}

		$this->_append($header . $data);
	}

	private function _writeMarginBottom()
	{
		$record = 41;
		$length = 8;
		$margin = $this->_phpSheet->getPageMargins()->getBottom();
		$header = pack('vv', $record, $length);
		$data = pack('d', $margin);

		if (PHPExcel_Writer_Excel5_BIFFwriter::getByteOrder()) {
			$data = strrev($data);
		}

		$this->_append($header . $data);
	}

	private function _writePrintHeaders()
	{
		$record = 42;
		$length = 2;
		$fPrintRwCol = $this->_print_headers;
		$header = pack('vv', $record, $length);
		$data = pack('v', $fPrintRwCol);
		$this->_append($header . $data);
	}

	private function _writePrintGridlines()
	{
		$record = 43;
		$length = 2;
		$fPrintGrid = ($this->_phpSheet->getPrintGridlines() ? 1 : 0);
		$header = pack('vv', $record, $length);
		$data = pack('v', $fPrintGrid);
		$this->_append($header . $data);
	}

	private function _writeGridset()
	{
		$record = 130;
		$length = 2;
		$fGridSet = !$this->_phpSheet->getPrintGridlines();
		$header = pack('vv', $record, $length);
		$data = pack('v', $fGridSet);
		$this->_append($header . $data);
	}

	private function _writeGuts()
	{
		$record = 128;
		$length = 8;
		$dxRwGut = 0;
		$dxColGut = 0;
		$maxRowOutlineLevel = 0;

		foreach ($this->_phpSheet->getRowDimensions() as $rowDimension) {
			$maxRowOutlineLevel = max($maxRowOutlineLevel, $rowDimension->getOutlineLevel());
		}

		$col_level = 0;
		$colcount = count($this->_colinfo);

		for ($i = 0; $i < $colcount; ++$i) {
			$col_level = max($this->_colinfo[$i][5], $col_level);
		}

		$col_level = max(0, min($col_level, 7));

		if ($maxRowOutlineLevel) {
			++$maxRowOutlineLevel;
		}

		if ($col_level) {
			++$col_level;
		}

		$header = pack('vv', $record, $length);
		$data = pack('vvvv', $dxRwGut, $dxColGut, $maxRowOutlineLevel, $col_level);
		$this->_append($header . $data);
	}

	private function _writeWsbool()
	{
		$record = 129;
		$length = 2;
		$grbit = 0;
		$grbit |= 1;

		if ($this->_outline_style) {
			$grbit |= 32;
		}

		if ($this->_phpSheet->getShowSummaryBelow()) {
			$grbit |= 64;
		}

		if ($this->_phpSheet->getShowSummaryRight()) {
			$grbit |= 128;
		}

		if ($this->_phpSheet->getPageSetup()->getFitToPage()) {
			$grbit |= 256;
		}

		if ($this->_outline_on) {
			$grbit |= 1024;
		}

		$header = pack('vv', $record, $length);
		$data = pack('v', $grbit);
		$this->_append($header . $data);
	}

	private function _writeBreaks()
	{
		$vbreaks = array();
		$hbreaks = array();

		foreach ($this->_phpSheet->getBreaks() as $cell => $breakType) {
			$coordinates = PHPExcel_Cell::coordinateFromString($cell);

			switch ($breakType) {
			case PHPExcel_Worksheet::BREAK_COLUMN:
				$vbreaks[] = PHPExcel_Cell::columnIndexFromString($coordinates[0]) - 1;
				break;

			case PHPExcel_Worksheet::BREAK_ROW:
				$hbreaks[] = $coordinates[1];
				break;

			case PHPExcel_Worksheet::BREAK_NONE:
			default:
				break;
			}
		}

		if (0 < count($hbreaks)) {
			sort($hbreaks, SORT_NUMERIC);

			if ($hbreaks[0] == 0) {
				array_shift($hbreaks);
			}

			$record = 27;
			$cbrk = count($hbreaks);

			if ($this->_BIFF_version == 1536) {
				$length = 2 + (6 * $cbrk);
			}
			else {
				$length = 2 + (2 * $cbrk);
			}

			$header = pack('vv', $record, $length);
			$data = pack('v', $cbrk);

			foreach ($hbreaks as $hbreak) {
				if ($this->_BIFF_version == 1536) {
					$data .= pack('vvv', $hbreak, 0, 255);
				}
				else {
					$data .= pack('v', $hbreak);
				}
			}

			$this->_append($header . $data);
		}

		if (0 < count($vbreaks)) {
			$vbreaks = array_slice($vbreaks, 0, 1000);
			sort($vbreaks, SORT_NUMERIC);

			if ($vbreaks[0] == 0) {
				array_shift($vbreaks);
			}

			$record = 26;
			$cbrk = count($vbreaks);

			if ($this->_BIFF_version == 1536) {
				$length = 2 + (6 * $cbrk);
			}
			else {
				$length = 2 + (2 * $cbrk);
			}

			$header = pack('vv', $record, $length);
			$data = pack('v', $cbrk);

			foreach ($vbreaks as $vbreak) {
				if ($this->_BIFF_version == 1536) {
					$data .= pack('vvv', $vbreak, 0, 65535);
				}
				else {
					$data .= pack('v', $vbreak);
				}
			}

			$this->_append($header . $data);
		}
	}

	private function _writeProtect()
	{
		if (!$this->_phpSheet->getProtection()->getSheet()) {
			return NULL;
		}

		$record = 18;
		$length = 2;
		$fLock = 1;
		$header = pack('vv', $record, $length);
		$data = pack('v', $fLock);
		$this->_append($header . $data);
	}

	private function _writeScenProtect()
	{
		if (!$this->_phpSheet->getProtection()->getSheet()) {
			return NULL;
		}

		if (!$this->_phpSheet->getProtection()->getScenarios()) {
			return NULL;
		}

		$record = 221;
		$length = 2;
		$header = pack('vv', $record, $length);
		$data = pack('v', 1);
		$this->_append($header . $data);
	}

	private function _writeObjectProtect()
	{
		if (!$this->_phpSheet->getProtection()->getSheet()) {
			return NULL;
		}

		if (!$this->_phpSheet->getProtection()->getObjects()) {
			return NULL;
		}

		$record = 99;
		$length = 2;
		$header = pack('vv', $record, $length);
		$data = pack('v', 1);
		$this->_append($header . $data);
	}

	private function _writePassword()
	{
		if (!$this->_phpSheet->getProtection()->getSheet() || !$this->_phpSheet->getProtection()->getPassword()) {
			return NULL;
		}

		$record = 19;
		$length = 2;
		$wPassword = hexdec($this->_phpSheet->getProtection()->getPassword());
		$header = pack('vv', $record, $length);
		$data = pack('v', $wPassword);
		$this->_append($header . $data);
	}

	public function insertBitmap($row, $col, $bitmap, $x = 0, $y = 0, $scale_x = 1, $scale_y = 1)
	{
		$bitmap_array = (is_resource($bitmap) ? $this->_processBitmapGd($bitmap) : $this->_processBitmap($bitmap));
		list($width, $height, $size, $data) = $bitmap_array;
		$width *= $scale_x;
		$height *= $scale_y;
		$this->_positionImage($col, $row, $x, $y, $width, $height);
		$record = 127;
		$length = 8 + $size;
		$cf = 9;
		$env = 1;
		$lcb = $size;
		$header = pack('vvvvV', $record, $length, $cf, $env, $lcb);
		$this->_append($header . $data);
	}

	public function _positionImage($col_start, $row_start, $x1, $y1, $width, $height)
	{
		$col_end = $col_start;
		$row_end = $row_start;

		if (PHPExcel_Shared_Excel5::sizeCol($this->_phpSheet, PHPExcel_Cell::stringFromColumnIndex($col_start)) <= $x1) {
			$x1 = 0;
		}

		if (PHPExcel_Shared_Excel5::sizeRow($this->_phpSheet, $row_start + 1) <= $y1) {
			$y1 = 0;
		}

		$width = ($width + $x1) - 1;
		$height = ($height + $y1) - 1;

		while (PHPExcel_Shared_Excel5::sizeCol($this->_phpSheet, PHPExcel_Cell::stringFromColumnIndex($col_end)) <= $width) {
			$width -= PHPExcel_Shared_Excel5::sizeCol($this->_phpSheet, PHPExcel_Cell::stringFromColumnIndex($col_end));
			++$col_end;
		}

		while (PHPExcel_Shared_Excel5::sizeRow($this->_phpSheet, $row_end + 1) <= $height) {
			$height -= PHPExcel_Shared_Excel5::sizeRow($this->_phpSheet, $row_end + 1);
			++$row_end;
		}

		if (PHPExcel_Shared_Excel5::sizeCol($this->_phpSheet, PHPExcel_Cell::stringFromColumnIndex($col_start)) == 0) {
			return NULL;
		}

		if (PHPExcel_Shared_Excel5::sizeCol($this->_phpSheet, PHPExcel_Cell::stringFromColumnIndex($col_end)) == 0) {
			return NULL;
		}

		if (PHPExcel_Shared_Excel5::sizeRow($this->_phpSheet, $row_start + 1) == 0) {
			return NULL;
		}

		if (PHPExcel_Shared_Excel5::sizeRow($this->_phpSheet, $row_end + 1) == 0) {
			return NULL;
		}

		$x1 = ($x1 / PHPExcel_Shared_Excel5::sizeCol($this->_phpSheet, PHPExcel_Cell::stringFromColumnIndex($col_start))) * 1024;
		$y1 = ($y1 / PHPExcel_Shared_Excel5::sizeRow($this->_phpSheet, $row_start + 1)) * 256;
		$x2 = ($width / PHPExcel_Shared_Excel5::sizeCol($this->_phpSheet, PHPExcel_Cell::stringFromColumnIndex($col_end))) * 1024;
		$y2 = ($height / PHPExcel_Shared_Excel5::sizeRow($this->_phpSheet, $row_end + 1)) * 256;
		$this->_writeObjPicture($col_start, $x1, $row_start, $y1, $col_end, $x2, $row_end, $y2);
	}

	private function _writeObjPicture($colL, $dxL, $rwT, $dyT, $colR, $dxR, $rwB, $dyB)
	{
		$record = 93;
		$length = 60;
		$cObj = 1;
		$OT = 8;
		$id = 1;
		$grbit = 1556;
		$cbMacro = 0;
		$Reserved1 = 0;
		$Reserved2 = 0;
		$icvBack = 9;
		$icvFore = 9;
		$fls = 0;
		$fAuto = 0;
		$icv = 8;
		$lns = 255;
		$lnw = 1;
		$fAutoB = 0;
		$frs = 0;
		$cf = 9;
		$Reserved3 = 0;
		$cbPictFmla = 0;
		$Reserved4 = 0;
		$grbit2 = 1;
		$Reserved5 = 0;
		$header = pack('vv', $record, $length);
		$data = pack('V', $cObj);
		$data .= pack('v', $OT);
		$data .= pack('v', $id);
		$data .= pack('v', $grbit);
		$data .= pack('v', $colL);
		$data .= pack('v', $dxL);
		$data .= pack('v', $rwT);
		$data .= pack('v', $dyT);
		$data .= pack('v', $colR);
		$data .= pack('v', $dxR);
		$data .= pack('v', $rwB);
		$data .= pack('v', $dyB);
		$data .= pack('v', $cbMacro);
		$data .= pack('V', $Reserved1);
		$data .= pack('v', $Reserved2);
		$data .= pack('C', $icvBack);
		$data .= pack('C', $icvFore);
		$data .= pack('C', $fls);
		$data .= pack('C', $fAuto);
		$data .= pack('C', $icv);
		$data .= pack('C', $lns);
		$data .= pack('C', $lnw);
		$data .= pack('C', $fAutoB);
		$data .= pack('v', $frs);
		$data .= pack('V', $cf);
		$data .= pack('v', $Reserved3);
		$data .= pack('v', $cbPictFmla);
		$data .= pack('v', $Reserved4);
		$data .= pack('v', $grbit2);
		$data .= pack('V', $Reserved5);
		$this->_append($header . $data);
	}

	public function _processBitmapGd($image)
	{
		$width = imagesx($image);
		$height = imagesy($image);
		$data = pack('Vvvvv', 12, $width, $height, 1, 24);

		for ($j = $height; $j--; ) {
			for ($i = 0; $i < $width; ++$i) {
				$color = imagecolorsforindex($image, imagecolorat($image, $i, $j));

				foreach (array('red', 'green', 'blue') as $key) {
					$color[$key] = $color[$key] + round(((255 - $color[$key]) * $color['alpha']) / 127);
				}

				$data .= chr($color['blue']) . chr($color['green']) . chr($color['red']);
			}

			if ((3 * $width) % 4) {
				$data .= str_repeat("\x00", 4 - ((3 * $width) % 4));
			}
		}

		return array($width, $height, strlen($data), $data);
	}

	public function _processBitmap($bitmap)
	{
		$bmp_fd = @fopen($bitmap, 'rb');

		if (!$bmp_fd) {
			throw new Exception('Couldn\'t import ' . $bitmap);
		}

		$data = fread($bmp_fd, filesize($bitmap));

		if (strlen($data) <= 54) {
			throw new Exception($bitmap . " doesn't contain enough data.\n");
		}

		$identity = unpack('A2ident', $data);

		if ($identity['ident'] != 'BM') {
			throw new Exception($bitmap . " doesn't appear to be a valid bitmap image.\n");
		}

		$data = substr($data, 2);
		$size_array = unpack('Vsa', substr($data, 0, 4));
		$size = $size_array['sa'];
		$data = substr($data, 4);
		$size -= 54;
		$size += 12;
		$data = substr($data, 12);
		$width_and_height = unpack('V2', substr($data, 0, 8));
		$width = $width_and_height[1];
		$height = $width_and_height[2];
		$data = substr($data, 8);

		if (65535 < $width) {
			throw new Exception($bitmap . ": largest image width supported is 65k.\n");
		}

		if (65535 < $height) {
			throw new Exception($bitmap . ": largest image height supported is 65k.\n");
		}

		$planes_and_bitcount = unpack('v2', substr($data, 0, 4));
		$data = substr($data, 4);

		if ($planes_and_bitcount[2] != 24) {
			throw new Exception($bitmap . " isn't a 24bit true color bitmap.\n");
		}

		if ($planes_and_bitcount[1] != 1) {
			throw new Exception($bitmap . ": only 1 plane supported in bitmap image.\n");
		}

		$compression = unpack('Vcomp', substr($data, 0, 4));
		$data = substr($data, 4);

		if ($compression['comp'] != 0) {
			throw new Exception($bitmap . ": compression not supported in bitmap image.\n");
		}

		$data = substr($data, 20);
		$header = pack('Vvvvv', 12, $width, $height, 1, 24);
		$data = $header . $data;
		return array($width, $height, $size, $data);
	}

	private function _writeZoom()
	{
		if ($this->_phpSheet->getSheetView()->getZoomScale() == 100) {
			return NULL;
		}

		$record = 160;
		$length = 4;
		$header = pack('vv', $record, $length);
		$data = pack('vv', $this->_phpSheet->getSheetView()->getZoomScale(), 100);
		$this->_append($header . $data);
	}

	private function _writeMsoDrawing()
	{
		if (count($this->_phpSheet->getDrawingCollection()) == 0) {
			return NULL;
		}

		$escher = new PHPExcel_Shared_Escher();
		$dgContainer = new PHPExcel_Shared_Escher_DgContainer();
		$dgContainer->setDgId($this->_phpSheet->getParent()->getIndex($this->_phpSheet) + 1);
		$escher->setDgContainer($dgContainer);
		$spgrContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer();
		$dgContainer->setSpgrContainer($spgrContainer);
		$spContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer();
		$spContainer->setSpgr(true);
		$spContainer->setSpType(0);
		$spContainer->setSpId(($this->_phpSheet->getParent()->getIndex($this->_phpSheet) + 1) << 10);
		$spgrContainer->addChild($spContainer);
		$blipIndex = 0;
		$countShapes = 0;

		foreach ($this->_phpSheet->getParent()->getAllsheets() as $sheet) {
			foreach ($sheet->getDrawingCollection() as $drawing) {
				++$blipIndex;

				if ($sheet === $this->_phpSheet) {
					++$countShapes;
					$spContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer();
					$spContainer->setSpType(75);
					$spId = $countShapes | (($this->_phpSheet->getParent()->getIndex($this->_phpSheet) + 1) << 10);
					$spContainer->setSpId($spId);
					$lastSpId = $spId;
					$spContainer->setOPT(16644, $blipIndex);
					$coordinates = $drawing->getCoordinates();
					$offsetX = $drawing->getOffsetX();
					$offsetY = $drawing->getOffsetY();
					$width = $drawing->getWidth();
					$height = $drawing->getHeight();
					$twoAnchor = PHPExcel_Shared_Excel5::oneAnchor2twoAnchor($this->_phpSheet, $coordinates, $offsetX, $offsetY, $width, $height);
					$spContainer->setStartCoordinates($twoAnchor['startCoordinates']);
					$spContainer->setStartOffsetX($twoAnchor['startOffsetX']);
					$spContainer->setStartOffsetY($twoAnchor['startOffsetY']);
					$spContainer->setEndCoordinates($twoAnchor['endCoordinates']);
					$spContainer->setEndOffsetX($twoAnchor['endOffsetX']);
					$spContainer->setEndOffsetY($twoAnchor['endOffsetY']);
					$spgrContainer->addChild($spContainer);
				}
			}
		}

		$dgContainer->setLastSpId($lastSpId);
		$writer = new PHPExcel_Writer_Excel5_Escher($escher);
		$data = $writer->close();
		$spOffsets = $writer->getSpOffsets();
		$spOffsets[0] = 0;
		$nm = count($spOffsets) - 1;

		for ($i = 1; $i <= $nm; ++$i) {
			$record = 236;
			$dataChunk = substr($data, $spOffsets[$i - 1], $spOffsets[$i] - $spOffsets[$i - 1]);
			$length = strlen($dataChunk);
			$header = pack('vv', $record, $length);
			$this->_append($header . $dataChunk);
			$record = 93;
			$objData = '';
			$objData .= pack('vvvvvVVV', 21, 18, 8, $i, 24593, 0, 0, 0);
			$objData .= pack('vv', 0, 0);
			$length = strlen($objData);
			$header = pack('vv', $record, $length);
			$this->_append($header . $objData);
		}
	}

	private function _writeDataValidity()
	{
		$dataValidationCollection = $this->_phpSheet->getDataValidationCollection();

		if (0 < count($dataValidationCollection)) {
			$record = 434;
			$length = 18;
			$grbit = 0;
			$horPos = 0;
			$verPos = 0;
			$objId = 4294967295;
			$header = pack('vv', $record, $length);
			$data = pack('vVVVV', $grbit, $horPos, $verPos, $objId, count($dataValidationCollection));
			$this->_append($header . $data);
			$record = 446;

			foreach ($dataValidationCollection as $cellCoordinate => $dataValidation) {
				$data = '';
				$options = 0;
				$type = $dataValidation->getType();

				switch ($type) {
				case PHPExcel_Cell_DataValidation::TYPE_NONE:
					$type = 0;
					break;

				case PHPExcel_Cell_DataValidation::TYPE_WHOLE:
					$type = 1;
					break;

				case PHPExcel_Cell_DataValidation::TYPE_DECIMAL:
					$type = 2;
					break;

				case PHPExcel_Cell_DataValidation::TYPE_LIST:
					$type = 3;
					break;

				case PHPExcel_Cell_DataValidation::TYPE_DATE:
					$type = 4;
					break;

				case PHPExcel_Cell_DataValidation::TYPE_TIME:
					$type = 5;
					break;

				case PHPExcel_Cell_DataValidation::TYPE_TEXTLENGTH:
					$type = 6;
					break;

				case PHPExcel_Cell_DataValidation::TYPE_CUSTOM:
					$type = 7;
					break;
				}

				$options |= $type << 0;
				$errorStyle = $dataValidation->getType();

				switch ($errorStyle) {
				case PHPExcel_Cell_DataValidation::STYLE_STOP:
					$errorStyle = 0;
					break;

				case PHPExcel_Cell_DataValidation::STYLE_WARNING:
					$errorStyle = 1;
					break;

				case PHPExcel_Cell_DataValidation::STYLE_INFORMATION:
					$errorStyle = 2;
					break;
				}

				$options |= $errorStyle << 4;
				if (($type == 3) && preg_match('/^\\".*\\"$/', $dataValidation->getFormula1())) {
					$options |= 1 << 7;
				}

				$options |= $dataValidation->getAllowBlank() << 8;
				$options |= !$dataValidation->getShowDropDown() << 9;
				$options |= $dataValidation->getShowInputMessage() << 18;
				$options |= $dataValidation->getShowErrorMessage() << 19;
				$operator = $dataValidation->getOperator();

				switch ($operator) {
				case PHPExcel_Cell_DataValidation::OPERATOR_BETWEEN:
					$operator = 0;
					break;

				case PHPExcel_Cell_DataValidation::OPERATOR_NOTBETWEEN:
					$operator = 1;
					break;

				case PHPExcel_Cell_DataValidation::OPERATOR_EQUAL:
					$operator = 2;
					break;

				case PHPExcel_Cell_DataValidation::OPERATOR_NOTEQUAL:
					$operator = 3;
					break;

				case PHPExcel_Cell_DataValidation::OPERATOR_GREATERTHAN:
					$operator = 4;
					break;

				case PHPExcel_Cell_DataValidation::OPERATOR_LESSTHAN:
					$operator = 5;
					break;

				case PHPExcel_Cell_DataValidation::OPERATOR_GREATERTHANOREQUAL:
					$operator = 6;
					break;

				case PHPExcel_Cell_DataValidation::OPERATOR_LESSTHANOREQUAL:
					$operator = 7;
					break;
				}

				$options |= $operator << 20;
				$data = pack('V', $options);
				$promptTitle = ($dataValidation->getPromptTitle() !== '' ? $dataValidation->getPromptTitle() : chr(0));
				$data .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($promptTitle);
				$errorTitle = ($dataValidation->getErrorTitle() !== '' ? $dataValidation->getErrorTitle() : chr(0));
				$data .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($errorTitle);
				$prompt = ($dataValidation->getPrompt() !== '' ? $dataValidation->getPrompt() : chr(0));
				$data .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($prompt);
				$error = ($dataValidation->getError() !== '' ? $dataValidation->getError() : chr(0));
				$data .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($error);

				try {
					$formula1 = $dataValidation->getFormula1();

					if ($type == 3) {
						$formula1 = str_replace(',', chr(0), $formula1);
					}

					$this->_parser->parse($formula1);
					$formula1 = $this->_parser->toReversePolish();
					$sz1 = strlen($formula1);
				}
				catch (Exception $e) {
					$sz1 = 0;
					$formula1 = '';
				}

				$data .= pack('vv', $sz1, 0);
				$data .= $formula1;

				try {
					$formula2 = $dataValidation->getFormula2();

					if ($formula2 === '') {
						throw new Exception('No formula2');
					}

					$this->_parser->parse($formula2);
					$formula2 = $this->_parser->toReversePolish();
					$sz2 = strlen($formula2);
				}
				catch (Exception $e) {
					$sz2 = 0;
					$formula2 = '';
				}

				$data .= pack('vv', $sz2, 0);
				$data .= $formula2;
				$data .= pack('v', 1);
				$data .= $this->_writeBIFF8CellRangeAddressFixed($cellCoordinate);
				$length = strlen($data);
				$header = pack('vv', $record, $length);
				$this->_append($header . $data);
			}
		}
	}

	private function _mapErrorCode($errorCode)
	{
		switch ($errorCode) {
		case '#NULL!':
			return 0;
		case '#DIV/0!':
			return 7;
		case '#VALUE!':
			return 15;
		case '#REF!':
			return 23;
		case '#NAME?':
			return 29;
		case '#NUM!':
			return 36;
		case '#N/A':
			return 42;
		}

		return 0;
	}
}

?>

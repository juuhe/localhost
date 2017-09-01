<?php

class PHPExcel_Writer_Excel5_Xf
{
	/**
	 * BIFF version
	 *
	 * @var int
	 */
	private $_BIFFVersion;
	/**
	 * Style XF or a cell XF ?
	 *
	 * @var boolean
	 */
	private $_isStyleXf;
	/**
	 * Index to the FONT record. Index 4 does not exist
	 * @var integer
	 */
	private $_fontIndex;
	/**
	 * An index (2 bytes) to a FORMAT record (number format).
	 * @var integer
	 */
	public $_numberFormatIndex;
	/**
	 * 1 bit, apparently not used.
	 * @var integer
	 */
	public $_text_justlast;
	/**
	 * The cell's foreground color.
	 * @var integer
	 */
	public $_fg_color;
	/**
	 * The cell's background color.
	 * @var integer
	 */
	public $_bg_color;
	/**
	 * Color of the bottom border of the cell.
	 * @var integer
	 */
	public $_bottom_color;
	/**
	 * Color of the top border of the cell.
	 * @var integer
	 */
	public $_top_color;
	/**
	* Color of the left border of the cell.
	* @var integer
	*/
	public $_left_color;
	/**
	 * Color of the right border of the cell.
	 * @var integer
	 */
	public $_right_color;

	public function __construct(PHPExcel_Style $style = NULL)
	{
		$this->_isStyleXf = false;
		$this->_BIFFVersion = 1536;
		$this->_fontIndex = 0;
		$this->_numberFormatIndex = 0;
		$this->_text_justlast = 0;
		$this->_fg_color = 64;
		$this->_bg_color = 65;
		$this->_diag = 0;
		$this->_bottom_color = 64;
		$this->_top_color = 64;
		$this->_left_color = 64;
		$this->_right_color = 64;
		$this->_diag_color = 64;
		$this->_style = $style;
	}

	public function writeXf()
	{
		if ($this->_isStyleXf) {
			$style = 65525;
		}
		else {
			$style = $this->_mapLocked($this->_style->getProtection()->getLocked());
			$style |= $this->_mapHidden($this->_style->getProtection()->getHidden()) << 1;
		}

		$atr_num = ($this->_numberFormatIndex != 0 ? 1 : 0);
		$atr_fnt = ($this->_fontIndex != 0 ? 1 : 0);
		$atr_alc = ((int) $this->_style->getAlignment()->getWrapText() ? 1 : 0);
		$atr_bdr = ($this->_mapBorderStyle($this->_style->getBorders()->getBottom()->getBorderStyle()) || $this->_mapBorderStyle($this->_style->getBorders()->getTop()->getBorderStyle()) || $this->_mapBorderStyle($this->_style->getBorders()->getLeft()->getBorderStyle()) || $this->_mapBorderStyle($this->_style->getBorders()->getRight()->getBorderStyle()) ? 1 : 0);
		$atr_pat = (($this->_fg_color != 64) || ($this->_bg_color != 65) || $this->_mapFillType($this->_style->getFill()->getFillType()) ? 1 : 0);
		$atr_prot = $this->_mapLocked($this->_style->getProtection()->getLocked()) | $this->_mapHidden($this->_style->getProtection()->getHidden());

		if ($this->_mapBorderStyle($this->_style->getBorders()->getBottom()->getBorderStyle()) == 0) {
			$this->_bottom_color = 0;
		}

		if ($this->_mapBorderStyle($this->_style->getBorders()->getTop()->getBorderStyle()) == 0) {
			$this->_top_color = 0;
		}

		if ($this->_mapBorderStyle($this->_style->getBorders()->getRight()->getBorderStyle()) == 0) {
			$this->_right_color = 0;
		}

		if ($this->_mapBorderStyle($this->_style->getBorders()->getLeft()->getBorderStyle()) == 0) {
			$this->_left_color = 0;
		}

		if ($this->_mapBorderStyle($this->_style->getBorders()->getDiagonal()->getBorderStyle()) == 0) {
			$this->_diag_color = 0;
		}

		$record = 224;

		if ($this->_BIFFVersion == 1280) {
			$length = 16;
		}

		if ($this->_BIFFVersion == 1536) {
			$length = 20;
		}

		$ifnt = $this->_fontIndex;
		$ifmt = $this->_numberFormatIndex;

		if ($this->_BIFFVersion == 1280) {
			$align = $this->_mapHAlign($this->_style->getAlignment()->getHorizontal());
			$align |= (int) $this->_style->getAlignment()->getWrapText() << 3;
			$align |= $this->_mapVAlign($this->_style->getAlignment()->getVertical()) << 4;
			$align |= $this->_text_justlast << 7;
			$align |= 0 << 8;
			$align |= $atr_num << 10;
			$align |= $atr_fnt << 11;
			$align |= $atr_alc << 12;
			$align |= $atr_bdr << 13;
			$align |= $atr_pat << 14;
			$align |= $atr_prot << 15;
			$icv = $this->_fg_color;
			$icv |= $this->_bg_color << 7;
			$fill = $this->_mapFillType($this->_style->getFill()->getFillType());
			$fill |= $this->_mapBorderStyle($this->_style->getBorders()->getBottom()->getBorderStyle()) << 6;
			$fill |= $this->_bottom_color << 9;
			$border1 = $this->_mapBorderStyle($this->_style->getBorders()->getTop()->getBorderStyle());
			$border1 |= $this->_mapBorderStyle($this->_style->getBorders()->getLeft()->getBorderStyle()) << 3;
			$border1 |= $this->_mapBorderStyle($this->_style->getBorders()->getRight()->getBorderStyle()) << 6;
			$border1 |= $this->_top_color << 9;
			$border2 = $this->_left_color;
			$border2 |= $this->_right_color << 7;
			$header = pack('vv', $record, $length);
			$data = pack('vvvvvvvv', $ifnt, $ifmt, $style, $align, $icv, $fill, $border1, $border2);
		}
		else if ($this->_BIFFVersion == 1536) {
			$align = $this->_mapHAlign($this->_style->getAlignment()->getHorizontal());
			$align |= (int) $this->_style->getAlignment()->getWrapText() << 3;
			$align |= $this->_mapVAlign($this->_style->getAlignment()->getVertical()) << 4;
			$align |= $this->_text_justlast << 7;
			$used_attrib = $atr_num << 2;
			$used_attrib |= $atr_fnt << 3;
			$used_attrib |= $atr_alc << 4;
			$used_attrib |= $atr_bdr << 5;
			$used_attrib |= $atr_pat << 6;
			$used_attrib |= $atr_prot << 7;
			$icv = $this->_fg_color;
			$icv |= $this->_bg_color << 7;
			$border1 = $this->_mapBorderStyle($this->_style->getBorders()->getLeft()->getBorderStyle());
			$border1 |= $this->_mapBorderStyle($this->_style->getBorders()->getRight()->getBorderStyle()) << 4;
			$border1 |= $this->_mapBorderStyle($this->_style->getBorders()->getTop()->getBorderStyle()) << 8;
			$border1 |= $this->_mapBorderStyle($this->_style->getBorders()->getBottom()->getBorderStyle()) << 12;
			$border1 |= $this->_left_color << 16;
			$border1 |= $this->_right_color << 23;
			$diagonalDirection = $this->_style->getBorders()->getDiagonalDirection();
			$diag_tl_to_rb = ($diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_BOTH) || ($diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_DOWN);
			$diag_tr_to_lb = ($diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_BOTH) || ($diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_UP);
			$border1 |= $diag_tl_to_rb << 30;
			$border1 |= $diag_tr_to_lb << 31;
			$border2 = $this->_top_color;
			$border2 |= $this->_bottom_color << 7;
			$border2 |= $this->_diag_color << 14;
			$border2 |= $this->_mapBorderStyle($this->_style->getBorders()->getDiagonal()->getBorderStyle()) << 21;
			$border2 |= $this->_mapFillType($this->_style->getFill()->getFillType()) << 26;
			$header = pack('vv', $record, $length);
			$biff8_options = $this->_style->getAlignment()->getIndent();
			$biff8_options |= (int) $this->_style->getAlignment()->getShrinkToFit() << 4;
			$data = pack('vvvC', $ifnt, $ifmt, $style, $align);
			$data .= pack('CCC', $this->_mapTextRotation($this->_style->getAlignment()->getTextRotation()), $biff8_options, $used_attrib);
			$data .= pack('VVv', $border1, $border2, $icv);
		}

		return $header . $data;
	}

	public function setBIFFVersion($BIFFVersion)
	{
		$this->_BIFFVersion = $BIFFVersion;
	}

	public function setIsStyleXf($value)
	{
		$this->_isStyleXf = $value;
	}

	public function setBottomColor($colorIndex)
	{
		$this->_bottom_color = $colorIndex;
	}

	public function setTopColor($colorIndex)
	{
		$this->_top_color = $colorIndex;
	}

	public function setLeftColor($colorIndex)
	{
		$this->_left_color = $colorIndex;
	}

	public function setRightColor($colorIndex)
	{
		$this->_right_color = $colorIndex;
	}

	public function setDiagColor($colorIndex)
	{
		$this->_diag_color = $colorIndex;
	}

	public function setFgColor($colorIndex)
	{
		$this->_fg_color = $colorIndex;
	}

	public function setBgColor($colorIndex)
	{
		$this->_bg_color = $colorIndex;
	}

	public function setNumberFormatIndex($numberFormatIndex)
	{
		$this->_numberFormatIndex = $numberFormatIndex;
	}

	public function setFontIndex($value)
	{
		$this->_fontIndex = $value;
	}

	private function _mapBorderStyle($borderStyle)
	{
		switch ($borderStyle) {
		case PHPExcel_Style_Border::BORDER_NONE:
			return 0;
		case PHPExcel_Style_Border::BORDER_THIN:
			return 1;
		case PHPExcel_Style_Border::BORDER_MEDIUM:
			return 2;
		case PHPExcel_Style_Border::BORDER_DASHED:
			return 3;
		case PHPExcel_Style_Border::BORDER_DOTTED:
			return 4;
		case PHPExcel_Style_Border::BORDER_THICK:
			return 5;
		case PHPExcel_Style_Border::BORDER_DOUBLE:
			return 6;
		case PHPExcel_Style_Border::BORDER_HAIR:
			return 7;
		case PHPExcel_Style_Border::BORDER_MEDIUMDASHED:
			return 8;
		case PHPExcel_Style_Border::BORDER_DASHDOT:
			return 9;
		case PHPExcel_Style_Border::BORDER_MEDIUMDASHDOT:
			return 10;
		case PHPExcel_Style_Border::BORDER_DASHDOTDOT:
			return 11;
		case PHPExcel_Style_Border::BORDER_MEDIUMDASHDOTDOT:
			return 12;
		case PHPExcel_Style_Border::BORDER_SLANTDASHDOT:
			return 13;
		default:
			return 0;
		}
	}

	private function _mapFillType($fillType)
	{
		switch ($fillType) {
		case PHPExcel_Style_Fill::FILL_NONE:
			return 0;
		case PHPExcel_Style_Fill::FILL_SOLID:
			return 1;
		case PHPExcel_Style_Fill::FILL_PATTERN_MEDIUMGRAY:
			return 2;
		case PHPExcel_Style_Fill::FILL_PATTERN_DARKGRAY:
			return 3;
		case PHPExcel_Style_Fill::FILL_PATTERN_LIGHTGRAY:
			return 4;
		case PHPExcel_Style_Fill::FILL_PATTERN_DARKHORIZONTAL:
			return 5;
		case PHPExcel_Style_Fill::FILL_PATTERN_DARKVERTICAL:
			return 6;
		case PHPExcel_Style_Fill::FILL_PATTERN_DARKDOWN:
			return 7;
		case PHPExcel_Style_Fill::FILL_PATTERN_DARKUP:
			return 8;
		case PHPExcel_Style_Fill::FILL_PATTERN_DARKGRID:
			return 9;
		case PHPExcel_Style_Fill::FILL_PATTERN_DARKTRELLIS:
			return 10;
		case PHPExcel_Style_Fill::FILL_PATTERN_LIGHTHORIZONTAL:
			return 11;
		case PHPExcel_Style_Fill::FILL_PATTERN_LIGHTVERTICAL:
			return 12;
		case PHPExcel_Style_Fill::FILL_PATTERN_LIGHTDOWN:
			return 13;
		case PHPExcel_Style_Fill::FILL_PATTERN_LIGHTUP:
			return 14;
		case PHPExcel_Style_Fill::FILL_PATTERN_LIGHTGRID:
			return 15;
		case PHPExcel_Style_Fill::FILL_PATTERN_LIGHTTRELLIS:
			return 16;
		case PHPExcel_Style_Fill::FILL_PATTERN_GRAY125:
			return 17;
		case PHPExcel_Style_Fill::FILL_PATTERN_GRAY0625:
			return 18;
		case PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR:
		case PHPExcel_Style_Fill::FILL_GRADIENT_PATH:
		default:
			return 0;
		}
	}

	private function _mapHAlign($hAlign)
	{
		switch ($hAlign) {
		case PHPExcel_Style_Alignment::HORIZONTAL_GENERAL:
			return 0;
		case PHPExcel_Style_Alignment::HORIZONTAL_LEFT:
			return 1;
		case PHPExcel_Style_Alignment::HORIZONTAL_CENTER:
			return 2;
		case PHPExcel_Style_Alignment::HORIZONTAL_RIGHT:
			return 3;
		case PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY:
			return 5;
		case PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS:
			return 6;
		default:
			return 0;
		}
	}

	private function _mapVAlign($vAlign)
	{
		switch ($vAlign) {
		case PHPExcel_Style_Alignment::VERTICAL_TOP:
			return 0;
		case PHPExcel_Style_Alignment::VERTICAL_CENTER:
			return 1;
		case PHPExcel_Style_Alignment::VERTICAL_BOTTOM:
			return 2;
		case PHPExcel_Style_Alignment::VERTICAL_JUSTIFY:
			return 3;
		default:
			return 2;
		}
	}

	private function _mapTextRotation($textRotation)
	{
		if (0 <= $textRotation) {
			return $textRotation;
		}

		if ($textRotation == -165) {
			return 255;
		}

		if ($textRotation < 0) {
			return 90 - $textRotation;
		}
	}

	private function _mapLocked($locked)
	{
		switch ($locked) {
		case PHPExcel_Style_Protection::PROTECTION_INHERIT:
			return 1;
		case PHPExcel_Style_Protection::PROTECTION_PROTECTED:
			return 1;
		case PHPExcel_Style_Protection::PROTECTION_UNPROTECTED:
			return 0;
		default:
			return 1;
		}
	}

	private function _mapHidden($hidden)
	{
		switch ($hidden) {
		case PHPExcel_Style_Protection::PROTECTION_INHERIT:
			return 0;
		case PHPExcel_Style_Protection::PROTECTION_PROTECTED:
			return 1;
		case PHPExcel_Style_Protection::PROTECTION_UNPROTECTED:
			return 0;
		default:
			return 0;
		}
	}
}


?>

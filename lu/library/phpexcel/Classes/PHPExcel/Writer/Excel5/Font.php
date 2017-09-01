<?php

class PHPExcel_Writer_Excel5_Font
{
	/**
	 * BIFF version
	 *
	 * @var int
	 */
	private $_BIFFVersion;
	/**
	 * Color index
	 *
	 * @var int
	 */
	private $_colorIndex;
	/**
	 * Font
	 *
	 * @var PHPExcel_Style_Font
	 */
	private $_font;

	public function __construct(PHPExcel_Style_Font $font = NULL)
	{
		$this->_BIFFVersion = 1536;
		$this->_colorIndex = 32767;
		$this->_font = $font;
	}

	public function setColorIndex($colorIndex)
	{
		$this->_colorIndex = $colorIndex;
	}

	public function writeFont()
	{
		$font_outline = 0;
		$font_shadow = 0;
		$icv = $this->_colorIndex;

		if ($this->_font->getSuperScript()) {
			$sss = 1;
		}
		else if ($this->_font->getSubScript()) {
			$sss = 2;
		}
		else {
			$sss = 0;
		}

		$bFamily = 0;
		$bCharSet = PHPExcel_Shared_Font::getCharsetFromFontName($this->_font->getName());
		$record = 49;
		$reserved = 0;
		$grbit = 0;

		if ($this->_font->getItalic()) {
			$grbit |= 2;
		}

		if ($this->_font->getStrikethrough()) {
			$grbit |= 8;
		}

		if ($font_outline) {
			$grbit |= 16;
		}

		if ($font_shadow) {
			$grbit |= 32;
		}

		if ($this->_BIFFVersion == 1280) {
			$data = pack('vvvvvCCCCC', $this->_font->getSize() * 20, $grbit, $icv, $this->_mapBold($this->_font->getBold()), $sss, $this->_mapUnderline($this->_font->getUnderline()), $bFamily, $bCharSet, $reserved, strlen($this->_font->getName()));
			$data .= $this->_font->getName();
		}
		else if ($this->_BIFFVersion == 1536) {
			$data = pack('vvvvvCCCC', $this->_font->getSize() * 20, $grbit, $icv, $this->_mapBold($this->_font->getBold()), $sss, $this->_mapUnderline($this->_font->getUnderline()), $bFamily, $bCharSet, $reserved);
			$data .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeShort($this->_font->getName());
		}

		$length = strlen($data);
		$header = pack('vv', $record, $length);
		return $header . $data;
	}

	public function setBIFFVersion($BIFFVersion)
	{
		$this->_BIFFVersion = $BIFFVersion;
	}

	private function _mapBold($bold)
	{
		if ($bold) {
			return 700;
		}

		return 400;
	}

	private function _mapUnderline($underline)
	{
		switch ($underline) {
		case PHPExcel_Style_Font::UNDERLINE_NONE:
			return 0;
		case PHPExcel_Style_Font::UNDERLINE_SINGLE:
			return 1;
		case PHPExcel_Style_Font::UNDERLINE_DOUBLE:
			return 2;
		case PHPExcel_Style_Font::UNDERLINE_SINGLEACCOUNTING:
			return 33;
		case PHPExcel_Style_Font::UNDERLINE_DOUBLEACCOUNTING:
			return 34;
		default:
			return 0;
		}
	}
}


?>

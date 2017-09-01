<?php

class PHPExcel_Style_Font implements PHPExcel_IComparable
{
	const UNDERLINE_NONE = 'none';
	const UNDERLINE_DOUBLE = 'double';
	const UNDERLINE_DOUBLEACCOUNTING = 'doubleAccounting';
	const UNDERLINE_SINGLE = 'single';
	const UNDERLINE_SINGLEACCOUNTING = 'singleAccounting';

	/**
	 * Name
	 *
	 * @var string
	 */
	private $_name;
	/**
	 * Bold
	 *
	 * @var boolean
	 */
	private $_bold;
	/**
	 * Italic
	 *
	 * @var boolean
	 */
	private $_italic;
	/**
	 * Superscript
	 *
	 * @var boolean
	 */
	private $_superScript;
	/**
	 * Subscript
	 *
	 * @var boolean
	 */
	private $_subScript;
	/**
	 * Underline
	 *
	 * @var string
	 */
	private $_underline;
	/**
	 * Strikethrough
	 *
	 * @var boolean
	 */
	private $_strikethrough;
	/**
	 * Foreground color
	 *
	 * @var PHPExcel_Style_Color
	 */
	private $_color;
	/**
	 * Parent Borders
	 *
	 * @var _parentPropertyName string
	 */
	private $_parentPropertyName;
	/**
	 * Supervisor?
	 *
	 * @var boolean
	 */
	private $_isSupervisor;
	/**
	 * Parent. Only used for supervisor
	 *
	 * @var PHPExcel_Style
	 */
	private $_parent;

	public function __construct($isSupervisor = false)
	{
		$this->_isSupervisor = $isSupervisor;
		$this->_name = 'Calibri';
		$this->_size = 11;
		$this->_bold = false;
		$this->_italic = false;
		$this->_superScript = false;
		$this->_subScript = false;
		$this->_underline = PHPExcel_Style_Font::UNDERLINE_NONE;
		$this->_strikethrough = false;
		$this->_color = new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK, $isSupervisor);

		if ($isSupervisor) {
			$this->_color->bindParent($this, '_color');
		}
	}

	public function bindParent($parent)
	{
		$this->_parent = $parent;
	}

	public function getIsSupervisor()
	{
		return $this->_isSupervisor;
	}

	public function getSharedComponent()
	{
		return $this->_parent->getSharedComponent()->getFont();
	}

	public function getActiveSheet()
	{
		return $this->_parent->getActiveSheet();
	}

	public function getSelectedCells()
	{
		return $this->getActiveSheet()->getSelectedCells();
	}

	public function getActiveCell()
	{
		return $this->getActiveSheet()->getActiveCell();
	}

	public function getStyleArray($array)
	{
		return array('font' => $array);
	}

	public function applyFromArray($pStyles = NULL)
	{
		if (is_array($pStyles)) {
			if ($this->_isSupervisor) {
				$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($this->getStyleArray($pStyles));
			}
			else {
				if (array_key_exists('name', $pStyles)) {
					$this->setName($pStyles['name']);
				}

				if (array_key_exists('bold', $pStyles)) {
					$this->setBold($pStyles['bold']);
				}

				if (array_key_exists('italic', $pStyles)) {
					$this->setItalic($pStyles['italic']);
				}

				if (array_key_exists('superScript', $pStyles)) {
					$this->setSuperScript($pStyles['superScript']);
				}

				if (array_key_exists('subScript', $pStyles)) {
					$this->setSubScript($pStyles['subScript']);
				}

				if (array_key_exists('underline', $pStyles)) {
					$this->setUnderline($pStyles['underline']);
				}

				if (array_key_exists('strike', $pStyles)) {
					$this->setStrikethrough($pStyles['strike']);
				}

				if (array_key_exists('color', $pStyles)) {
					$this->getColor()->applyFromArray($pStyles['color']);
				}

				if (array_key_exists('size', $pStyles)) {
					$this->setSize($pStyles['size']);
				}
			}
		}
		else {
			throw new Exception('Invalid style array passed.');
		}

		return $this;
	}

	public function getName()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getName();
		}

		return $this->_name;
	}

	public function setName($pValue = 'Calibri')
	{
		if ($pValue == '') {
			$pValue = 'Calibri';
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('name' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_name = $pValue;
		}

		return $this;
	}

	public function getSize()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getSize();
		}

		return $this->_size;
	}

	public function setSize($pValue = 10)
	{
		if ($pValue == '') {
			$pValue = 10;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('size' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_size = $pValue;
		}

		return $this;
	}

	public function getBold()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getBold();
		}

		return $this->_bold;
	}

	public function setBold($pValue = false)
	{
		if ($pValue == '') {
			$pValue = false;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('bold' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_bold = $pValue;
		}

		return $this;
	}

	public function getItalic()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getItalic();
		}

		return $this->_italic;
	}

	public function setItalic($pValue = false)
	{
		if ($pValue == '') {
			$pValue = false;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('italic' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_italic = $pValue;
		}

		return $this;
	}

	public function getSuperScript()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getSuperScript();
		}

		return $this->_superScript;
	}

	public function setSuperScript($pValue = false)
	{
		if ($pValue == '') {
			$pValue = false;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('superScript' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_superScript = $pValue;
			$this->_subScript = !$pValue;
		}

		return $this;
	}

	public function getSubScript()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getSubScript();
		}

		return $this->_subScript;
	}

	public function setSubScript($pValue = false)
	{
		if ($pValue == '') {
			$pValue = false;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('subScript' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_subScript = $pValue;
			$this->_superScript = !$pValue;
		}

		return $this;
	}

	public function getUnderline()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getUnderline();
		}

		return $this->_underline;
	}

	public function setUnderline($pValue = PHPExcel_Style_Font::UNDERLINE_NONE)
	{
		if ($pValue == '') {
			$pValue = PHPExcel_Style_Font::UNDERLINE_NONE;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('underline' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_underline = $pValue;
		}

		return $this;
	}

	public function getStriketrough()
	{
		return $this->getStrikethrough();
	}

	public function setStriketrough($pValue = false)
	{
		return $this->setStrikethrough($pValue);
	}

	public function getStrikethrough()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getStrikethrough();
		}

		return $this->_strikethrough;
	}

	public function setStrikethrough($pValue = false)
	{
		if ($pValue == '') {
			$pValue = false;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('strike' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_strikethrough = $pValue;
		}

		return $this;
	}

	public function getColor()
	{
		return $this->_color;
	}

	public function setColor(PHPExcel_Style_Color $pValue = NULL)
	{
		$color = ($pValue->getIsSupervisor() ? $pValue->getSharedComponent() : $pValue);

		if ($this->_isSupervisor) {
			$styleArray = $this->getColor()->getStyleArray(array('argb' => $color->getARGB()));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_color = $color;
		}

		return $this;
	}

	public function getHashCode()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getHashCode();
		}

		return md5($this->_name . $this->_size . ($this->_bold ? 't' : 'f') . ($this->_italic ? 't' : 'f') . ($this->_superScript ? 't' : 'f') . ($this->_subScript ? 't' : 'f') . $this->_underline . ($this->_strikethrough ? 't' : 'f') . $this->_color->getHashCode() . 'PHPExcel_Style_Font');
	}

	public function __clone()
	{
		$vars = get_object_vars($this);

		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			}
			else {
				$this->$key = $value;
			}
		}
	}
}

?>

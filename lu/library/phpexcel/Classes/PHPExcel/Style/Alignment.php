<?php

class PHPExcel_Style_Alignment implements PHPExcel_IComparable
{
	const HORIZONTAL_GENERAL = 'general';
	const HORIZONTAL_LEFT = 'left';
	const HORIZONTAL_RIGHT = 'right';
	const HORIZONTAL_CENTER = 'center';
	const HORIZONTAL_CENTER_CONTINUOUS = 'centerContinuous';
	const HORIZONTAL_JUSTIFY = 'justify';
	const VERTICAL_BOTTOM = 'bottom';
	const VERTICAL_TOP = 'top';
	const VERTICAL_CENTER = 'center';
	const VERTICAL_JUSTIFY = 'justify';

	/**
	 * Horizontal
	 *
	 * @var string
	 */
	private $_horizontal;
	/**
	 * Vertical
	 *
	 * @var string
	 */
	private $_vertical;
	/**
	 * Text rotation
	 *
	 * @var int
	 */
	private $_textRotation;
	/**
	 * Wrap text
	 *
	 * @var boolean
	 */
	private $_wrapText;
	/**
	 * Shrink to fit
	 *
	 * @var boolean
	 */
	private $_shrinkToFit;
	/**
	 * Indent - only possible with horizontal alignment left and right
	 *
	 * @var int
	 */
	private $_indent;
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
		$this->_horizontal = PHPExcel_Style_Alignment::HORIZONTAL_GENERAL;
		$this->_vertical = PHPExcel_Style_Alignment::VERTICAL_BOTTOM;
		$this->_textRotation = 0;
		$this->_wrapText = false;
		$this->_shrinkToFit = false;
		$this->_indent = 0;
	}

	public function bindParent($parent)
	{
		$this->_parent = $parent;
		return $this;
	}

	public function getIsSupervisor()
	{
		return $this->_isSupervisor;
	}

	public function getSharedComponent()
	{
		return $this->_parent->getSharedComponent()->getAlignment();
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
		return array('alignment' => $array);
	}

	public function applyFromArray($pStyles = NULL)
	{
		if (is_array($pStyles)) {
			if ($this->_isSupervisor) {
				$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($this->getStyleArray($pStyles));
			}
			else {
				if (array_key_exists('horizontal', $pStyles)) {
					$this->setHorizontal($pStyles['horizontal']);
				}

				if (array_key_exists('vertical', $pStyles)) {
					$this->setVertical($pStyles['vertical']);
				}

				if (array_key_exists('rotation', $pStyles)) {
					$this->setTextRotation($pStyles['rotation']);
				}

				if (array_key_exists('wrap', $pStyles)) {
					$this->setWrapText($pStyles['wrap']);
				}

				if (array_key_exists('shrinkToFit', $pStyles)) {
					$this->setShrinkToFit($pStyles['shrinkToFit']);
				}

				if (array_key_exists('indent', $pStyles)) {
					$this->setIndent($pStyles['indent']);
				}
			}
		}
		else {
			throw new Exception('Invalid style array passed.');
		}

		return $this;
	}

	public function getHorizontal()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getHorizontal();
		}

		return $this->_horizontal;
	}

	public function setHorizontal($pValue = PHPExcel_Style_Alignment::HORIZONTAL_GENERAL)
	{
		if ($pValue == '') {
			$pValue = PHPExcel_Style_Alignment::HORIZONTAL_GENERAL;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('horizontal' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_horizontal = $pValue;
		}

		return $this;
	}

	public function getVertical()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getVertical();
		}

		return $this->_vertical;
	}

	public function setVertical($pValue = PHPExcel_Style_Alignment::VERTICAL_BOTTOM)
	{
		if ($pValue == '') {
			$pValue = PHPExcel_Style_Alignment::VERTICAL_BOTTOM;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('vertical' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_vertical = $pValue;
		}

		return $this;
	}

	public function getTextRotation()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getTextRotation();
		}

		return $this->_textRotation;
	}

	public function setTextRotation($pValue = 0)
	{
		if ($pValue == 255) {
			$pValue = -165;
		}

		if (((-90 <= $pValue) && ($pValue <= 90)) || ($pValue == -165)) {
			if ($this->_isSupervisor) {
				$styleArray = $this->getStyleArray(array('rotation' => $pValue));
				$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
			}
			else {
				$this->_textRotation = $pValue;
			}
		}
		else {
			throw new Exception('Text rotation should be a value between -90 and 90.');
		}

		return $this;
	}

	public function getWrapText()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getWrapText();
		}

		return $this->_wrapText;
	}

	public function setWrapText($pValue = false)
	{
		if ($pValue == '') {
			$pValue = false;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('wrap' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_wrapText = $pValue;
		}

		return $this;
	}

	public function getShrinkToFit()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getShrinkToFit();
		}

		return $this->_shrinkToFit;
	}

	public function setShrinkToFit($pValue = false)
	{
		if ($pValue == '') {
			$pValue = false;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('shrinkToFit' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_shrinkToFit = $pValue;
		}

		return $this;
	}

	public function getIndent()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getIndent();
		}

		return $this->_indent;
	}

	public function setIndent($pValue = 0)
	{
		if (0 < $pValue) {
			if (($this->getHorizontal() != self::HORIZONTAL_GENERAL) && ($this->getHorizontal() != self::HORIZONTAL_LEFT) && ($this->getHorizontal() != self::HORIZONTAL_RIGHT)) {
				$pValue = 0;
			}
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('indent' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_indent = $pValue;
		}

		return $this;
	}

	public function getHashCode()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getHashCode();
		}

		return md5($this->_horizontal . $this->_vertical . $this->_textRotation . ($this->_wrapText ? 't' : 'f') . ($this->_shrinkToFit ? 't' : 'f') . $this->_indent . 'PHPExcel_Style_Alignment');
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

<?php

class PHPExcel_Style_Color implements PHPExcel_IComparable
{
	const COLOR_BLACK = 'FF000000';
	const COLOR_WHITE = 'FFFFFFFF';
	const COLOR_RED = 'FFFF0000';
	const COLOR_DARKRED = 'FF800000';
	const COLOR_BLUE = 'FF0000FF';
	const COLOR_DARKBLUE = 'FF000080';
	const COLOR_GREEN = 'FF00FF00';
	const COLOR_DARKGREEN = 'FF008000';
	const COLOR_YELLOW = 'FFFFFF00';
	const COLOR_DARKYELLOW = 'FF808000';

	/**
	 * Indexed colors array
	 *
	 * @var array
	 */
	static private $_indexedColors;
	/**
	 * ARGB - Alpha RGB
	 *
	 * @var string
	 */
	private $_argb;
	/**
	 * Supervisor?
	 *
	 * @var boolean
	 */
	private $_isSupervisor;
	/**
	 * Parent. Only used for supervisor
	 *
	 * @var mixed
	 */
	private $_parent;
	/**
	 * Parent property name
	 *
	 * @var string
	 */
	private $_parentPropertyName;

	public function __construct($pARGB = PHPExcel_Style_Color::COLOR_BLACK, $isSupervisor = false)
	{
		$this->_isSupervisor = $isSupervisor;
		$this->_argb = $pARGB;
	}

	public function bindParent($parent, $parentPropertyName)
	{
		$this->_parent = $parent;
		$this->_parentPropertyName = $parentPropertyName;
		return $this;
	}

	public function getIsSupervisor()
	{
		return $this->_isSupervisor;
	}

	public function getSharedComponent()
	{
		switch ($this->_parentPropertyName) {
		case '_endColor':
			return $this->_parent->getSharedComponent()->getEndColor();
			break;

		case '_color':
			return $this->_parent->getSharedComponent()->getColor();
			break;

		case '_startColor':
			return $this->_parent->getSharedComponent()->getStartColor();
			break;
		}
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
		switch ($this->_parentPropertyName) {
		case '_endColor':
			$key = 'endcolor';
			break;

		case '_color':
			$key = 'color';
			break;

		case '_startColor':
			$key = 'startcolor';
			break;
		}

		return $this->_parent->getStyleArray(array($key => $array));
	}

	public function applyFromArray($pStyles = NULL)
	{
		if (is_array($pStyles)) {
			if ($this->_isSupervisor) {
				$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($this->getStyleArray($pStyles));
			}
			else {
				if (array_key_exists('rgb', $pStyles)) {
					$this->setRGB($pStyles['rgb']);
				}

				if (array_key_exists('argb', $pStyles)) {
					$this->setARGB($pStyles['argb']);
				}
			}
		}
		else {
			throw new Exception('Invalid style array passed.');
		}

		return $this;
	}

	public function getARGB()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getARGB();
		}

		return $this->_argb;
	}

	public function setARGB($pValue = PHPExcel_Style_Color::COLOR_BLACK)
	{
		if ($pValue == '') {
			$pValue = PHPExcel_Style_Color::COLOR_BLACK;
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('argb' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_argb = $pValue;
		}

		return $this;
	}

	public function getRGB()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getRGB();
		}

		return substr($this->_argb, 2);
	}

	public function setRGB($pValue = '000000')
	{
		if ($pValue == '') {
			$pValue = '000000';
		}

		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('argb' => 'FF' . $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_argb = 'FF' . $pValue;
		}

		return $this;
	}

	static public function indexedColor($pIndex)
	{
		$pIndex = intval($pIndex);

		if (is_null(self::$_indexedColors)) {
			self::$_indexedColors = array();
			self::$_indexedColors[] = '00000000';
			self::$_indexedColors[] = '00FFFFFF';
			self::$_indexedColors[] = '00FF0000';
			self::$_indexedColors[] = '0000FF00';
			self::$_indexedColors[] = '000000FF';
			self::$_indexedColors[] = '00FFFF00';
			self::$_indexedColors[] = '00FF00FF';
			self::$_indexedColors[] = '0000FFFF';
			self::$_indexedColors[] = '00000000';
			self::$_indexedColors[] = '00FFFFFF';
			self::$_indexedColors[] = '00FF0000';
			self::$_indexedColors[] = '0000FF00';
			self::$_indexedColors[] = '000000FF';
			self::$_indexedColors[] = '00FFFF00';
			self::$_indexedColors[] = '00FF00FF';
			self::$_indexedColors[] = '0000FFFF';
			self::$_indexedColors[] = '00800000';
			self::$_indexedColors[] = '00008000';
			self::$_indexedColors[] = '00000080';
			self::$_indexedColors[] = '00808000';
			self::$_indexedColors[] = '00800080';
			self::$_indexedColors[] = '00008080';
			self::$_indexedColors[] = '00C0C0C0';
			self::$_indexedColors[] = '00808080';
			self::$_indexedColors[] = '009999FF';
			self::$_indexedColors[] = '00993366';
			self::$_indexedColors[] = '00FFFFCC';
			self::$_indexedColors[] = '00CCFFFF';
			self::$_indexedColors[] = '00660066';
			self::$_indexedColors[] = '00FF8080';
			self::$_indexedColors[] = '000066CC';
			self::$_indexedColors[] = '00CCCCFF';
			self::$_indexedColors[] = '00000080';
			self::$_indexedColors[] = '00FF00FF';
			self::$_indexedColors[] = '00FFFF00';
			self::$_indexedColors[] = '0000FFFF';
			self::$_indexedColors[] = '00800080';
			self::$_indexedColors[] = '00800000';
			self::$_indexedColors[] = '00008080';
			self::$_indexedColors[] = '000000FF';
			self::$_indexedColors[] = '0000CCFF';
			self::$_indexedColors[] = '00CCFFFF';
			self::$_indexedColors[] = '00CCFFCC';
			self::$_indexedColors[] = '00FFFF99';
			self::$_indexedColors[] = '0099CCFF';
			self::$_indexedColors[] = '00FF99CC';
			self::$_indexedColors[] = '00CC99FF';
			self::$_indexedColors[] = '00FFCC99';
			self::$_indexedColors[] = '003366FF';
			self::$_indexedColors[] = '0033CCCC';
			self::$_indexedColors[] = '0099CC00';
			self::$_indexedColors[] = '00FFCC00';
			self::$_indexedColors[] = '00FF9900';
			self::$_indexedColors[] = '00FF6600';
			self::$_indexedColors[] = '00666699';
			self::$_indexedColors[] = '00969696';
			self::$_indexedColors[] = '00003366';
			self::$_indexedColors[] = '00339966';
			self::$_indexedColors[] = '00003300';
			self::$_indexedColors[] = '00333300';
			self::$_indexedColors[] = '00993300';
			self::$_indexedColors[] = '00993366';
			self::$_indexedColors[] = '00333399';
			self::$_indexedColors[] = '00333333';
		}

		if (array_key_exists($pIndex, self::$_indexedColors)) {
			return new PHPExcel_Style_Color(self::$_indexedColors[$pIndex]);
		}

		return new PHPExcel_Style_Color();
	}

	public function getHashCode()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getHashCode();
		}

		return md5($this->_argb . 'PHPExcel_Style_Color');
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

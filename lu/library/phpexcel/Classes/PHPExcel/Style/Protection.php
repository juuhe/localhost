<?php

class PHPExcel_Style_Protection implements PHPExcel_IComparable
{
	const PROTECTION_INHERIT = 'inherit';
	const PROTECTION_PROTECTED = 'protected';
	const PROTECTION_UNPROTECTED = 'unprotected';

	/**
	 * Locked
	 *
	 * @var string
	 */
	private $_locked;
	/**
	 * Hidden
	 *
	 * @var string
	 */
	private $_hidden;
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
		$this->_locked = self::PROTECTION_INHERIT;
		$this->_hidden = self::PROTECTION_INHERIT;
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
		return $this->_parent->getSharedComponent()->getProtection();
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
		return array('protection' => $array);
	}

	public function applyFromArray($pStyles = NULL)
	{
		if (is_array($pStyles)) {
			if ($this->_isSupervisor) {
				$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($this->getStyleArray($pStyles));
			}
			else {
				if (array_key_exists('locked', $pStyles)) {
					$this->setLocked($pStyles['locked']);
				}

				if (array_key_exists('hidden', $pStyles)) {
					$this->setHidden($pStyles['hidden']);
				}
			}
		}
		else {
			throw new Exception('Invalid style array passed.');
		}

		return $this;
	}

	public function getLocked()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getLocked();
		}

		return $this->_locked;
	}

	public function setLocked($pValue = self::PROTECTION_INHERIT)
	{
		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('locked' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_locked = $pValue;
		}

		return $this;
	}

	public function getHidden()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getHidden();
		}

		return $this->_hidden;
	}

	public function setHidden($pValue = self::PROTECTION_INHERIT)
	{
		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('hidden' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		}
		else {
			$this->_hidden = $pValue;
		}

		return $this;
	}

	public function getHashCode()
	{
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getHashCode();
		}

		return md5($this->_locked . $this->_hidden . 'PHPExcel_Style_Protection');
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

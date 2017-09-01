<?php

class PHPExcel_Shared_Escher_DggContainer
{
	/**
	 * Maximum shape index of all shapes in all drawings increased by one
	 *
	 * @var int
	 */
	private $_spIdMax;
	/**
	 * Total number of drawings saved
	 *
	 * @var int
	 */
	private $_cDgSaved;
	/**
	 * Total number of shapes saved (including group shapes)
	 *
	 * @var int
	 */
	private $_cSpSaved;
	/**
	 * BLIP Store Container
	 *
	 * @var PHPExcel_Shared_Escher_DggContainer_BstoreContainer
	 */
	private $_bstoreContainer;
	/**
	 * Array of options for the drawing group
	 *
	 * @var array
	 */
	private $_OPT = array();

	public function getSpIdMax()
	{
		return $this->_spIdMax;
	}

	public function setSpIdMax($value)
	{
		$this->_spIdMax = $value;
	}

	public function getCDgSaved()
	{
		return $this->_cDgSaved;
	}

	public function setCDgSaved($value)
	{
		$this->_cDgSaved = $value;
	}

	public function getCSpSaved()
	{
		return $this->_cSpSaved;
	}

	public function setCSpSaved($value)
	{
		$this->_cSpSaved = $value;
	}

	public function getBstoreContainer()
	{
		return $this->_bstoreContainer;
	}

	public function setBstoreContainer($bstoreContainer)
	{
		$this->_bstoreContainer = $bstoreContainer;
	}

	public function setOPT($property, $value)
	{
		$this->_OPT[$property] = $value;
	}

	public function getOPT($property)
	{
		if (isset($this->_OPT[$property])) {
			return $this->_OPT[$property];
		}

		return NULL;
	}
}


?>

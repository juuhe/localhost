<?php

class PHPExcel_Worksheet_PageMargins
{
	/**
	 * Left
	 *
	 * @var double
	 */
	private $_left;
	/**
	 * Right
	 *
	 * @var double
	 */
	private $_right;
	/**
	 * Top
	 *
	 * @var double
	 */
	private $_top;
	/**
	 * Bottom
	 *
	 * @var double
	 */
	private $_bottom;
	/**
	 * Header
	 *
	 * @var double
	 */
	private $_header;
	/**
	 * Footer
	 *
	 * @var double
	 */
	private $_footer;

	public function __construct()
	{
		$this->_left = 0.69999999999999996;
		$this->_right = 0.69999999999999996;
		$this->_top = 0.75;
		$this->_bottom = 0.75;
		$this->_header = 0.29999999999999999;
		$this->_footer = 0.29999999999999999;
	}

	public function getLeft()
	{
		return $this->_left;
	}

	public function setLeft($pValue)
	{
		$this->_left = $pValue;
		return $this;
	}

	public function getRight()
	{
		return $this->_right;
	}

	public function setRight($pValue)
	{
		$this->_right = $pValue;
		return $this;
	}

	public function getTop()
	{
		return $this->_top;
	}

	public function setTop($pValue)
	{
		$this->_top = $pValue;
		return $this;
	}

	public function getBottom()
	{
		return $this->_bottom;
	}

	public function setBottom($pValue)
	{
		$this->_bottom = $pValue;
		return $this;
	}

	public function getHeader()
	{
		return $this->_header;
	}

	public function setHeader($pValue)
	{
		$this->_header = $pValue;
		return $this;
	}

	public function getFooter()
	{
		return $this->_footer;
	}

	public function setFooter($pValue)
	{
		$this->_footer = $pValue;
		return $this;
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

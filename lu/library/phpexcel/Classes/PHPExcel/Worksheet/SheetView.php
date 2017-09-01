<?php

class PHPExcel_Worksheet_SheetView
{
	/**
	 * ZoomScale
	 * 
	 * Valid values range from 10 to 400.
	 *
	 * @var int
	 */
	private $_zoomScale;
	/**
	 * ZoomScaleNormal
	 * 
	 * Valid values range from 10 to 400.
	 *
	 * @var int
	 */
	private $_zoomScaleNormal;

	public function __construct()
	{
		$this->_zoomScale = 100;
		$this->_zoomScaleNormal = 100;
	}

	public function getZoomScale()
	{
		return $this->_zoomScale;
	}

	public function setZoomScale($pValue = 100)
	{
		if ((1 <= $pValue) || is_null($pValue)) {
			$this->_zoomScale = $pValue;
		}
		else {
			throw new Exception('Scale must be greater than or equal to 1.');
		}

		return $this;
	}

	public function getZoomScaleNormal()
	{
		return $this->_zoomScaleNormal;
	}

	public function setZoomScaleNormal($pValue = 100)
	{
		if ((1 <= $pValue) || is_null($pValue)) {
			$this->_zoomScaleNormal = $pValue;
		}
		else {
			throw new Exception('Scale must be greater than or equal to 1.');
		}

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

<?php

class PHPExcel_CachedObjectStorage_CacheBase
{
	/**
	 *	Parent worksheet
	 *
	 *	@var PHPExcel_Worksheet
	 */
	protected $_parent;
	/**
	 *	The currently active Cell
	 *
	 *	@var PHPExcel_Cell
	 */
	protected $_currentObject;
	/**
	 *	Coordinate address of the currently active Cell
	 *
	 *	@var string
	 */
	protected $_currentObjectID;
	/**
	 *	An array of cells or cell pointers for the worksheet cells held in this cache,
	 *		and indexed by their coordinate address within the worksheet
	 *
	 *	@var array of mixed
	 */
	protected $_cellCache = array();

	public function __construct(PHPExcel_Worksheet $parent)
	{
		$this->_parent = $parent;
	}

	public function isDataSet($pCoord)
	{
		if ($pCoord === $this->_currentObjectID) {
			return true;
		}

		return isset($this->_cellCache[$pCoord]);
	}

	public function updateCacheData(PHPExcel_Cell $cell)
	{
		$pCoord = $cell->getCoordinate();
		return $this->addCacheData($pCoord, $cell);
	}

	public function deleteCacheData($pCoord)
	{
		if ($pCoord === $this->_currentObjectID) {
			$this->_currentObject->detach();
			$this->_currentObjectID = $this->_currentObject = NULL;
		}

		if (isset($this->_cellCache[$pCoord])) {
			$this->_cellCache[$pCoord]->detach();
			unset($this->_cellCache[$pCoord]);
		}
	}

	public function getCellList()
	{
		return array_keys($this->_cellCache);
	}

	public function getSortedCellList()
	{
		$sortKeys = array();

		foreach ($this->_cellCache as $coord => $value) {
			preg_match('/^(\\w+)(\\d+)$/U', $coord, $matches);
			$sortKeys[$coord] = str_pad($rowNum . str_pad($colNum, 3, '@', STR_PAD_LEFT), 12, '0', STR_PAD_LEFT);
		}

		asort($sortKeys);
		return array_keys($sortKeys);
	}
}


?>

<?php

class PHPExcel_CachedObjectStorage_Memory extends PHPExcel_CachedObjectStorage_CacheBase implements PHPExcel_CachedObjectStorage_ICache
{
	public function addCacheData($pCoord, PHPExcel_Cell $cell)
	{
		$this->_cellCache[$pCoord] = $cell;
		return $cell;
	}

	public function getCacheData($pCoord)
	{
		if (!isset($this->_cellCache[$pCoord])) {
			return NULL;
		}

		return $this->_cellCache[$pCoord];
	}

	public function unsetWorksheetCells()
	{
		foreach ($this->_cellCache as $k => &$cell) {
			$cell->detach();
			$this->_cellCache[$k] = NULL;
		}

		unset($cell);
		$this->_cellCache = array();
		$this->_parent = NULL;
	}
}

?>

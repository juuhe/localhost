<?php

class PHPExcel_CachedObjectStorage_MemoryGZip extends PHPExcel_CachedObjectStorage_CacheBase implements PHPExcel_CachedObjectStorage_ICache
{
	private function _storeData()
	{
		$this->_currentObject->detach();
		$this->_cellCache[$this->_currentObjectID] = gzdeflate(serialize($this->_currentObject));
		$this->_currentObjectID = $this->_currentObject = NULL;
	}

	public function addCacheData($pCoord, PHPExcel_Cell $cell)
	{
		if (($pCoord !== $this->_currentObjectID) && ($this->_currentObjectID !== NULL)) {
			$this->_storeData();
		}

		$this->_currentObjectID = $pCoord;
		$this->_currentObject = $cell;
		return $cell;
	}

	public function getCacheData($pCoord)
	{
		if ($pCoord === $this->_currentObjectID) {
			return $this->_currentObject;
		}

		$this->_storeData();

		if (!isset($this->_cellCache[$pCoord])) {
			return NULL;
		}

		$this->_currentObjectID = $pCoord;
		$this->_currentObject = unserialize(gzinflate($this->_cellCache[$pCoord]));
		$this->_currentObject->attach($this->_parent);
		return $this->_currentObject;
	}

	public function unsetWorksheetCells()
	{
		if (!is_null($this->_currentObject)) {
			$this->_currentObject->detach();
			$this->_currentObject = $this->_currentObjectID = NULL;
		}

		$this->_cellCache = array();
		$this->_parent = NULL;
	}
}

?>

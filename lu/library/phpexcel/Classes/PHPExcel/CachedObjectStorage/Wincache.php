<?php

class PHPExcel_CachedObjectStorage_Wincache extends PHPExcel_CachedObjectStorage_CacheBase implements PHPExcel_CachedObjectStorage_ICache
{
	private $_cachePrefix;
	private $_cacheTime = 600;

	private function _storeData()
	{
		$this->_currentObject->detach();
		$obj = serialize($this->_currentObject);

		if (wincache_ucache_exists($this->_cachePrefix . $this->_currentObjectID . '.cache')) {
			wincache_ucache_set($this->_cachePrefix . $this->_currentObjectID . '.cache', $obj, $this->_cacheTime);
		}
		else {
			wincache_ucache_add($this->_cachePrefix . $this->_currentObjectID . '.cache', $obj, $this->_cacheTime);
		}

		$this->_currentObjectID = $this->_currentObject = NULL;
	}

	public function addCacheData($pCoord, PHPExcel_Cell $cell)
	{
		if (($pCoord !== $this->_currentObjectID) && ($this->_currentObjectID !== NULL)) {
			$this->_storeData();
		}

		$this->_cellCache[$pCoord] = true;
		$this->_currentObjectID = $pCoord;
		$this->_currentObject = $cell;
		return $cell;
	}

	public function isDataSet($pCoord)
	{
		if (parent::isDataSet($pCoord)) {
			if ($this->_currentObjectID == $pCoord) {
				return true;
			}

			$success = wincache_ucache_exists($this->_cachePrefix . $pCoord . '.cache');

			if ($success === false) {
				parent::deleteCacheData($pCoord);
				throw new Exception('Cell entry no longer exists in Wincache');
			}

			return true;
		}

		return false;
	}

	public function getCacheData($pCoord)
	{
		if ($pCoord === $this->_currentObjectID) {
			return $this->_currentObject;
		}

		$this->_storeData();
		$obj = NULL;

		if (parent::isDataSet($pCoord)) {
			$success = false;
			$obj = wincache_ucache_get($this->_cachePrefix . $pCoord . '.cache', $success);

			if ($success === false) {
				parent::deleteCacheData($pCoord);
				throw new Exception('Cell entry no longer exists in Wincache');
			}
		}
		else {
			return NULL;
		}

		$this->_currentObjectID = $pCoord;
		$this->_currentObject = unserialize($obj);
		$this->_currentObject->attach($this->_parent);
		return $this->_currentObject;
	}

	public function deleteCacheData($pCoord)
	{
		wincache_ucache_delete($this->_cachePrefix . $pCoord . '.cache');
		parent::deleteCacheData($pCoord);
	}

	public function unsetWorksheetCells()
	{
		if (!is_null($this->_currentObject)) {
			$this->_currentObject->detach();
			$this->_currentObject = $this->_currentObjectID = NULL;
		}

		$this->__destruct();
		$this->_cellCache = array();
		$this->_parent = NULL;
	}

	public function __construct(PHPExcel_Worksheet $parent, $arguments)
	{
		$cacheTime = (isset($arguments['cacheTime']) ? $arguments['cacheTime'] : 600);

		if (is_null($this->_cachePrefix)) {
			if (function_exists('posix_getpid')) {
				$baseUnique = posix_getpid();
			}
			else {
				$baseUnique = mt_rand();
			}

			$this->_cachePrefix = substr(md5(uniqid($baseUnique, true)), 0, 8) . '.';
			$this->_cacheTime = $cacheTime;
			parent::__construct($parent);
		}
	}

	public function __destruct()
	{
		$cacheList = $this->getCellList();

		foreach ($cacheList as $cellID) {
			wincache_ucache_delete($this->_cachePrefix . $cellID . '.cache');
		}
	}
}

?>

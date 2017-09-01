<?php

class PHPExcel_CachedObjectStorage_Memcache extends PHPExcel_CachedObjectStorage_CacheBase implements PHPExcel_CachedObjectStorage_ICache
{
	private $_cachePrefix;
	private $_cacheTime = 600;
	private $_memcache;

	private function _storeData()
	{
		$this->_currentObject->detach();
		$obj = serialize($this->_currentObject);

		if (!$this->_memcache->replace($this->_cachePrefix . $this->_currentObjectID . '.cache', $obj, NULL, $this->_cacheTime)) {
			if (!$this->_memcache->add($this->_cachePrefix . $this->_currentObjectID . '.cache', $obj, NULL, $this->_cacheTime)) {
				$this->__destruct();
				throw new Exception('Failed to store cell in Memcache');
			}
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

			$success = $this->_memcache->get($this->_cachePrefix . $pCoord . '.cache');

			if ($success === false) {
				parent::deleteCacheData($pCoord);
				throw new Exception('Cell entry no longer exists in Memcache');
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

		if (parent::isDataSet($pCoord)) {
			$obj = $this->_memcache->get($this->_cachePrefix . $pCoord . '.cache');

			if ($obj === false) {
				parent::deleteCacheData($pCoord);
				throw new Exception('Cell entry no longer exists in Memcache');
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
		$this->_memcache->delete($this->_cachePrefix . $pCoord . '.cache');
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
		$memcacheServer = (isset($arguments['memcacheServer']) ? $arguments['memcacheServer'] : 'localhost');
		$memcachePort = (isset($arguments['memcachePort']) ? $arguments['memcachePort'] : 11211);
		$cacheTime = (isset($arguments['cacheTime']) ? $arguments['cacheTime'] : 600);

		if (is_null($this->_cachePrefix)) {
			if (function_exists('posix_getpid')) {
				$baseUnique = posix_getpid();
			}
			else {
				$baseUnique = mt_rand();
			}

			$this->_cachePrefix = substr(md5(uniqid($baseUnique, true)), 0, 8) . '.';
			$this->_memcache = new Memcache();

			if (!$this->_memcache->addServer($memcacheServer, $memcachePort, false, 50, 5, 5, true, array($this, 'failureCallback'))) {
				throw new Exception('Could not connect to Memcache server at ' . $memcacheServer . ':' . $memcachePort);
			}

			$this->_cacheTime = $cacheTime;
			parent::__construct($parent);
		}
	}

	public function failureCallback($host, $port)
	{
		throw new Exception('memcache ' . $host . ':' . $port . ' failed');
	}

	public function __destruct()
	{
		$cacheList = $this->getCellList();

		foreach ($cacheList as $cellID) {
			$this->_memcache->delete($this->_cachePrefix . $cellID . '.cache');
		}
	}
}

?>

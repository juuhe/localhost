<?php

class PHPExcel_CachedObjectStorage_DiscISAM extends PHPExcel_CachedObjectStorage_CacheBase implements PHPExcel_CachedObjectStorage_ICache
{
	private $_fileName;
	private $_fileHandle;

	private function _storeData()
	{
		$this->_currentObject->detach();
		fseek($this->_fileHandle, 0, SEEK_END);
		$offset = ftell($this->_fileHandle);
		fwrite($this->_fileHandle, serialize($this->_currentObject));
		$this->_cellCache[$this->_currentObjectID] = array('ptr' => $offset, 'sz' => ftell($this->_fileHandle) - $offset);
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
		fseek($this->_fileHandle, $this->_cellCache[$pCoord]['ptr']);
		$this->_currentObject = unserialize(fread($this->_fileHandle, $this->_cellCache[$pCoord]['sz']));
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
		$this->__destruct();
	}

	public function __construct(PHPExcel_Worksheet $parent)
	{
		parent::__construct($parent);

		if (is_null($this->_fileHandle)) {
			if (function_exists('posix_getpid')) {
				$baseUnique = posix_getpid();
			}
			else {
				$baseUnique = mt_rand();
			}

			$this->_fileName = sys_get_temp_dir() . '/PHPExcel.' . uniqid($baseUnique, true) . '.cache';
			$this->_fileHandle = fopen($this->_fileName, 'a+');
		}
	}

	public function __destruct()
	{
		if (!is_null($this->_fileHandle)) {
			fclose($this->_fileHandle);
			unlink($this->_fileName);
		}

		$this->_fileHandle = NULL;
	}
}

?>

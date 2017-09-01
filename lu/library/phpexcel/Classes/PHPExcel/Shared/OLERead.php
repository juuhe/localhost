<?php

class PHPExcel_Shared_OLERead
{
	const IDENTIFIER_OLE = IDENTIFIER_OLE;
	const BIG_BLOCK_SIZE = 512;
	const SMALL_BLOCK_SIZE = 64;
	const PROPERTY_STORAGE_BLOCK_SIZE = 128;
	const SMALL_BLOCK_THRESHOLD = 4096;
	const NUM_BIG_BLOCK_DEPOT_BLOCKS_POS = 44;
	const ROOT_START_BLOCK_POS = 48;
	const SMALL_BLOCK_DEPOT_BLOCK_POS = 60;
	const EXTENSION_BLOCK_POS = 68;
	const NUM_EXTENSION_BLOCK_POS = 72;
	const BIG_BLOCK_DEPOT_BLOCKS_POS = 76;
	const SIZE_OF_NAME_POS = 64;
	const TYPE_POS = 66;
	const START_BLOCK_POS = 116;
	const SIZE_POS = 120;

	private $data = '';

	public function read($sFileName)
	{
		if (!is_readable($sFileName)) {
			throw new Exception('Could not open ' . $sFileName . ' for reading! File does not exist, or it is not readable.');
		}

		$this->data = file_get_contents($sFileName);

		if (substr($this->data, 0, 8) != self::IDENTIFIER_OLE) {
			throw new Exception('The filename ' . $sFileName . ' is not recognised as an OLE file');
		}

		$this->numBigBlockDepotBlocks = $this->_GetInt4d($this->data, self::NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);
		$this->rootStartBlock = $this->_GetInt4d($this->data, self::ROOT_START_BLOCK_POS);
		$this->sbdStartBlock = $this->_GetInt4d($this->data, self::SMALL_BLOCK_DEPOT_BLOCK_POS);
		$this->extensionBlock = $this->_GetInt4d($this->data, self::EXTENSION_BLOCK_POS);
		$this->numExtensionBlocks = $this->_GetInt4d($this->data, self::NUM_EXTENSION_BLOCK_POS);
		$bigBlockDepotBlocks = array();
		$pos = self::BIG_BLOCK_DEPOT_BLOCKS_POS;
		$bbdBlocks = $this->numBigBlockDepotBlocks;

		if ($this->numExtensionBlocks != 0) {
			$bbdBlocks = (self::BIG_BLOCK_SIZE - self::BIG_BLOCK_DEPOT_BLOCKS_POS) / 4;
		}

		for ($i = 0; $i < $bbdBlocks; ++$i) {
			$bigBlockDepotBlocks[$i] = $this->_GetInt4d($this->data, $pos);
			$pos += 4;
		}

		for ($j = 0; $j < $this->numExtensionBlocks; ++$j) {
			$pos = ($this->extensionBlock + 1) * self::BIG_BLOCK_SIZE;
			$blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, (self::BIG_BLOCK_SIZE / 4) - 1);

			for ($i = $bbdBlocks; $i < ($bbdBlocks + $blocksToRead); ++$i) {
				$bigBlockDepotBlocks[$i] = $this->_GetInt4d($this->data, $pos);
				$pos += 4;
			}

			$bbdBlocks += $blocksToRead;

			if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
				$this->extensionBlock = $this->_GetInt4d($this->data, $pos);
			}
		}

		$pos = 0;
		$index = 0;
		$this->bigBlockChain = array();

		for ($i = 0; $i < $this->numBigBlockDepotBlocks; ++$i) {
			$pos = ($bigBlockDepotBlocks[$i] + 1) * self::BIG_BLOCK_SIZE;

			for ($j = 0; $j < (self::BIG_BLOCK_SIZE / 4); ++$j) {
				$this->bigBlockChain[$index] = $this->_GetInt4d($this->data, $pos);
				$pos += 4;
				++$index;
			}
		}

		$pos = 0;
		$index = 0;
		$sbdBlock = $this->sbdStartBlock;
		$this->smallBlockChain = array();

		while ($sbdBlock != -2) {
			$pos = ($sbdBlock + 1) * self::BIG_BLOCK_SIZE;

			for ($j = 0; $j < (self::BIG_BLOCK_SIZE / 4); ++$j) {
				$this->smallBlockChain[$index] = $this->_GetInt4d($this->data, $pos);
				$pos += 4;
				++$index;
			}

			$sbdBlock = $this->bigBlockChain[$sbdBlock];
		}

		$block = $this->rootStartBlock;
		$pos = 0;
		$this->entry = $this->_readData($block);
		$this->_readPropertySets();
	}

	public function getWorkBook()
	{
		if ($this->props[$this->wrkbook]['size'] < self::SMALL_BLOCK_THRESHOLD) {
			$rootdata = $this->_readData($this->props[$this->rootentry]['startBlock']);
			$streamData = '';
			$block = $this->props[$this->wrkbook]['startBlock'];
			$pos = 0;

			while ($block != -2) {
				$pos = $block * self::SMALL_BLOCK_SIZE;
				$streamData .= substr($rootdata, $pos, self::SMALL_BLOCK_SIZE);
				$block = $this->smallBlockChain[$block];
			}

			return $streamData;
		}
		else {
			$numBlocks = $this->props[$this->wrkbook]['size'] / self::BIG_BLOCK_SIZE;

			if (($this->props[$this->wrkbook]['size'] % self::BIG_BLOCK_SIZE) != 0) {
				++$numBlocks;
			}

			if ($numBlocks == 0) {
				return '';
			}

			$streamData = '';
			$block = $this->props[$this->wrkbook]['startBlock'];
			$pos = 0;

			while ($block != -2) {
				$pos = ($block + 1) * self::BIG_BLOCK_SIZE;
				$streamData .= substr($this->data, $pos, self::BIG_BLOCK_SIZE);
				$block = $this->bigBlockChain[$block];
			}

			return $streamData;
		}
	}

	public function getSummaryInformation()
	{
		if (!isset($this->summaryInformation)) {
			return NULL;
		}

		if ($this->props[$this->summaryInformation]['size'] < self::SMALL_BLOCK_THRESHOLD) {
			$rootdata = $this->_readData($this->props[$this->rootentry]['startBlock']);
			$streamData = '';
			$block = $this->props[$this->summaryInformation]['startBlock'];
			$pos = 0;

			while ($block != -2) {
				$pos = $block * self::SMALL_BLOCK_SIZE;
				$streamData .= substr($rootdata, $pos, self::SMALL_BLOCK_SIZE);
				$block = $this->smallBlockChain[$block];
			}

			return $streamData;
		}
		else {
			$numBlocks = $this->props[$this->summaryInformation]['size'] / self::BIG_BLOCK_SIZE;

			if (($this->props[$this->summaryInformation]['size'] % self::BIG_BLOCK_SIZE) != 0) {
				++$numBlocks;
			}

			if ($numBlocks == 0) {
				return '';
			}

			$streamData = '';
			$block = $this->props[$this->summaryInformation]['startBlock'];
			$pos = 0;

			while ($block != -2) {
				$pos = ($block + 1) * self::BIG_BLOCK_SIZE;
				$streamData .= substr($this->data, $pos, self::BIG_BLOCK_SIZE);
				$block = $this->bigBlockChain[$block];
			}

			return $streamData;
		}
	}

	private function _readData($bl)
	{
		$block = $bl;
		$pos = 0;
		$data = '';

		while ($block != -2) {
			$pos = ($block + 1) * self::BIG_BLOCK_SIZE;
			$data = $data . substr($this->data, $pos, self::BIG_BLOCK_SIZE);
			$block = $this->bigBlockChain[$block];
		}

		return $data;
	}

	private function _readPropertySets()
	{
		$offset = 0;

		while ($offset < strlen($this->entry)) {
			$d = substr($this->entry, $offset, self::PROPERTY_STORAGE_BLOCK_SIZE);
			$nameSize = ord($d[self::SIZE_OF_NAME_POS]) | (ord($d[self::SIZE_OF_NAME_POS + 1]) << 8);
			$type = ord($d[self::TYPE_POS]);
			$startBlock = $this->_GetInt4d($d, self::START_BLOCK_POS);
			$size = $this->_GetInt4d($d, self::SIZE_POS);
			$name = '';

			for ($i = 0; $i < $nameSize; ++$i) {
				$name .= $d[$i];
			}

			$name = str_replace("\x00", '', $name);
			$this->props[] = array('name' => $name, 'type' => $type, 'startBlock' => $startBlock, 'size' => $size);
			if (($name == 'Workbook') || ($name == 'Book') || ($name == 'WORKBOOK') || ($name == 'BOOK')) {
				$this->wrkbook = count($this->props) - 1;
			}

			if (($name == 'Root Entry') || ($name == 'ROOT ENTRY') || ($name == 'R')) {
				$this->rootentry = count($this->props) - 1;
			}

			if ($name == (chr(5) . 'SummaryInformation')) {
				$this->summaryInformation = count($this->props) - 1;
			}

			$offset += self::PROPERTY_STORAGE_BLOCK_SIZE;
		}
	}

	private function _GetInt4d($data, $pos)
	{
		$_or_24 = ord($data[$pos + 3]);

		if (128 <= $_or_24) {
			$_ord_24 = 0 - abs((256 - $_or_24) << 24);
		}
		else {
			$_ord_24 = ($_or_24 & 127) << 24;
		}

		return ord($data[$pos]) | (ord($data[$pos + 1]) << 8) | (ord($data[$pos + 2]) << 16) | $_ord_24;
	}
}

define('IDENTIFIER_OLE', pack('CCCCCCCC', 208, 207, 17, 224, 161, 177, 26, 225));

?>

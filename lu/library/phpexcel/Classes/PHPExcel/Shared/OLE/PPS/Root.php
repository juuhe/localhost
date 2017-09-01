<?php

class PHPExcel_Shared_OLE_PPS_Root extends PHPExcel_Shared_OLE_PPS
{
	public function __construct($time_1st, $time_2nd, $raChild)
	{
		parent::__construct(NULL, PHPExcel_Shared_OLE::Asc2Ucs('Root Entry'), PHPExcel_Shared_OLE::OLE_PPS_TYPE_ROOT, NULL, NULL, NULL, $time_1st, $time_2nd, NULL, $raChild);
	}

	public function save($filename)
	{
		$this->_BIG_BLOCK_SIZE = pow(2, isset($this->_BIG_BLOCK_SIZE) ? $this->_adjust2($this->_BIG_BLOCK_SIZE) : 9);
		$this->_SMALL_BLOCK_SIZE = pow(2, isset($this->_SMALL_BLOCK_SIZE) ? $this->_adjust2($this->_SMALL_BLOCK_SIZE) : 6);

		if (is_resource($filename)) {
			$this->_FILEH_ = $filename;
		}
		else {
			if (($filename == '-') || ($filename == '')) {
				$this->_tmp_filename = tempnam($this->_tmp_dir, 'OLE_PPS_Root');
				$this->_FILEH_ = fopen($this->_tmp_filename, 'w+b');

				if ($this->_FILEH_ == false) {
					throw new Exception('Can\'t create temporary file.');
				}
			}
			else {
				$this->_FILEH_ = fopen($filename, 'wb');
			}
		}

		if ($this->_FILEH_ == false) {
			throw new Exception('Can\'t open ' . $filename . '. It may be in use or protected.');
		}

		$aList = array();
		$this->_savePpsSetPnt($aList);
		list($iSBDcnt, $iBBcnt, $iPPScnt) = $this->_calcSize($aList);
		$this->_saveHeader($iSBDcnt, $iBBcnt, $iPPScnt);
		$this->_data = $this->_makeSmallData($aList);
		$this->_saveBigData($iSBDcnt, $aList);
		$this->_savePps($aList);
		$this->_saveBbd($iSBDcnt, $iBBcnt, $iPPScnt);

		if (!is_resource($filename)) {
			fclose($this->_FILEH_);
		}

		return true;
	}

	public function _calcSize(&$raList)
	{
		list($iSBDcnt, $iBBcnt, $iPPScnt) = array(0, 0, 0);
		$iSmallLen = 0;
		$iSBcnt = 0;

		for ($i = 0; $i < count($raList); ++$i) {
			if ($raList[$i]->Type == PHPExcel_Shared_OLE::OLE_PPS_TYPE_FILE) {
				$raList[$i]->Size = $raList[$i]->_DataLen();

				if ($raList[$i]->Size < PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL) {
					$iSBcnt += floor($raList[$i]->Size / $this->_SMALL_BLOCK_SIZE) + ($raList[$i]->Size % $this->_SMALL_BLOCK_SIZE ? 1 : 0);
				}
				else {
					$iBBcnt += floor($raList[$i]->Size / $this->_BIG_BLOCK_SIZE) + ($raList[$i]->Size % $this->_BIG_BLOCK_SIZE ? 1 : 0);
				}
			}
		}

		$iSmallLen = $iSBcnt * $this->_SMALL_BLOCK_SIZE;
		$iSlCnt = floor($this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE);
		$iSBDcnt = floor($iSBcnt / $iSlCnt) + ($iSBcnt % $iSlCnt ? 1 : 0);
		$iBBcnt += floor($iSmallLen / $this->_BIG_BLOCK_SIZE) + ($iSmallLen % $this->_BIG_BLOCK_SIZE ? 1 : 0);
		$iCnt = count($raList);
		$iBdCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_PPS_SIZE;
		$iPPScnt = floor($iCnt / $iBdCnt) + ($iCnt % $iBdCnt ? 1 : 0);
		return array($iSBDcnt, $iBBcnt, $iPPScnt);
	}

	public function _adjust2($i2)
	{
		$iWk = log($i2) / log(2);
		return floor($iWk) < $iWk ? floor($iWk) + 1 : $iWk;
	}

	public function _saveHeader($iSBDcnt, $iBBcnt, $iPPScnt)
	{
		$FILE = $this->_FILEH_;
		$iBlCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
		$i1stBdL = ($this->_BIG_BLOCK_SIZE - 76) / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
		$iBdExL = 0;
		$iAll = $iBBcnt + $iPPScnt + $iSBDcnt;
		$iAllW = $iAll;
		$iBdCntW = floor($iAllW / $iBlCnt) + ($iAllW % $iBlCnt ? 1 : 0);
		$iBdCnt = floor(($iAll + $iBdCntW) / $iBlCnt) + (($iAllW + $iBdCntW) % $iBlCnt ? 1 : 0);

		if ($i1stBdL < $iBdCnt) {
			while (1) {
				++$iBdExL;
				++$iAllW;
				$iBdCntW = floor($iAllW / $iBlCnt) + ($iAllW % $iBlCnt ? 1 : 0);
				$iBdCnt = floor(($iAllW + $iBdCntW) / $iBlCnt) + (($iAllW + $iBdCntW) % $iBlCnt ? 1 : 0);

				if ($iBdCnt <= ($iBdExL * $iBlCnt) + $i1stBdL) {
					break;
				}
			}
		}

		fwrite($FILE, "\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1" . "\x00\x00\x00\x00" . "\x00\x00\x00\x00" . "\x00\x00\x00\x00" . "\x00\x00\x00\x00" . pack('v', 59) . pack('v', 3) . pack('v', -2) . pack('v', 9) . pack('v', 6) . pack('v', 0) . "\x00\x00\x00\x00" . "\x00\x00\x00\x00" . pack('V', $iBdCnt) . pack('V', $iBBcnt + $iSBDcnt) . pack('V', 0) . pack('V', 4096) . pack('V', $iSBDcnt ? 0 : -2) . pack('V', $iSBDcnt));

		if ($iBdCnt < $i1stBdL) {
			fwrite($FILE, pack('V', -2) . pack('V', 0));
		}
		else {
			fwrite($FILE, pack('V', $iAll + $iBdCnt) . pack('V', $iBdExL));
		}

		for ($i = 0; $i < $iBdCnt; ++$i) {
			fwrite($FILE, pack('V', $iAll + $i));
		}

		if ($i < $i1stBdL) {
			for ($j = 0; $j < ($i1stBdL - $i); ++$j) {
				fwrite($FILE, pack('V', -1));
			}
		}
	}

	public function _saveBigData($iStBlk, &$raList)
	{
		$FILE = $this->_FILEH_;

		for ($i = 0; $i < count($raList); ++$i) {
			if ($raList[$i]->Type != PHPExcel_Shared_OLE::OLE_PPS_TYPE_DIR) {
				$raList[$i]->Size = $raList[$i]->_DataLen();
				if ((PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL <= $raList[$i]->Size) || (($raList[$i]->Type == PHPExcel_Shared_OLE::OLE_PPS_TYPE_ROOT) && isset($raList[$i]->_data))) {
					fwrite($FILE, $raList[$i]->_data);

					if ($raList[$i]->Size % $this->_BIG_BLOCK_SIZE) {
						for ($j = 0; $j < ($this->_BIG_BLOCK_SIZE - ($raList[$i]->Size % $this->_BIG_BLOCK_SIZE)); ++$j) {
							fwrite($FILE, "\x00");
						}
					}

					$raList[$i]->_StartBlock = $iStBlk;
					$iStBlk += floor($raList[$i]->Size / $this->_BIG_BLOCK_SIZE) + ($raList[$i]->Size % $this->_BIG_BLOCK_SIZE ? 1 : 0);
				}
			}
		}
	}

	public function _makeSmallData(&$raList)
	{
		$sRes = '';
		$FILE = $this->_FILEH_;
		$iSmBlk = 0;

		for ($i = 0; $i < count($raList); ++$i) {
			if ($raList[$i]->Type == PHPExcel_Shared_OLE::OLE_PPS_TYPE_FILE) {
				if ($raList[$i]->Size <= 0) {
					continue;
				}

				if ($raList[$i]->Size < PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL) {
					$iSmbCnt = floor($raList[$i]->Size / $this->_SMALL_BLOCK_SIZE) + ($raList[$i]->Size % $this->_SMALL_BLOCK_SIZE ? 1 : 0);

					for ($j = 0; $j < ($iSmbCnt - 1); ++$j) {
						fwrite($FILE, pack('V', $j + $iSmBlk + 1));
					}

					fwrite($FILE, pack('V', -2));
					$sRes .= $raList[$i]->_data;

					if ($raList[$i]->Size % $this->_SMALL_BLOCK_SIZE) {
						for ($j = 0; $j < ($this->_SMALL_BLOCK_SIZE - ($raList[$i]->Size % $this->_SMALL_BLOCK_SIZE)); ++$j) {
							$sRes .= "\x00";
						}
					}

					$raList[$i]->_StartBlock = $iSmBlk;
					$iSmBlk += $iSmbCnt;
				}
			}
		}

		$iSbCnt = floor($this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE);

		if ($iSmBlk % $iSbCnt) {
			for ($i = 0; $i < ($iSbCnt - ($iSmBlk % $iSbCnt)); ++$i) {
				fwrite($FILE, pack('V', -1));
			}
		}

		return $sRes;
	}

	public function _savePps(&$raList)
	{
		for ($i = 0; $i < count($raList); ++$i) {
			fwrite($this->_FILEH_, $raList[$i]->_getPpsWk());
		}

		$iCnt = count($raList);
		$iBCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_PPS_SIZE;

		if ($iCnt % $iBCnt) {
			for ($i = 0; $i < (($iBCnt - ($iCnt % $iBCnt)) * PHPExcel_Shared_OLE::OLE_PPS_SIZE); ++$i) {
				fwrite($this->_FILEH_, "\x00");
			}
		}
	}

	public function _saveBbd($iSbdSize, $iBsize, $iPpsCnt)
	{
		$FILE = $this->_FILEH_;
		$iBbCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
		$i1stBdL = ($this->_BIG_BLOCK_SIZE - 76) / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
		$iBdExL = 0;
		$iAll = $iBsize + $iPpsCnt + $iSbdSize;
		$iAllW = $iAll;
		$iBdCntW = floor($iAllW / $iBbCnt) + ($iAllW % $iBbCnt ? 1 : 0);
		$iBdCnt = floor(($iAll + $iBdCntW) / $iBbCnt) + (($iAllW + $iBdCntW) % $iBbCnt ? 1 : 0);

		if ($i1stBdL < $iBdCnt) {
			while (1) {
				++$iBdExL;
				++$iAllW;
				$iBdCntW = floor($iAllW / $iBbCnt) + ($iAllW % $iBbCnt ? 1 : 0);
				$iBdCnt = floor(($iAllW + $iBdCntW) / $iBbCnt) + (($iAllW + $iBdCntW) % $iBbCnt ? 1 : 0);

				if ($iBdCnt <= ($iBdExL * $iBbCnt) + $i1stBdL) {
					break;
				}
			}
		}

		if (0 < $iSbdSize) {
			for ($i = 0; $i < ($iSbdSize - 1); ++$i) {
				fwrite($FILE, pack('V', $i + 1));
			}

			fwrite($FILE, pack('V', -2));
		}

		for ($i = 0; $i < ($iBsize - 1); ++$i) {
			fwrite($FILE, pack('V', $i + $iSbdSize + 1));
		}

		fwrite($FILE, pack('V', -2));

		for ($i = 0; $i < ($iPpsCnt - 1); ++$i) {
			fwrite($FILE, pack('V', $i + $iSbdSize + $iBsize + 1));
		}

		fwrite($FILE, pack('V', -2));

		for ($i = 0; $i < $iBdCnt; ++$i) {
			fwrite($FILE, pack('V', 4294967293));
		}

		for ($i = 0; $i < $iBdExL; ++$i) {
			fwrite($FILE, pack('V', 4294967292));
		}

		if (($iAllW + $iBdCnt) % $iBbCnt) {
			for ($i = 0; $i < ($iBbCnt - (($iAllW + $iBdCnt) % $iBbCnt)); ++$i) {
				fwrite($FILE, pack('V', -1));
			}
		}

		if ($i1stBdL < $iBdCnt) {
			$iN = 0;
			$iNb = 0;

			for ($i = $i1stBdL; $i < $iBdCnt; $i++, ++$iN) {
				if (($iBbCnt - 1) <= $iN) {
					$iN = 0;
					++$iNb;
					fwrite($FILE, pack('V', $iAll + $iBdCnt + $iNb));
				}

				fwrite($FILE, pack('V', $iBsize + $iSbdSize + $iPpsCnt + $i));
			}

			if (($iBdCnt - $i1stBdL) % ($iBbCnt - 1)) {
				for ($i = 0; $i < ($iBbCnt - 1 - (($iBdCnt - $i1stBdL) % ($iBbCnt - 1))); ++$i) {
					fwrite($FILE, pack('V', -1));
				}
			}

			fwrite($FILE, pack('V', -2));
		}
	}
}

?>

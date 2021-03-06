<?php

class PHPExcel_Reader_Excel5_Escher
{
	const DGGCONTAINER = 61440;
	const BSTORECONTAINER = 61441;
	const DGCONTAINER = 61442;
	const SPGRCONTAINER = 61443;
	const SPCONTAINER = 61444;
	const DGG = 61446;
	const BSE = 61447;
	const DG = 61448;
	const SPGR = 61449;
	const SP = 61450;
	const OPT = 61451;
	const CLIENTTEXTBOX = 61453;
	const CLIENTANCHOR = 61456;
	const CLIENTDATA = 61457;
	const BLIPJPEG = 61469;
	const BLIPPNG = 61470;
	const SPLITMENUCOLORS = 61726;
	const TERTIARYOPT = 61730;

	/**
	 * Escher stream data (binary)
	 *
	 * @var string
	 */
	private $_data;
	/**
	 * Size in bytes of the Escher stream data
	 *
	 * @var int
	 */
	private $_dataSize;
	/**
	 * Current position of stream pointer in Escher stream data
	 *
	 * @var int
	 */
	private $_pos;
	/**
	 * The object to be returned by the reader. Modified during load.
	 *
	 * @var mixed
	 */
	private $_object;

	public function __construct($object)
	{
		$this->_object = $object;
	}

	public function load($data)
	{
		$this->_data = $data;
		$this->_dataSize = strlen($this->_data);
		$this->_pos = 0;

		while ($this->_pos < $this->_dataSize) {
			$fbt = $this->_GetInt2d($this->_data, $this->_pos + 2);

			switch ($fbt) {
			case self::DGGCONTAINER:
				$this->_readDggContainer();
				break;

			case self::DGG:
				$this->_readDgg();
				break;

			case self::BSTORECONTAINER:
				$this->_readBstoreContainer();
				break;

			case self::BSE:
				$this->_readBSE();
				break;

			case self::BLIPJPEG:
				$this->_readBlipJPEG();
				break;

			case self::BLIPPNG:
				$this->_readBlipPNG();
				break;

			case self::OPT:
				$this->_readOPT();
				break;

			case self::TERTIARYOPT:
				$this->_readTertiaryOPT();
				break;

			case self::SPLITMENUCOLORS:
				$this->_readSplitMenuColors();
				break;

			case self::DGCONTAINER:
				$this->_readDgContainer();
				break;

			case self::DG:
				$this->_readDg();
				break;

			case self::SPGRCONTAINER:
				$this->_readSpgrContainer();
				break;

			case self::SPCONTAINER:
				$this->_readSpContainer();
				break;

			case self::SPGR:
				$this->_readSpgr();
				break;

			case self::SP:
				$this->_readSp();
				break;

			case self::CLIENTTEXTBOX:
				$this->_readClientTextbox();
				break;

			case self::CLIENTANCHOR:
				$this->_readClientAnchor();
				break;

			case self::CLIENTDATA:
				$this->_readClientData();
				break;

			default:
				$this->_readDefault();
				break;
			}
		}

		return $this->_object;
	}

	private function _readDefault()
	{
		$verInstance = $this->_GetInt2d($this->_data, $this->_pos);
		$fbt = $this->_GetInt2d($this->_data, $this->_pos + 2);
		$recVer = (15 & $verInstance) >> 0;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readDggContainer()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$dggContainer = new PHPExcel_Shared_Escher_DggContainer();
		$this->_object->setDggContainer($dggContainer);
		$reader = new PHPExcel_Reader_Excel5_Escher($dggContainer);
		$reader->load($recordData);
	}

	private function _readDgg()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readBstoreContainer()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$bstoreContainer = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer();
		$this->_object->setBstoreContainer($bstoreContainer);
		$reader = new PHPExcel_Reader_Excel5_Escher($bstoreContainer);
		$reader->load($recordData);
	}

	private function _readBSE()
	{
		$recInstance = (65520 & $this->_GetInt2d($this->_data, $this->_pos)) >> 4;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$BSE = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE();
		$this->_object->addBSE($BSE);
		$BSE->setBLIPType($recInstance);
		$btWin32 = ord($recordData[0]);
		$btMacOS = ord($recordData[1]);
		$rgbUid = substr($recordData, 2, 16);
		$tag = $this->_GetInt2d($recordData, 18);
		$size = $this->_GetInt4d($recordData, 20);
		$cRef = $this->_GetInt4d($recordData, 24);
		$foDelay = $this->_GetInt4d($recordData, 28);
		$unused1 = ord($recordData[32]);
		$cbName = ord($recordData[33]);
		$unused2 = ord($recordData[34]);
		$unused3 = ord($recordData[35]);
		$nameData = substr($recordData, 36, $cbName);
		$blipData = substr($recordData, 36 + $cbName);
		$reader = new PHPExcel_Reader_Excel5_Escher($BSE);
		$reader->load($blipData);
	}

	private function _readBlipJPEG()
	{
		$recInstance = (65520 & $this->_GetInt2d($this->_data, $this->_pos)) >> 4;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$pos = 0;
		$rgbUid1 = substr($recordData, 0, 16);
		$pos += 16;

		if (in_array($recInstance, array(1131, 1763))) {
			$rgbUid2 = substr($recordData, 16, 16);
			$pos += 16;
		}

		$tag = ord($recordData[$pos]);
		$pos += 1;
		$data = substr($recordData, $pos);
		$blip = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip();
		$blip->setData($data);
		$this->_object->setBlip($blip);
	}

	private function _readBlipPNG()
	{
		$recInstance = (65520 & $this->_GetInt2d($this->_data, $this->_pos)) >> 4;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$pos = 0;
		$rgbUid1 = substr($recordData, 0, 16);
		$pos += 16;

		if ($recInstance == 1761) {
			$rgbUid2 = substr($recordData, 16, 16);
			$pos += 16;
		}

		$tag = ord($recordData[$pos]);
		$pos += 1;
		$data = substr($recordData, $pos);
		$blip = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip();
		$blip->setData($data);
		$this->_object->setBlip($blip);
	}

	private function _readOPT()
	{
		$recInstance = (65520 & $this->_GetInt2d($this->_data, $this->_pos)) >> 4;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$this->_readOfficeArtRGFOPTE($recordData, $recInstance);
	}

	private function _readTertiaryOPT()
	{
		$recInstance = (65520 & $this->_GetInt2d($this->_data, $this->_pos)) >> 4;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readSplitMenuColors()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readDgContainer()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$dgContainer = new PHPExcel_Shared_Escher_DgContainer();
		$this->_object->setDgContainer($dgContainer);
		$reader = new PHPExcel_Reader_Excel5_Escher($dgContainer);
		$escher = $reader->load($recordData);
	}

	private function _readDg()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readSpgrContainer()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$spgrContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer();

		if ($this->_object instanceof PHPExcel_Shared_Escher_DgContainer) {
			$this->_object->setSpgrContainer($spgrContainer);
		}
		else {
			$this->_object->addChild($spgrContainer);
		}

		$reader = new PHPExcel_Reader_Excel5_Escher($spgrContainer);
		$escher = $reader->load($recordData);
	}

	private function _readSpContainer()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$spContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer();
		$this->_object->addChild($spContainer);
		$this->_pos += 8 + $length;
		$reader = new PHPExcel_Reader_Excel5_Escher($spContainer);
		$escher = $reader->load($recordData);
	}

	private function _readSpgr()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readSp()
	{
		$recInstance = (65520 & $this->_GetInt2d($this->_data, $this->_pos)) >> 4;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readClientTextbox()
	{
		$recInstance = (65520 & $this->_GetInt2d($this->_data, $this->_pos)) >> 4;
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readClientAnchor()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
		$c1 = $this->_GetInt2d($recordData, 2);
		$startOffsetX = $this->_GetInt2d($recordData, 4);
		$r1 = $this->_GetInt2d($recordData, 6);
		$startOffsetY = $this->_GetInt2d($recordData, 8);
		$c2 = $this->_GetInt2d($recordData, 10);
		$endOffsetX = $this->_GetInt2d($recordData, 12);
		$r2 = $this->_GetInt2d($recordData, 14);
		$endOffsetY = $this->_GetInt2d($recordData, 16);
		$this->_object->setStartCoordinates(PHPExcel_Cell::stringFromColumnIndex($c1) . ($r1 + 1));
		$this->_object->setStartOffsetX($startOffsetX);
		$this->_object->setStartOffsetY($startOffsetY);
		$this->_object->setEndCoordinates(PHPExcel_Cell::stringFromColumnIndex($c2) . ($r2 + 1));
		$this->_object->setEndOffsetX($endOffsetX);
		$this->_object->setEndOffsetY($endOffsetY);
	}

	private function _readClientData()
	{
		$length = $this->_GetInt4d($this->_data, $this->_pos + 4);
		$recordData = substr($this->_data, $this->_pos + 8, $length);
		$this->_pos += 8 + $length;
	}

	private function _readOfficeArtRGFOPTE($data, $n)
	{
		$splicedComplexData = substr($data, 6 * $n);

		for ($i = 0; $i < $n; ++$i) {
			$fopte = substr($data, 6 * $i, 6);
			$opid = $this->_GetInt2d($fopte, 0);
			$opidOpid = (16383 & $opid) >> 0;
			$opidFBid = (16384 & $opid) >> 14;
			$opidFComplex = (32768 & $opid) >> 15;
			$op = $this->_GetInt4d($fopte, 2);

			if ($opidFComplex) {
				$complexData = substr($splicedComplexData, 0, $op);
				$splicedComplexData = substr($splicedComplexData, $op);
				$value = $complexData;
			}
			else {
				$value = $op;
			}

			$this->_object->setOPT($opidOpid, $value);
		}
	}

	private function _GetInt2d($data, $pos)
	{
		return ord($data[$pos]) | (ord($data[$pos + 1]) << 8);
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


?>

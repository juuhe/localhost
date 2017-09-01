<?php

class PHPExcel_Cell
{
	/**
	 * Value binder to use
	 *
	 * @var PHPExcel_Cell_IValueBinder
	 */
	static private $_valueBinder;
	/**
	 * Column of the cell
	 *
	 * @var string
	 */
	private $_column;
	/**
	 * Row of the cell
	 *
	 * @var int
	 */
	private $_row;
	/**
	 * Value of the cell
	 *
	 * @var mixed
	 */
	private $_value;
	/**
	 * Calculated value of the cell (used for caching)
	 *
	 * @var mixed
	 */
	private $_calculatedValue;
	/**
	 * Type of the cell data
	 *
	 * @var string
	 */
	private $_dataType;
	/**
	 * Parent worksheet
	 *
	 * @var PHPExcel_Worksheet
	 */
	private $_parent;
	/**
	 * Index to cellXf
	 *
	 * @var int
	 */
	private $_xfIndex;

	public function notifyCacheController()
	{
		$this->_parent->getCellCacheController()->updateCacheData($this);
	}

	public function detach()
	{
		$this->_parent = NULL;
	}

	public function attach($parent)
	{
		$this->_parent = $parent;
	}

	public function __construct($pColumn = 'A', $pRow = 1, $pValue = NULL, $pDataType = NULL, PHPExcel_Worksheet $pSheet = NULL)
	{
		$this->_column = strtoupper($pColumn);
		$this->_row = $pRow;
		$this->_value = $pValue;
		$this->_parent = $pSheet;

		if (!is_null($pDataType)) {
			$this->_dataType = $pDataType;
		}
		else if (!self::getValueBinder()->bindValue($this, $pValue)) {
			throw new Exception('Value could not be bound to cell.');
		}

		$this->_xfIndex = 0;
	}

	public function getColumn()
	{
		return $this->_column;
	}

	public function getRow()
	{
		return $this->_row;
	}

	public function getCoordinate()
	{
		return $this->_column . $this->_row;
	}

	public function getValue()
	{
		return $this->_value;
	}

	public function setValue($pValue = NULL)
	{
		if (!self::getValueBinder()->bindValue($this, $pValue)) {
			throw new Exception('Value could not be bound to cell.');
		}

		return $this;
	}

	public function setValueExplicit($pValue = NULL, $pDataType = PHPExcel_Cell_DataType::TYPE_STRING)
	{
		switch ($pDataType) {
		case PHPExcel_Cell_DataType::TYPE_STRING:
		case PHPExcel_Cell_DataType::TYPE_NULL:
		case PHPExcel_Cell_DataType::TYPE_INLINE:
			$this->_value = PHPExcel_Cell_DataType::checkString($pValue);
			break;

		case PHPExcel_Cell_DataType::TYPE_NUMERIC:
			$this->_value = (double) $pValue;
			break;

		case PHPExcel_Cell_DataType::TYPE_FORMULA:
			$this->_value = (string) $pValue;
			break;

		case PHPExcel_Cell_DataType::TYPE_BOOL:
			$this->_value = (bool) $pValue;
			break;

		case PHPExcel_Cell_DataType::TYPE_ERROR:
			$this->_value = PHPExcel_Cell_DataType::checkErrorCode($pValue);
			break;

		default:
			throw new Exception('Invalid datatype: ' . $pDataType);
			break;
		}

		$this->_dataType = $pDataType;
		$this->notifyCacheController();
		return $this;
	}

	public function getCalculatedValue($resetLog = true)
	{
		if (!is_null($this->_calculatedValue) && ($this->_dataType == PHPExcel_Cell_DataType::TYPE_FORMULA)) {
			try {
				$result = PHPExcel_Calculation::getInstance()->calculateCellValue($this, $resetLog);
			}
			catch (Exception $ex) {
				$result = '#N/A';
				throw new Exception($ex->getMessage());
			}

			if (is_string($result) && ($result == '#Not Yet Implemented')) {
				return $this->_calculatedValue;
			}
			else {
				return $result;
			}
		}

		if (is_null($this->_value)) {
			return NULL;
		}
		else if ($this->_dataType != PHPExcel_Cell_DataType::TYPE_FORMULA) {
			return $this->_value;
		}
		else {
			return PHPExcel_Calculation::getInstance()->calculateCellValue($this, $resetLog);
		}
	}

	public function setCalculatedValue($pValue = NULL)
	{
		if (!is_null($pValue)) {
			$this->_calculatedValue = $pValue;
		}

		$this->notifyCacheController();
		return $this;
	}

	public function getOldCalculatedValue()
	{
		return $this->_calculatedValue;
	}

	public function getDataType()
	{
		return $this->_dataType;
	}

	public function setDataType($pDataType = PHPExcel_Cell_DataType::TYPE_STRING)
	{
		$this->_dataType = $pDataType;
		$this->notifyCacheController();
		return $this;
	}

	public function hasDataValidation()
	{
		if (!isset($this->_parent)) {
			throw new Exception('Cannot check for data validation when cell is not bound to a worksheet');
		}

		return $this->_parent->dataValidationExists($this->getCoordinate());
	}

	public function getDataValidation()
	{
		if (!isset($this->_parent)) {
			throw new Exception('Cannot get data validation for cell that is not bound to a worksheet');
		}

		$dataValidation = $this->_parent->getDataValidation($this->getCoordinate());
		return $dataValidation;
	}

	public function setDataValidation(PHPExcel_Cell_DataValidation $pDataValidation = NULL)
	{
		if (!isset($this->_parent)) {
			throw new Exception('Cannot set data validation for cell that is not bound to a worksheet');
		}

		$this->_parent->setDataValidation($this->getCoordinate(), $pDataValidation);
		$this->notifyCacheController();
		return $this;
	}

	public function hasHyperlink()
	{
		if (!isset($this->_parent)) {
			throw new Exception('Cannot check for hyperlink when cell is not bound to a worksheet');
		}

		return $this->_parent->hyperlinkExists($this->getCoordinate());
	}

	public function getHyperlink()
	{
		if (!isset($this->_parent)) {
			throw new Exception('Cannot get hyperlink for cell that is not bound to a worksheet');
		}

		$hyperlink = $this->_parent->getHyperlink($this->getCoordinate());
		return $hyperlink;
	}

	public function setHyperlink(PHPExcel_Cell_Hyperlink $pHyperlink = NULL)
	{
		if (!isset($this->_parent)) {
			throw new Exception('Cannot set hyperlink for cell that is not bound to a worksheet');
		}

		$this->_parent->setHyperlink($this->getCoordinate(), $pHyperlink);
		$this->notifyCacheController();
		return $this;
	}

	public function getParent()
	{
		return $this->_parent;
	}

	public function rebindParent(PHPExcel_Worksheet $parent)
	{
		$this->_parent = $parent;
		$this->notifyCacheController();
		return $this;
	}

	public function isInRange($pRange = 'A1:A1')
	{
		$pRange = strtoupper($pRange);
		$rangeA = '';
		$rangeB = '';

		if (strpos($pRange, ':') === false) {
			$rangeA = $pRange;
			$rangeB = $pRange;
		}
		else {
			list($rangeA, $rangeB) = explode(':', $pRange);
		}

		$rangeStart = PHPExcel_Cell::coordinateFromString($rangeA);
		$rangeEnd = PHPExcel_Cell::coordinateFromString($rangeB);
		$rangeStart[0] = PHPExcel_Cell::columnIndexFromString($rangeStart[0]) - 1;
		$rangeEnd[0] = PHPExcel_Cell::columnIndexFromString($rangeEnd[0]) - 1;
		$myColumn = PHPExcel_Cell::columnIndexFromString($this->getColumn()) - 1;
		$myRow = $this->getRow();
		return ($rangeStart[0] <= $myColumn) && ($myColumn <= $rangeEnd[0]) && ($rangeStart[1] <= $myRow) && ($myRow <= $rangeEnd[1]);
	}

	static public function coordinateFromString($pCoordinateString = 'A1')
	{
		if (strpos($pCoordinateString, ':') !== false) {
			throw new Exception('Cell coordinate string can not be a range of cells.');
		}
		else if ($pCoordinateString == '') {
			throw new Exception('Cell coordinate can not be zero-length string.');
		}
		else if (preg_match('/([$]?[A-Z]+)([$]?\\d+)/', $pCoordinateString, $matches)) {
			return array($column, $row);
		}
		else {
			throw new Exception('Invalid cell coordinate.');
		}
	}

	static public function absoluteCoordinate($pCoordinateString = 'A1')
	{
		if ((strpos($pCoordinateString, ':') === false) && (strpos($pCoordinateString, ',') === false)) {
			$returnValue = '';
			list($column, $row) = PHPExcel_Cell::coordinateFromString($pCoordinateString);
			$returnValue = '$' . $column . '$' . $row;
			return $returnValue;
		}
		else {
			throw new Exception('Coordinate string should not be a cell range.');
		}
	}

	static public function splitRange($pRange = 'A1:A1')
	{
		$exploded = explode(',', $pRange);

		for ($i = 0; $i < count($exploded); ++$i) {
			$exploded[$i] = explode(':', $exploded[$i]);
		}

		return $exploded;
	}

	static public function buildRange($pRange)
	{
		if (!is_array($pRange) || (count($pRange) == 0) || !is_array($pRange[0])) {
			throw new Exception('Range does not contain any information.');
		}

		$imploded = array();

		for ($i = 0; $i < count($pRange); ++$i) {
			$pRange[$i] = implode(':', $pRange[$i]);
		}

		$imploded = implode(',', $pRange);
		return $imploded;
	}

	static public function rangeDimension($pRange = 'A1:A1')
	{
		$pRange = strtoupper($pRange);
		$rangeA = '';
		$rangeB = '';

		if (strpos($pRange, ':') === false) {
			$rangeA = $pRange;
			$rangeB = $pRange;
		}
		else {
			list($rangeA, $rangeB) = explode(':', $pRange);
		}

		$rangeStart = PHPExcel_Cell::coordinateFromString($rangeA);
		$rangeEnd = PHPExcel_Cell::coordinateFromString($rangeB);
		$rangeStart[0] = PHPExcel_Cell::columnIndexFromString($rangeStart[0]);
		$rangeEnd[0] = PHPExcel_Cell::columnIndexFromString($rangeEnd[0]);
		return array(($rangeEnd[0] - $rangeStart[0]) + 1, ($rangeEnd[1] - $rangeStart[1]) + 1);
	}

	static public function getRangeBoundaries($pRange = 'A1:A1')
	{
		$pRange = strtoupper($pRange);
		$rangeA = '';
		$rangeB = '';

		if (strpos($pRange, ':') === false) {
			$rangeA = $pRange;
			$rangeB = $pRange;
		}
		else {
			list($rangeA, $rangeB) = explode(':', $pRange);
		}

		return array(self::coordinateFromString($rangeA), self::coordinateFromString($rangeB));
	}

	static public function columnIndexFromString($pString = 'A')
	{
		static $lookup = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13, 'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26);

		if (isset($lookup[$pString])) {
			return $lookup[$pString];
		}

		$pString = strtoupper($pString);
		$strLen = strlen($pString);

		if ($strLen == 1) {
			return ord($pString[0]) - 64;
		}
		else if ($strLen == 2) {
			return $result = ((1 + (ord($pString[0]) - 65)) * 26) + (ord($pString[1]) - 64);
		}
		else if ($strLen == 3) {
			return ((1 + (ord($pString[0]) - 65)) * 676) + ((1 + (ord($pString[1]) - 65)) * 26) + (ord($pString[2]) - 64);
		}
		else {
			throw new Exception('Column string index can not be ' . ($strLen != 0 ? 'longer than 3 characters' : 'empty') . '.');
		}
	}

	static public function stringFromColumnIndex($pColumnIndex = 0)
	{
		if ($pColumnIndex < 26) {
			return chr(65 + $pColumnIndex);
		}

		return PHPExcel_Cell::stringFromColumnIndex((int) ($pColumnIndex / 26) - 1) . chr(65 + ($pColumnIndex % 26));
	}

	static public function extractAllCellReferencesInRange($pRange = 'A1')
	{
		$returnValue = array();
		$aExplodeSpaces = explode(' ', str_replace('$', '', strtoupper($pRange)));

		foreach ($aExplodeSpaces as $explodedSpaces) {
			if ((strpos($explodedSpaces, ':') === false) && (strpos($explodedSpaces, ',') === false)) {
				$col = 'A';
				$row = 1;
				list($col, $row) = PHPExcel_Cell::coordinateFromString($explodedSpaces);

				if (strlen($col) <= 2) {
					$returnValue[] = $explodedSpaces;
				}

				continue;
			}

			$range = PHPExcel_Cell::splitRange($explodedSpaces);

			for ($i = 0; $i < count($range); ++$i) {
				if (count($range[$i]) == 1) {
					$col = 'A';
					$row = 1;
					list($col, $row) = PHPExcel_Cell::coordinateFromString($range[$i]);

					if (strlen($col) <= 2) {
						$returnValue[] = $explodedSpaces;
					}
				}

				$rangeStart = $rangeEnd = '';
				$startingCol = $startingRow = $endingCol = $endingRow = 0;
				list($rangeStart, $rangeEnd) = $range[$i];
				list($startingCol, $startingRow) = PHPExcel_Cell::coordinateFromString($rangeStart);
				list($endingCol, $endingRow) = PHPExcel_Cell::coordinateFromString($rangeEnd);
				$startingCol = PHPExcel_Cell::columnIndexFromString($startingCol);
				$endingCol = PHPExcel_Cell::columnIndexFromString($endingCol);
				$currentCol = --$startingCol;
				$currentRow = $startingRow;

				while ($currentCol < $endingCol) {
					$loopColumn = PHPExcel_Cell::stringFromColumnIndex($currentCol);

					while ($currentRow <= $endingRow) {
						$returnValue[] = $loopColumn . $currentRow;
						++$currentRow;
					}

					++$currentCol;
					$currentRow = $startingRow;
				}
			}
		}

		return $returnValue;
	}

	static public function compareCells(PHPExcel_Cell $a, PHPExcel_Cell $b)
	{
		if ($a->_row < $b->_row) {
			return -1;
		}
		else if ($b->_row < $a->_row) {
			return 1;
		}
		else if (PHPExcel_Cell::columnIndexFromString($a->_column) < PHPExcel_Cell::columnIndexFromString($b->_column)) {
			return -1;
		}
		else {
			return 1;
		}
	}

	static public function getValueBinder()
	{
		if (is_null(self::$_valueBinder)) {
			self::$_valueBinder = new PHPExcel_Cell_DefaultValueBinder();
		}

		return self::$_valueBinder;
	}

	static public function setValueBinder(PHPExcel_Cell_IValueBinder $binder = NULL)
	{
		if (is_null($binder)) {
			throw new Exception('A PHPExcel_Cell_IValueBinder is required for PHPExcel to function correctly.');
		}

		self::$_valueBinder = $binder;
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

	public function getXfIndex()
	{
		return $this->_xfIndex;
	}

	public function setXfIndex($pValue = 0)
	{
		$this->_xfIndex = $pValue;
		$this->notifyCacheController();
		return $this;
	}
}


?>

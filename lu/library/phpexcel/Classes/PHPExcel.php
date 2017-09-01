<?php

class PHPExcel
{
	/**
	 * Document properties
	 *
	 * @var PHPExcel_DocumentProperties
	 */
	private $_properties;
	/**
	 * Document security
	 *
	 * @var PHPExcel_DocumentSecurity
	 */
	private $_security;
	/**
	 * Collection of Worksheet objects
	 *
	 * @var PHPExcel_Worksheet[]
	 */
	private $_workSheetCollection = array();
	/**
	 * Active sheet index
	 *
	 * @var int
	 */
	private $_activeSheetIndex = 0;
	/**
	 * Named ranges
	 *
	 * @var PHPExcel_NamedRange[]
	 */
	private $_namedRanges = array();
	/**
	 * CellXf supervisor
	 *
	 * @var PHPExcel_Style
	 */
	private $_cellXfSupervisor;
	/**
	 * CellXf collection
	 *
	 * @var PHPExcel_Style[]
	 */
	private $_cellXfCollection = array();
	/**
	 * CellStyleXf collection
	 *
	 * @var PHPExcel_Style[]
	 */
	private $_cellStyleXfCollection = array();

	public function __construct()
	{
		$this->_workSheetCollection = array();
		$this->_workSheetCollection[] = new PHPExcel_Worksheet($this);
		$this->_activeSheetIndex = 0;
		$this->_properties = new PHPExcel_DocumentProperties();
		$this->_security = new PHPExcel_DocumentSecurity();
		$this->_namedRanges = array();
		$this->_cellXfSupervisor = new PHPExcel_Style(true);
		$this->_cellXfSupervisor->bindParent($this);
		$this->addCellXf(new PHPExcel_Style());
		$this->addCellStyleXf(new PHPExcel_Style());
	}

	public function disconnectWorksheets()
	{
		foreach ($this->_workSheetCollection as $k => &$worksheet) {
			$worksheet->disconnectCells();
			$this->_workSheetCollection[$k] = NULL;
		}

		unset($worksheet);
		$this->_workSheetCollection = array();
	}

	public function getProperties()
	{
		return $this->_properties;
	}

	public function setProperties(PHPExcel_DocumentProperties $pValue)
	{
		$this->_properties = $pValue;
	}

	public function getSecurity()
	{
		return $this->_security;
	}

	public function setSecurity(PHPExcel_DocumentSecurity $pValue)
	{
		$this->_security = $pValue;
	}

	public function getActiveSheet()
	{
		return $this->_workSheetCollection[$this->_activeSheetIndex];
	}

	public function createSheet($iSheetIndex = NULL)
	{
		$newSheet = new PHPExcel_Worksheet($this);
		$this->addSheet($newSheet, $iSheetIndex);
		return $newSheet;
	}

	public function addSheet(PHPExcel_Worksheet $pSheet = NULL, $iSheetIndex = NULL)
	{
		if (is_null($iSheetIndex)) {
			$this->_workSheetCollection[] = $pSheet;
		}
		else {
			array_splice($this->_workSheetCollection, $iSheetIndex, 0, array($pSheet));

			if ($iSheetIndex <= $this->_activeSheetIndex) {
				++$this->_activeSheetIndex;
			}
		}

		return $pSheet;
	}

	public function removeSheetByIndex($pIndex = 0)
	{
		if ((count($this->_workSheetCollection) - 1) < $pIndex) {
			throw new Exception('Sheet index is out of bounds.');
		}
		else {
			array_splice($this->_workSheetCollection, $pIndex, 1);
		}
	}

	public function getSheet($pIndex = 0)
	{
		if ((count($this->_workSheetCollection) - 1) < $pIndex) {
			throw new Exception('Sheet index is out of bounds.');
		}
		else {
			return $this->_workSheetCollection[$pIndex];
		}
	}

	public function getAllSheets()
	{
		return $this->_workSheetCollection;
	}

	public function getSheetByName($pName = '')
	{
		$worksheetCount = count($this->_workSheetCollection);

		for ($i = 0; $i < $worksheetCount; ++$i) {
			if ($this->_workSheetCollection[$i]->getTitle() == $pName) {
				return $this->_workSheetCollection[$i];
			}
		}

		return NULL;
	}

	public function getIndex(PHPExcel_Worksheet $pSheet)
	{
		foreach ($this->_workSheetCollection as $key => $value) {
			if ($value->getHashCode() == $pSheet->getHashCode()) {
				return $key;
			}
		}
	}

	public function setIndexByName($sheetName, $newIndex)
	{
		$oldIndex = $this->getIndex($this->getSheetByName($sheetName));
		$pSheet = array_splice($this->_workSheetCollection, $oldIndex, 1);
		array_splice($this->_workSheetCollection, $newIndex, 0, $pSheet);
		return $newIndex;
	}

	public function getSheetCount()
	{
		return count($this->_workSheetCollection);
	}

	public function getActiveSheetIndex()
	{
		return $this->_activeSheetIndex;
	}

	public function setActiveSheetIndex($pIndex = 0)
	{
		if ((count($this->_workSheetCollection) - 1) < $pIndex) {
			throw new Exception('Active sheet index is out of bounds.');
		}
		else {
			$this->_activeSheetIndex = $pIndex;
		}

		return $this->getActiveSheet();
	}

	public function setActiveSheetIndexByName($pValue = '')
	{
		if ($worksheet = $this->getSheetByName($pValue) instanceof PHPExcel_Worksheet) {
			$this->setActiveSheetIndex($worksheet->getParent()->getIndex($worksheet));
			return $worksheet;
		}

		throw new Exception('Workbook does not contain sheet:' . $pValue);
	}

	public function getSheetNames()
	{
		$returnValue = array();
		$worksheetCount = $this->getSheetCount();

		for ($i = 0; $i < $worksheetCount; ++$i) {
			array_push($returnValue, $this->getSheet($i)->getTitle());
		}

		return $returnValue;
	}

	public function addExternalSheet(PHPExcel_Worksheet $pSheet, $iSheetIndex = NULL)
	{
		if (!is_null($this->getSheetByName($pSheet->getTitle()))) {
			throw new Exception('Workbook already contains a worksheet named \'' . $pSheet->getTitle() . '\'. Rename the external sheet first.');
		}

		$countCellXfs = count($this->_cellXfCollection);

		foreach ($pSheet->getParent()->getCellXfCollection() as $cellXf) {
			$this->addCellXf(clone $cellXf);
		}

		$pSheet->rebindParent($this);

		foreach ($pSheet->getCellCollection(false) as $cellID) {
			$cell = $sheet->getCell($cellID);
			$cell->setXfIndex($cell->getXfIndex() + $countCellXfs);
		}

		return $this->addSheet($pSheet, $iSheetIndex);
	}

	public function getNamedRanges()
	{
		return $this->_namedRanges;
	}

	public function addNamedRange(PHPExcel_NamedRange $namedRange)
	{
		if ($namedRange->getScope() == NULL) {
			$this->_namedRanges[$namedRange->getName()] = $namedRange;
		}
		else {
			$this->_namedRanges[$namedRange->getScope()->getTitle() . '!' . $namedRange->getName()] = $namedRange;
		}

		return true;
	}

	public function getNamedRange($namedRange, PHPExcel_Worksheet $pSheet = NULL)
	{
		$returnValue = NULL;
		if (($namedRange != '') && !is_null($namedRange)) {
			if (isset($this->_namedRanges[$namedRange])) {
				$returnValue = $this->_namedRanges[$namedRange];
			}

			if (!is_null($pSheet) && isset($this->_namedRanges[$pSheet->getTitle() . '!' . $namedRange])) {
				$returnValue = $this->_namedRanges[$pSheet->getTitle() . '!' . $namedRange];
			}
		}

		return $returnValue;
	}

	public function removeNamedRange($namedRange, PHPExcel_Worksheet $pSheet = NULL)
	{
		if (is_null($pSheet)) {
			if (isset($this->_namedRanges[$namedRange])) {
				unset($this->_namedRanges[$namedRange]);
			}
		}
		else if (isset($this->_namedRanges[$pSheet->getTitle() . '!' . $namedRange])) {
			unset($this->_namedRanges[$pSheet->getTitle() . '!' . $namedRange]);
		}

		return $this;
	}

	public function getWorksheetIterator()
	{
		return new PHPExcel_WorksheetIterator($this);
	}

	public function copy()
	{
		$copied = clone $this;
		$worksheetCount = count($this->_workSheetCollection);

		for ($i = 0; $i < $worksheetCount; ++$i) {
			$this->_workSheetCollection[$i] = $this->_workSheetCollection[$i]->copy();
			$this->_workSheetCollection[$i]->rebindParent($this);
		}

		return $copied;
	}

	public function __clone()
	{
		foreach ($this as $key => $val) {
			if (is_object($val) || is_array($val)) {
				$this->$key = unserialize(serialize($val));
			}
		}
	}

	public function getCellXfCollection()
	{
		return $this->_cellXfCollection;
	}

	public function getCellXfByIndex($pIndex = 0)
	{
		return $this->_cellXfCollection[$pIndex];
	}

	public function getCellXfByHashCode($pValue = '')
	{
		foreach ($this->_cellXfCollection as $cellXf) {
			if ($cellXf->getHashCode() == $pValue) {
				return $cellXf;
			}
		}

		return false;
	}

	public function getDefaultStyle()
	{
		if (isset($this->_cellXfCollection[0])) {
			return $this->_cellXfCollection[0];
		}

		throw new Exception('No default style found for this workbook');
	}

	public function addCellXf(PHPExcel_Style $style)
	{
		$this->_cellXfCollection[] = $style;
		$style->setIndex(count($this->_cellXfCollection) - 1);
	}

	public function removeCellXfByIndex($pIndex = 0)
	{
		if ((count($this->_cellXfCollection) - 1) < $pIndex) {
			throw new Exception('CellXf index is out of bounds.');
		}
		else {
			array_splice($this->_cellXfCollection, $pIndex, 1);

			foreach ($this->_workSheetCollection as $worksheet) {
				foreach ($worksheet->getCellCollection(false) as $cellID) {
					$cell = $sheet->getCell($cellID);
					$xfIndex = $cell->getXfIndex();

					if ($pIndex < $xfIndex) {
						$cell->setXfIndex($xfIndex - 1);
					}
					else if ($xfIndex == $pIndex) {
						$cell->setXfIndex(0);
					}
				}
			}
		}
	}

	public function getCellXfSupervisor()
	{
		return $this->_cellXfSupervisor;
	}

	public function getCellStyleXfCollection()
	{
		return $this->_cellStyleXfCollection;
	}

	public function getCellStyleXfByIndex($pIndex = 0)
	{
		return $this->_cellStyleXfCollection[$pIndex];
	}

	public function getCellStyleXfByHashCode($pValue = '')
	{
		foreach ($this->_cellXfStyleCollection as $cellStyleXf) {
			if ($cellStyleXf->getHashCode() == $pValue) {
				return $cellStyleXf;
			}
		}

		return false;
	}

	public function addCellStyleXf(PHPExcel_Style $pStyle)
	{
		$this->_cellStyleXfCollection[] = $pStyle;
		$pStyle->setIndex(count($this->_cellStyleXfCollection) - 1);
	}

	public function removeCellStyleXfByIndex($pIndex = 0)
	{
		if ((count($this->_cellStyleXfCollection) - 1) < $pIndex) {
			throw new Exception('CellStyleXf index is out of bounds.');
		}
		else {
			array_splice($this->_cellStyleXfCollection, $pIndex, 1);
		}
	}

	public function garbageCollect()
	{
		$countReferencesCellXf = array();

		foreach ($this->_cellXfCollection as $index => $cellXf) {
			$countReferencesCellXf[$index] = 0;
		}

		foreach ($this->getWorksheetIterator() as $sheet) {
			foreach ($sheet->getCellCollection(false) as $cellID) {
				$cell = $sheet->getCell($cellID);
				++$countReferencesCellXf[$cell->getXfIndex()];
			}

			foreach ($sheet->getRowDimensions() as $rowDimension) {
				if ($rowDimension->getXfIndex() !== NULL) {
					++$countReferencesCellXf[$rowDimension->getXfIndex()];
				}
			}

			foreach ($sheet->getColumnDimensions() as $columnDimension) {
				++$countReferencesCellXf[$columnDimension->getXfIndex()];
			}
		}

		$countNeededCellXfs = 0;

		foreach ($this->_cellXfCollection as $index => $cellXf) {
			if ((0 < $countReferencesCellXf[$index]) || ($index == 0)) {
				++$countNeededCellXfs;
			}
			else {
				unset($this->_cellXfCollection[$index]);
			}

			$map[$index] = $countNeededCellXfs - 1;
		}

		$this->_cellXfCollection = array_values($this->_cellXfCollection);

		foreach ($this->_cellXfCollection as $i => $cellXf) {
			$cellXf->setIndex($i);
		}

		if (count($this->_cellXfCollection) == 0) {
			$this->_cellXfCollection[] = new PHPExcel_Style();
		}

		foreach ($this->getWorksheetIterator() as $sheet) {
			foreach ($sheet->getCellCollection(false) as $cellID) {
				$cell = $sheet->getCell($cellID);
				$cell->setXfIndex($map[$cell->getXfIndex()]);
			}

			foreach ($sheet->getRowDimensions() as $rowDimension) {
				if ($rowDimension->getXfIndex() !== NULL) {
					$rowDimension->setXfIndex($map[$rowDimension->getXfIndex()]);
				}
			}

			foreach ($sheet->getColumnDimensions() as $columnDimension) {
				$columnDimension->setXfIndex($map[$columnDimension->getXfIndex()]);
			}
		}

		foreach ($this->getWorksheetIterator() as $sheet) {
			$sheet->garbageCollect();
		}
	}
}

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
	PHPExcel_Autoloader::Register();
	PHPExcel_Shared_ZipStreamWrapper::register();

	if (ini_get('mbstring.func_overload') & 2) {
		throw new Exception('Multibyte function overloading in PHP must be disabled for string functions (2).');
	}
}

?>

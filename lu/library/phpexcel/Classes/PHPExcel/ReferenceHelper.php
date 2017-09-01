<?php

class PHPExcel_ReferenceHelper
{
	/**
	 * Instance of this class
	 *
	 * @var PHPExcel_ReferenceHelper
	 */
	static private $_instance;

	static public function getInstance()
	{
		if (!isset(self::$_instance) || is_null(self::$_instance)) {
			self::$_instance = new PHPExcel_ReferenceHelper();
		}

		return self::$_instance;
	}

	protected function __construct()
	{
	}

	public function insertNewBefore($pBefore = 'A1', $pNumCols = 0, $pNumRows = 0, PHPExcel_Worksheet $pSheet = NULL)
	{
		$aCellCollection = $pSheet->getCellCollection();
		$beforeColumn = 'A';
		$beforeRow = 1;
		list($beforeColumn, $beforeRow) = PHPExcel_Cell::coordinateFromString($pBefore);
		$highestColumn = $pSheet->getHighestColumn();
		$highestRow = $pSheet->getHighestRow();
		if (($pNumCols < 0) && (0 < ((PHPExcel_Cell::columnIndexFromString($beforeColumn) - 2) + $pNumCols))) {
			for ($i = 1; $i <= $highestRow - 1; ++$i) {
				for ($j = (PHPExcel_Cell::columnIndexFromString($beforeColumn) - 1) + $pNumCols; $j <= PHPExcel_Cell::columnIndexFromString($beforeColumn) - 2; ++$j) {
					$coordinate = PHPExcel_Cell::stringFromColumnIndex($j) . $i;
					$pSheet->removeConditionalStyles($coordinate);

					if ($pSheet->cellExists($coordinate)) {
						$pSheet->getCell($coordinate)->setValueExplicit('', PHPExcel_Cell_DataType::TYPE_NULL);
						$pSheet->getCell($coordinate)->setXfIndex(0);
					}
				}
			}
		}

		if (($pNumRows < 0) && (0 < (($beforeRow - 1) + $pNumRows))) {
			for ($i = PHPExcel_Cell::columnIndexFromString($beforeColumn) - 1; $i <= PHPExcel_Cell::columnIndexFromString($highestColumn) - 1; ++$i) {
				for ($j = $beforeRow + $pNumRows; $j <= $beforeRow - 1; ++$j) {
					$coordinate = PHPExcel_Cell::stringFromColumnIndex($i) . $j;
					$pSheet->removeConditionalStyles($coordinate);

					if ($pSheet->cellExists($coordinate)) {
						$pSheet->getCell($coordinate)->setValueExplicit('', PHPExcel_Cell_DataType::TYPE_NULL);
						$pSheet->getCell($coordinate)->setXfIndex(0);
					}
				}
			}
		}

		while ($cellID = array_pop($aCellCollection)) {
			$cell = $pSheet->getCell($cellID);
			$newCoordinates = PHPExcel_Cell::stringFromColumnIndex((PHPExcel_Cell::columnIndexFromString($cell->getColumn()) - 1) + $pNumCols) . ($cell->getRow() + $pNumRows);
			if ((PHPExcel_Cell::columnIndexFromString($beforeColumn) <= PHPExcel_Cell::columnIndexFromString($cell->getColumn())) && ($beforeRow <= $cell->getRow())) {
				$pSheet->getCell($newCoordinates)->setXfIndex($cell->getXfIndex());
				$cell->setXfIndex(0);

				if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
					$pSheet->getCell($newCoordinates)->setValue($this->updateFormulaReferences($cell->getValue(), $pBefore, $pNumCols, $pNumRows));
				}
				else {
					$pSheet->getCell($newCoordinates)->setValue($cell->getValue());
				}

				$pSheet->getCell($cell->getCoordinate())->setValue('');
			}
		}

		$highestColumn = $pSheet->getHighestColumn();
		$highestRow = $pSheet->getHighestRow();
		if ((0 < $pNumCols) && (0 < (PHPExcel_Cell::columnIndexFromString($beforeColumn) - 2))) {
			for ($i = $beforeRow; $i <= $highestRow - 1; ++$i) {
				$coordinate = PHPExcel_Cell::stringFromColumnIndex(PHPExcel_Cell::columnIndexFromString($beforeColumn) - 2) . $i;

				if ($pSheet->cellExists($coordinate)) {
					$xfIndex = $pSheet->getCell($coordinate)->getXfIndex();
					$conditionalStyles = ($pSheet->conditionalStylesExists($coordinate) ? $pSheet->getConditionalStyles($coordinate) : false);

					for ($j = PHPExcel_Cell::columnIndexFromString($beforeColumn) - 1; $j <= (PHPExcel_Cell::columnIndexFromString($beforeColumn) - 2) + $pNumCols; ++$j) {
						$pSheet->getCellByColumnAndRow($j, $i)->setXfIndex($xfIndex);

						if ($conditionalStyles) {
							$cloned = array();

							foreach ($conditionalStyles as $conditionalStyle) {
								$cloned[] = clone $conditionalStyle;
							}

							$pSheet->setConditionalStyles(PHPExcel_Cell::stringFromColumnIndex($j) . $i, $cloned);
						}
					}
				}
			}
		}

		if ((0 < $pNumRows) && (0 < ($beforeRow - 1))) {
			for ($i = PHPExcel_Cell::columnIndexFromString($beforeColumn) - 1; $i <= PHPExcel_Cell::columnIndexFromString($highestColumn) - 1; ++$i) {
				$coordinate = PHPExcel_Cell::stringFromColumnIndex($i) . ($beforeRow - 1);

				if ($pSheet->cellExists($coordinate)) {
					$xfIndex = $pSheet->getCell($coordinate)->getXfIndex();
					$conditionalStyles = ($pSheet->conditionalStylesExists($coordinate) ? $pSheet->getConditionalStyles($coordinate) : false);

					for ($j = $beforeRow; $j <= ($beforeRow - 1) + $pNumRows; ++$j) {
						$pSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($i) . $j)->setXfIndex($xfIndex);

						if ($conditionalStyles) {
							$cloned = array();

							foreach ($conditionalStyles as $conditionalStyle) {
								$cloned[] = clone $conditionalStyle;
							}

							$pSheet->setConditionalStyles(PHPExcel_Cell::stringFromColumnIndex($i) . $j, $cloned);
						}
					}
				}
			}
		}

		$aColumnDimensions = array_reverse($pSheet->getColumnDimensions(), true);

		if (0 < count($aColumnDimensions)) {
			foreach ($aColumnDimensions as $objColumnDimension) {
				$newReference = $this->updateCellReference($objColumnDimension->getColumnIndex() . '1', $pBefore, $pNumCols, $pNumRows);
				list($newReference) = PHPExcel_Cell::coordinateFromString($newReference);

				if ($objColumnDimension->getColumnIndex() != $newReference) {
					$objColumnDimension->setColumnIndex($newReference);
				}
			}

			$pSheet->refreshColumnDimensions();
		}

		$aRowDimensions = array_reverse($pSheet->getRowDimensions(), true);

		if (0 < count($aRowDimensions)) {
			foreach ($aRowDimensions as $objRowDimension) {
				$newReference = $this->updateCellReference('A' . $objRowDimension->getRowIndex(), $pBefore, $pNumCols, $pNumRows);
				list(, $newReference) = PHPExcel_Cell::coordinateFromString($newReference);

				if ($objRowDimension->getRowIndex() != $newReference) {
					$objRowDimension->setRowIndex($newReference);
				}
			}

			$pSheet->refreshRowDimensions();
			$copyDimension = $pSheet->getRowDimension($beforeRow - 1);

			for ($i = $beforeRow; $i <= ($beforeRow - 1) + $pNumRows; ++$i) {
				$newDimension = $pSheet->getRowDimension($i);
				$newDimension->setRowHeight($copyDimension->getRowHeight());
				$newDimension->setVisible($copyDimension->getVisible());
				$newDimension->setOutlineLevel($copyDimension->getOutlineLevel());
				$newDimension->setCollapsed($copyDimension->getCollapsed());
			}
		}

		$aBreaks = array_reverse($pSheet->getBreaks(), true);

		foreach ($aBreaks as $key => $value) {
			$newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);

			if ($key != $newReference) {
				$pSheet->setBreak($newReference, $value);
				$pSheet->setBreak($key, PHPExcel_Worksheet::BREAK_NONE);
			}
		}

		$aHyperlinkCollection = array_reverse($pSheet->getHyperlinkCollection(), true);

		foreach ($aHyperlinkCollection as $key => $value) {
			$newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);

			if ($key != $newReference) {
				$pSheet->setHyperlink($newReference, $value);
				$pSheet->setHyperlink($key, NULL);
			}
		}

		$aDataValidationCollection = array_reverse($pSheet->getDataValidationCollection(), true);

		foreach ($aDataValidationCollection as $key => $value) {
			$newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);

			if ($key != $newReference) {
				$pSheet->setDataValidation($newReference, $value);
				$pSheet->setDataValidation($key, NULL);
			}
		}

		$aMergeCells = $pSheet->getMergeCells();
		$aNewMergeCells = array();

		foreach ($aMergeCells as $key => &$value) {
			$newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);
			$aNewMergeCells[$newReference] = $newReference;
		}

		$pSheet->setMergeCells($aNewMergeCells);
		$aProtectedCells = array_reverse($pSheet->getProtectedCells(), true);

		foreach ($aProtectedCells as $key => $value) {
			$newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);

			if ($key != $newReference) {
				$pSheet->protectCells($newReference, $value, true);
				$pSheet->unprotectCells($key);
			}
		}

		if ($pSheet->getAutoFilter() != '') {
			$pSheet->setAutoFilter($this->updateCellReference($pSheet->getAutoFilter(), $pBefore, $pNumCols, $pNumRows));
		}

		if ($pSheet->getFreezePane() != '') {
			$pSheet->freezePane($this->updateCellReference($pSheet->getFreezePane(), $pBefore, $pNumCols, $pNumRows));
		}

		if ($pSheet->getPageSetup()->isPrintAreaSet()) {
			$pSheet->getPageSetup()->setPrintArea($this->updateCellReference($pSheet->getPageSetup()->getPrintArea(), $pBefore, $pNumCols, $pNumRows));
		}

		$aDrawings = $pSheet->getDrawingCollection();

		foreach ($aDrawings as $objDrawing) {
			$newReference = $this->updateCellReference($objDrawing->getCoordinates(), $pBefore, $pNumCols, $pNumRows);

			if ($objDrawing->getCoordinates() != $newReference) {
				$objDrawing->setCoordinates($newReference);
			}
		}

		if (0 < count($pSheet->getParent()->getNamedRanges())) {
			foreach ($pSheet->getParent()->getNamedRanges() as $namedRange) {
				if ($namedRange->getWorksheet()->getHashCode() == $pSheet->getHashCode()) {
					$namedRange->setRange($this->updateCellReference($namedRange->getRange(), $pBefore, $pNumCols, $pNumRows));
				}
			}
		}

		$pSheet->garbageCollect();
	}

	public function updateFormulaReferences($pFormula = '', $pBefore = 'A1', $pNumCols = 0, $pNumRows = 0)
	{
		$tokenisedFormula = PHPExcel_Calculation::getInstance()->parseFormula($pFormula);
		$newCellTokens = $cellTokens = array();
		$adjustCount = 0;

		foreach ($tokenisedFormula as $token) {
			$token = $token['value'];

			if (preg_match('/^' . PHPExcel_Calculation::CALCULATION_REGEXP_CELLREF . '$/i', $token, $matches)) {
				list($column, $row) = PHPExcel_Cell::coordinateFromString($token);
				$column = PHPExcel_Cell::columnIndexFromString($column) + 100000;
				$row += 10000000;
				$cellIndex = $column . $row;

				if (!isset($cellTokens[$cellIndex])) {
					$newReference = $this->updateCellReference($token, $pBefore, $pNumCols, $pNumRows);

					if ($newReference !== $token) {
						$newCellTokens[$cellIndex] = preg_quote($newReference);
						$cellTokens[$cellIndex] = '/(?<![A-Z])' . preg_quote($token) . '(?!\\d)/i';
						++$adjustCount;
					}
				}
			}
		}

		if ($adjustCount == 0) {
			return $pFormula;
		}

		krsort($cellTokens);
		krsort($newCellTokens);
		$formulaBlocks = explode('"', $pFormula);

		foreach ($formulaBlocks as $i => &$formulaBlock) {
			if (($i % 2) == 0) {
				$formulaBlock = preg_replace($cellTokens, $newCellTokens, $formulaBlock);
			}
		}

		unset($formulaBlock);
		return implode('"', $formulaBlocks);
	}

	public function updateCellReference($pCellRange = 'A1', $pBefore = 'A1', $pNumCols = 0, $pNumRows = 0)
	{
		if (strpos($pCellRange, '!') !== false) {
			return $pCellRange;
		}
		else {
			if ((strpos($pCellRange, ':') === false) && (strpos($pCellRange, ',') === false)) {
				return $this->_updateSingleCellReference($pCellRange, $pBefore, $pNumCols, $pNumRows);
			}
			else {
				if ((strpos($pCellRange, ':') !== false) || (strpos($pCellRange, ',') !== false)) {
					return $this->_updateCellRange($pCellRange, $pBefore, $pNumCols, $pNumRows);
				}
				else {
					return $pCellRange;
				}
			}
		}
	}

	public function updateNamedFormulas(PHPExcel $pPhpExcel, $oldName = '', $newName = '')
	{
		if ($oldName == '') {
			return NULL;
		}

		foreach ($pPhpExcel->getWorksheetIterator() as $sheet) {
			foreach ($sheet->getCellCollection(false) as $cellID) {
				$cell = $sheet->getCell($cellID);
				if (!is_null($cell) && ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA)) {
					$formula = $cell->getValue();

					if (strpos($formula, $oldName) !== false) {
						$formula = str_replace('\'' . $oldName . '\'!', '\'' . $newName . '\'!', $formula);
						$formula = str_replace($oldName . '!', $newName . '!', $formula);
						$cell->setValueExplicit($formula, PHPExcel_Cell_DataType::TYPE_FORMULA);
					}
				}
			}
		}
	}

	private function _updateCellRange($pCellRange = 'A1:A1', $pBefore = 'A1', $pNumCols = 0, $pNumRows = 0)
	{
		if ((strpos($pCellRange, ':') !== false) || (strpos($pCellRange, ',') !== false)) {
			$range = PHPExcel_Cell::splitRange($pCellRange);

			for ($i = 0; $i < count($range); ++$i) {
				for ($j = 0; $j < count($range[$i]); ++$j) {
					$range[$i][$j] = $this->_updateSingleCellReference($range[$i][$j], $pBefore, $pNumCols, $pNumRows);
				}
			}

			return PHPExcel_Cell::buildRange($range);
		}
		else {
			throw new Exception('Only cell ranges may be passed to this method.');
		}
	}

	private function _updateSingleCellReference($pCellReference = 'A1', $pBefore = 'A1', $pNumCols = 0, $pNumRows = 0)
	{
		if ((strpos($pCellReference, ':') === false) && (strpos($pCellReference, ',') === false)) {
			$beforeColumn = 'A';
			$beforeRow = 1;
			list($beforeColumn, $beforeRow) = PHPExcel_Cell::coordinateFromString($pBefore);
			$newColumn = 'A';
			$newRow = 1;
			list($newColumn, $newRow) = PHPExcel_Cell::coordinateFromString($pCellReference);
			if (($newColumn == '') && ($newRow == '')) {
				return $pCellReference;
			}

			$updateColumn = (PHPExcel_Cell::columnIndexFromString($beforeColumn) <= PHPExcel_Cell::columnIndexFromString($newColumn)) && (strpos($newColumn, '$') === false) && (strpos($beforeColumn, '$') === false);
			$updateRow = ($beforeRow <= $newRow) && (strpos($newRow, '$') === false) && (strpos($beforeRow, '$') === false);

			if ($updateColumn) {
				$newColumn = PHPExcel_Cell::stringFromColumnIndex((PHPExcel_Cell::columnIndexFromString($newColumn) - 1) + $pNumCols);
			}

			if ($updateRow) {
				$newRow = $newRow + $pNumRows;
			}

			return $newColumn . $newRow;
		}
		else {
			throw new Exception('Only single cell references may be passed to this method.');
		}
	}

	final public function __clone()
	{
		throw new Exception('Cloning a Singleton is not allowed!');
	}
}


?>

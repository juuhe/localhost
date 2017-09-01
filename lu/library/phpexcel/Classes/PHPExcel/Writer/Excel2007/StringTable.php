<?php

class PHPExcel_Writer_Excel2007_StringTable extends PHPExcel_Writer_Excel2007_WriterPart
{
	public function createStringTable($pSheet = NULL, $pExistingTable = NULL)
	{
		if (!is_null($pSheet)) {
			$aStringTable = array();
			$cellCollection = NULL;
			$aFlippedStringTable = NULL;
			if (!is_null($pExistingTable) && is_array($pExistingTable)) {
				$aStringTable = $pExistingTable;
			}

			$aFlippedStringTable = $this->flipStringTable($aStringTable);

			foreach ($pSheet->getCellCollection() as $cellID) {
				$cell = $pSheet->getCell($cellID);
				if (!is_object($cell->getValue()) && !isset($aFlippedStringTable[$cell->getValue()]) && !is_null($cell->getValue()) && ($cell->getValue() !== '') && (($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_STRING) || ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_NULL))) {
					$aStringTable[] = $cell->getValue();
					$aFlippedStringTable[$cell->getValue()] = 1;
				}
				else {
					if ($cell->getValue() instanceof PHPExcel_RichText && !isset($aFlippedStringTable[$cell->getValue()->getHashCode()]) && !is_null($cell->getValue())) {
						$aStringTable[] = $cell->getValue();
						$aFlippedStringTable[$cell->getValue()->getHashCode()] = 1;
					}
				}
			}

			return $aStringTable;
		}
		else {
			throw new Exception('Invalid PHPExcel_Worksheet object passed.');
		}
	}

	public function writeStringTable($pStringTable = NULL)
	{
		if (!is_null($pStringTable)) {
			$objWriter = NULL;

			if ($this->getParentWriter()->getUseDiskCaching()) {
				$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
			}
			else {
				$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
			}

			$objWriter->startDocument('1.0', 'UTF-8', 'yes');
			$objWriter->startElement('sst');
			$objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
			$objWriter->writeAttribute('uniqueCount', count($pStringTable));

			foreach ($pStringTable as $textElement) {
				$objWriter->startElement('si');

				if (!$textElement instanceof PHPExcel_RichText) {
					$textToWrite = PHPExcel_Shared_String::ControlCharacterPHP2OOXML($textElement);
					$objWriter->startElement('t');

					if ($textToWrite !== trim($textToWrite)) {
						$objWriter->writeAttribute('xml:space', 'preserve');
					}

					$objWriter->writeRaw($textToWrite);
					$objWriter->endElement();
				}
				else if ($textElement instanceof PHPExcel_RichText) {
					$this->writeRichText($objWriter, $textElement);
				}

				$objWriter->endElement();
			}

			$objWriter->endElement();
			return $objWriter->getData();
		}
		else {
			throw new Exception('Invalid string table array passed.');
		}
	}

	public function writeRichText(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_RichText $pRichText = NULL)
	{
		$elements = $pRichText->getRichTextElements();

		foreach ($elements as $element) {
			$objWriter->startElement('r');

			if ($element instanceof PHPExcel_RichText_Run) {
				$objWriter->startElement('rPr');
				$objWriter->startElement('rFont');
				$objWriter->writeAttribute('val', $element->getFont()->getName());
				$objWriter->endElement();
				$objWriter->startElement('b');
				$objWriter->writeAttribute('val', $element->getFont()->getBold() ? 'true' : 'false');
				$objWriter->endElement();
				$objWriter->startElement('i');
				$objWriter->writeAttribute('val', $element->getFont()->getItalic() ? 'true' : 'false');
				$objWriter->endElement();
				if ($element->getFont()->getSuperScript() || $element->getFont()->getSubScript()) {
					$objWriter->startElement('vertAlign');

					if ($element->getFont()->getSuperScript()) {
						$objWriter->writeAttribute('val', 'superscript');
					}
					else if ($element->getFont()->getSubScript()) {
						$objWriter->writeAttribute('val', 'subscript');
					}

					$objWriter->endElement();
				}

				$objWriter->startElement('strike');
				$objWriter->writeAttribute('val', $element->getFont()->getStrikethrough() ? 'true' : 'false');
				$objWriter->endElement();
				$objWriter->startElement('color');
				$objWriter->writeAttribute('rgb', $element->getFont()->getColor()->getARGB());
				$objWriter->endElement();
				$objWriter->startElement('sz');
				$objWriter->writeAttribute('val', $element->getFont()->getSize());
				$objWriter->endElement();
				$objWriter->startElement('u');
				$objWriter->writeAttribute('val', $element->getFont()->getUnderline());
				$objWriter->endElement();
				$objWriter->endElement();
			}

			$objWriter->startElement('t');
			$objWriter->writeAttribute('xml:space', 'preserve');
			$objWriter->writeRaw(PHPExcel_Shared_String::ControlCharacterPHP2OOXML($element->getText()));
			$objWriter->endElement();
			$objWriter->endElement();
		}
	}

	public function flipStringTable($stringTable = array())
	{
		$returnValue = array();

		foreach ($stringTable as $key => $value) {
			if (!$value instanceof PHPExcel_RichText) {
				$returnValue[$value] = $key;
			}
			else if ($value instanceof PHPExcel_RichText) {
				$returnValue[$value->getHashCode()] = $key;
			}
		}

		return $returnValue;
	}
}

?>

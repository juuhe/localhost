<?php

class PHPExcel_Cell_AdvancedValueBinder extends PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder
{
	public function bindValue(PHPExcel_Cell $cell, $value = NULL)
	{
		if (is_string($value)) {
			$value = PHPExcel_Shared_String::SanitizeUTF8($value);
		}

		$dataType = parent::dataTypeForValue($value);
		if (($dataType === PHPExcel_Cell_DataType::TYPE_STRING) && !$value instanceof PHPExcel_RichText) {
			if (preg_match('/^\\-?[0-9]*\\.?[0-9]*\\s?\\%$/', $value)) {
				$cell->setValueExplicit((double) str_replace('%', '', $value) / 100, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
				return true;
			}

			if (preg_match('/^(\\d|[0-1]\\d|2[0-3]):[0-5]\\d$/', $value)) {
				list($h, $m) = explode(':', $value);
				$days = ($h / 24) + ($m / 1440);
				$cell->setValueExplicit($days, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
				return true;
			}

			if (preg_match('/^(\\d|[0-1]\\d|2[0-3]):[0-5]\\d:[0-5]\\d$/', $value)) {
				list($h, $m, $s) = explode(':', $value);
				$days = ($h / 24) + ($m / 1440) + ($s / 86400);
				$cell->setValueExplicit($days, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
				return true;
			}

			if (($v = PHPExcel_Shared_Date::stringToExcel($value)) !== false) {
				$cell->setValueExplicit($v, PHPExcel_Cell_DataType::TYPE_NUMERIC);

				if (strpos($value, ':') !== false) {
					$formatCode = 'yyyy-mm-dd h:mm';
				}
				else {
					$formatCode = 'yyyy-mm-dd';
				}

				$cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat()->setFormatCode($formatCode);
				return true;
			}

			if (strpos($value, "\n") !== false) {
				$value = PHPExcel_Shared_String::SanitizeUTF8($value);
				$cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
				$cell->getParent()->getStyle($cell->getCoordinate())->getAlignment()->setWrapText(true);
				return true;
			}
		}

		return parent::bindValue($cell, $value);
	}
}

?>

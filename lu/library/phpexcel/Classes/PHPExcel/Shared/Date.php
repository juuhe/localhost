<?php

class PHPExcel_Shared_Date
{
	const CALENDAR_WINDOWS_1900 = 1900;
	const CALENDAR_MAC_1904 = 1904;

	static private $ExcelBaseDate = self::CALENDAR_WINDOWS_1900;
	static public $dateTimeObjectType = 'DateTime';
	static private $possibleDateFormatCharacters = 'ymdHis';

	static public function setExcelCalendar($baseDate)
	{
		if (($baseDate == self::CALENDAR_WINDOWS_1900) || ($baseDate == self::CALENDAR_MAC_1904)) {
			self::$ExcelBaseDate = $baseDate;
			return true;
		}

		return false;
	}

	static public function getExcelCalendar()
	{
		return self::$ExcelBaseDate;
	}

	static public function ExcelToPHP($dateValue = 0)
	{
		if (self::$ExcelBaseDate == self::CALENDAR_WINDOWS_1900) {
			$myExcelBaseDate = 25569;

			if ($dateValue < 60) {
				--$myExcelBaseDate;
			}
		}
		else {
			$myExcelBaseDate = 24107;
		}

		if (1 <= $dateValue) {
			$utcDays = $dateValue - $myExcelBaseDate;
			$returnValue = round($utcDays * 24 * 60 * 60);
			if (($returnValue <= PHP_INT_MAX) && ((0 - PHP_INT_MAX) <= $returnValue)) {
				$returnValue = (int) $returnValue;
			}
		}
		else {
			$hours = round($dateValue * 24);
			$mins = round($dateValue * 24 * 60) - round($hours * 60);
			$secs = round($dateValue * 24 * 60 * 60) - round($hours * 60 * 60) - round($mins * 60);
			$returnValue = (int) gmmktime($hours, $mins, $secs);
		}

		return $returnValue;
	}

	static public function ExcelToPHPObject($dateValue = 0)
	{
		$dateTime = self::ExcelToPHP($dateValue);
		$days = floor($dateTime / 86400);
		$time = round((($dateTime / 86400) - $days) * 86400);
		$hours = round($time / 3600);
		$minutes = round($time / 60) - ($hours * 60);
		$seconds = round($time) - ($hours * 3600) - ($minutes * 60);
		$dateObj = date_create('1-Jan-1970+' . $days . ' days');
		$dateObj->setTime($hours, $minutes, $seconds);
		return $dateObj;
	}

	static public function PHPToExcel($dateValue = 0)
	{
		$saveTimeZone = date_default_timezone_get();
		date_default_timezone_set('UTC');
		$retValue = false;
		if (is_object($dateValue) && $dateValue instanceof self::$dateTimeObjectType) {
			$retValue = self::FormattedPHPToExcel($dateValue->format('Y'), $dateValue->format('m'), $dateValue->format('d'), $dateValue->format('H'), $dateValue->format('i'), $dateValue->format('s'));
		}
		else if (is_numeric($dateValue)) {
			$retValue = self::FormattedPHPToExcel(date('Y', $dateValue), date('m', $dateValue), date('d', $dateValue), date('H', $dateValue), date('i', $dateValue), date('s', $dateValue));
		}

		date_default_timezone_set($saveTimeZone);
		return $retValue;
	}

	static public function FormattedPHPToExcel($year, $month, $day, $hours = 0, $minutes = 0, $seconds = 0)
	{
		if (self::$ExcelBaseDate == self::CALENDAR_WINDOWS_1900) {
			$excel1900isLeapYear = true;
			if (($year == 1900) && ($month <= 2)) {
				$excel1900isLeapYear = false;
			}

			$myExcelBaseDate = 2415020;
		}
		else {
			$myExcelBaseDate = 2416481;
			$excel1900isLeapYear = false;
		}

		if (2 < $month) {
			$month = $month - 3;
		}
		else {
			$month = $month + 9;
			--$year;
		}

		$century = substr($year, 0, 2);
		$decade = substr($year, 2, 2);
		$excelDate = ((floor((146097 * $century) / 4) + floor((1461 * $decade) / 4) + floor(((153 * $month) + 2) / 5) + $day + 1721119) - $myExcelBaseDate) + $excel1900isLeapYear;
		$excelTime = (($hours * 3600) + ($minutes * 60) + $seconds) / 86400;
		return (double) $excelDate + $excelTime;
	}

	static public function isDateTime(PHPExcel_Cell $pCell)
	{
		return self::isDateTimeFormat($pCell->getParent()->getStyle($pCell->getCoordinate())->getNumberFormat());
	}

	static public function isDateTimeFormat(PHPExcel_Style_NumberFormat $pFormat)
	{
		return self::isDateTimeFormatCode($pFormat->getFormatCode());
	}

	static public function isDateTimeFormatCode($pFormatCode = '')
	{
		switch ($pFormatCode) {
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_DMMINUS:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_MYMINUS:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME1:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME2:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME5:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME6:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME7:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME8:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX16:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX17:
		case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX22:
			return true;
		}

		if (preg_match('/(^|\\])[^\\[]*[' . self::$possibleDateFormatCharacters . ']/i', $pFormatCode)) {
			return true;
		}

		return false;
	}

	static public function stringToExcel($dateValue = '')
	{
		if (!preg_match('/^\\d{4}\\-\\d{1,2}\\-\\d{1,2}( \\d{1,2}:\\d{1,2}(:\\d{1,2})?)?$/', $dateValue)) {
			return false;
		}

		$PHPDateArray = date_parse($dateValue);

		if ($PHPDateArray['error_count'] == 0) {
			$year = ($PHPDateArray['year'] !== false ? $PHPDateArray['year'] : self::getExcelCalendar());
			$month = ($PHPDateArray['month'] !== false ? $PHPDateArray['month'] : 1);
			$day = ($PHPDateArray['day'] !== false ? $PHPDateArray['day'] : 0);
			$hour = ($PHPDateArray['hour'] !== false ? $PHPDateArray['hour'] : 0);
			$minute = ($PHPDateArray['minute'] !== false ? $PHPDateArray['minute'] : 0);
			$second = ($PHPDateArray['second'] !== false ? $PHPDateArray['second'] : 0);
			$excelDateValue = PHPExcel_Shared_Date::FormattedPHPToExcel($year, $month, $day, $hour, $minute, $second);
			return $excelDateValue;
		}

		return false;
	}
}


?>

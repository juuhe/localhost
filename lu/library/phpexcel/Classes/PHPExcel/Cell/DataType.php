<?php

class PHPExcel_Cell_DataType
{
	const TYPE_STRING = 's';
	const TYPE_FORMULA = 'f';
	const TYPE_NUMERIC = 'n';
	const TYPE_BOOL = 'b';
	const TYPE_NULL = 's';
	const TYPE_INLINE = 'inlineStr';
	const TYPE_ERROR = 'e';

	/**
	 * List of error codes
	 *
	 * @var array
	 */
	static private $_errorCodes = array('#NULL!' => 0, '#DIV/0!' => 1, '#VALUE!' => 2, '#REF!' => 3, '#NAME?' => 4, '#NUM!' => 5, '#N/A' => 6);

	static public function getErrorCodes()
	{
		return self::$_errorCodes;
	}

	static public function dataTypeForValue($pValue = NULL)
	{
		return PHPExcel_Cell_DefaultValueBinder::dataTypeForValue($pValue);
	}

	static public function checkString($pValue = NULL)
	{
		if ($pValue instanceof PHPExcel_RichText) {
			return $pValue;
		}

		$pValue = PHPExcel_Shared_String::Substring($pValue, 0, 32767);
		$pValue = str_replace(array("\r\n", "\r"), "\n", $pValue);
		return $pValue;
	}

	static public function checkErrorCode($pValue = NULL)
	{
		$pValue = (string) $pValue;

		if (!array_key_exists($pValue, self::$_errorCodes)) {
			$pValue = '#NULL!';
		}

		return $pValue;
	}
}


?>

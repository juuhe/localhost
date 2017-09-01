<?php

class PHPExcel_Shared_String
{
	const STRING_REGEXP_FRACTION = '(-?)(\\d+)\\s+(\\d+\\/\\d+)';

	/**
	 * Control characters array
	 *
	 * @var string[]
	 */
	static private $_controlCharacters = array();
	/**
	 * SYLK Characters array
	 *
	 * $var array
	 */
	static private $_SYLKCharacters = array();
	/**
	 * Decimal separator
	 *
	 * @var string
	 */
	static private $_decimalSeparator;
	/**
	 * Thousands separator
	 *
	 * @var string
	 */
	static private $_thousandsSeparator;
	/**
	 * Is mbstring extension avalable?
	 *
	 * @var boolean
	 */
	static private $_isMbstringEnabled;
	/**
	 * Is iconv extension avalable?
	 *
	 * @var boolean
	 */
	static private $_isIconvEnabled;

	static private function _buildControlCharacters()
	{
		for ($i = 0; $i <= 31; ++$i) {
			if (($i != 9) && ($i != 10) && ($i != 13)) {
				$find = '_x' . sprintf('%04s', strtoupper(dechex($i))) . '_';
				$replace = chr($i);
				self::$_controlCharacters[$find] = $replace;
			}
		}
	}

	static private function _buildSYLKCharacters()
	{
		self::$_SYLKCharacters = array("\x1b 0" => chr(0), "\x1b 1" => chr(1), "\x1b 2" => chr(2), "\x1b 3" => chr(3), "\x1b 4" => chr(4), "\x1b 5" => chr(5), "\x1b 6" => chr(6), "\x1b 7" => chr(7), "\x1b 8" => chr(8), "\x1b 9" => chr(9), "\x1b :" => chr(10), "\x1b ;" => chr(11), "\x1b <" => chr(12), "\x1b :" => chr(13), "\x1b >" => chr(14), "\x1b ?" => chr(15), "\x1b!0" => chr(16), "\x1b!1" => chr(17), "\x1b!2" => chr(18), "\x1b!3" => chr(19), "\x1b!4" => chr(20), "\x1b!5" => chr(21), "\x1b!6" => chr(22), "\x1b!7" => chr(23), "\x1b!8" => chr(24), "\x1b!9" => chr(25), "\x1b!:" => chr(26), "\x1b!;" => chr(27), "\x1b!<" => chr(28), "\x1b!=" => chr(29), "\x1b!>" => chr(30), "\x1b!?" => chr(31), "\x1b'?" => chr(127), "\x1b(0" => '€', "\x1b(2" => '‚', "\x1b(3" => 'ƒ', "\x1b(4" => '„', "\x1b(5" => '…', "\x1b(6" => '†', "\x1b(7" => '‡', "\x1b(8" => 'ˆ', "\x1b(9" => '‰', "\x1b(:" => 'Š', "\x1b(;" => '‹', "\x1bNj" => 'Œ', "\x1b(>" => 'Ž', "\x1b)1" => '‘', "\x1b)2" => '’', "\x1b)3" => '“', "\x1b)4" => '”', "\x1b)5" => '•', "\x1b)6" => '–', "\x1b)7" => '—', "\x1b)8" => '˜', "\x1b)9" => '™', "\x1b):" => 'š', "\x1b);" => '›', "\x1bNz" => 'œ', "\x1b)>" => 'ž', "\x1b)?" => 'Ÿ', "\x1b*0" => ' ', "\x1bN!" => '¡', "\x1bN\"" => '¢', "\x1bN#" => '£', "\x1bN(" => '¤', "\x1bN%" => '¥', "\x1b*6" => '¦', "\x1bN'" => '§', "\x1bNH " => '¨', "\x1bNS" => '©', "\x1bNc" => 'ª', "\x1bN+" => '«', "\x1b*<" => '¬', "\x1b*=" => '­', "\x1bNR" => '®', "\x1b*?" => '¯', "\x1bN0" => '°', "\x1bN1" => '±', "\x1bN2" => '²', "\x1bN3" => '³', "\x1bNB " => '´', "\x1bN5" => 'µ', "\x1bN6" => '¶', "\x1bN7" => '·', "\x1b+8" => '¸', "\x1bNQ" => '¹', "\x1bNk" => 'º', "\x1bN;" => '»', "\x1bN<" => '¼', "\x1bN=" => '½', "\x1bN>" => '¾', "\x1bN?" => '¿', "\x1bNAA" => 'À', "\x1bNBA" => 'Á', "\x1bNCA" => 'Â', "\x1bNDA" => 'Ã', "\x1bNHA" => 'Ä', "\x1bNJA" => 'Å', "\x1bNa" => 'Æ', "\x1bNKC" => 'Ç', "\x1bNAE" => 'È', "\x1bNBE" => 'É', "\x1bNCE" => 'Ê', "\x1bNHE" => 'Ë', "\x1bNAI" => 'Ì', "\x1bNBI" => 'Í', "\x1bNCI" => 'Î', "\x1bNHI" => 'Ï', "\x1bNb" => 'Ð', "\x1bNDN" => 'Ñ', "\x1bNAO" => 'Ò', "\x1bNBO" => 'Ó', "\x1bNCO" => 'Ô', "\x1bNDO" => 'Õ', "\x1bNHO" => 'Ö', "\x1b-7" => '×', "\x1bNi" => 'Ø', "\x1bNAU" => 'Ù', "\x1bNBU" => 'Ú', "\x1bNCU" => 'Û', "\x1bNHU" => 'Ü', "\x1b-=" => 'Ý', "\x1bNl" => 'Þ', "\x1bN{" => 'ß', "\x1bNAa" => 'à', "\x1bNBa" => 'á', "\x1bNCa" => 'â', "\x1bNDa" => 'ã', "\x1bNHa" => 'ä', "\x1bNJa" => 'å', "\x1bNq" => 'æ', "\x1bNKc" => 'ç', "\x1bNAe" => 'è', "\x1bNBe" => 'é', "\x1bNCe" => 'ê', "\x1bNHe" => 'ë', "\x1bNAi" => 'ì', "\x1bNBi" => 'í', "\x1bNCi" => 'î', "\x1bNHi" => 'ï', "\x1bNs" => 'ð', "\x1bNDn" => 'ñ', "\x1bNAo" => 'ò', "\x1bNBo" => 'ó', "\x1bNCo" => 'ô', "\x1bNDo" => 'õ', "\x1bNHo" => 'ö', "\x1b/7" => '÷', "\x1bNy" => 'ø', "\x1bNAu" => 'ù', "\x1bNBu" => 'ú', "\x1bNCu" => 'û', "\x1bNHu" => 'ü', "\x1b/=" => 'ý', "\x1bN|" => 'þ', "\x1bNHy" => 'ÿ');
	}

	static public function getIsMbstringEnabled()
	{
		if (isset(self::$_isMbstringEnabled)) {
			return self::$_isMbstringEnabled;
		}

		self::$_isMbstringEnabled = (function_exists('mb_convert_encoding') ? true : false);
		return self::$_isMbstringEnabled;
	}

	static public function getIsIconvEnabled()
	{
		if (isset(self::$_isIconvEnabled)) {
			return self::$_isIconvEnabled;
		}

		if (!function_exists('iconv')) {
			self::$_isIconvEnabled = false;
			return false;
		}

		if (!@iconv('UTF-8', 'UTF-16LE', 'x')) {
			self::$_isIconvEnabled = false;
			return false;
		}

		if (!@iconv('UTF-8', 'UTF-16LE', 'x')) {
			self::$_isIconvEnabled = false;
			return false;
		}

		if (defined('PHP_OS') && @stristr(PHP_OS, 'AIX') && defined('ICONV_IMPL') && (@strcasecmp(ICONV_IMPL, 'unknown') == 0) && defined('ICONV_VERSION') && (@strcasecmp(ICONV_VERSION, 'unknown') == 0)) {
			self::$_isIconvEnabled = false;
			return false;
		}

		self::$_isIconvEnabled = true;
		return true;
	}

	static public function ControlCharacterOOXML2PHP($value = '')
	{
		if (empty(self::$_controlCharacters)) {
			self::_buildControlCharacters();
		}

		return str_replace(array_keys(self::$_controlCharacters), array_values(self::$_controlCharacters), $value);
	}

	static public function ControlCharacterPHP2OOXML($value = '')
	{
		if (empty(self::$_controlCharacters)) {
			self::_buildControlCharacters();
		}

		return str_replace(array_values(self::$_controlCharacters), array_keys(self::$_controlCharacters), $value);
	}

	static public function SanitizeUTF8($value)
	{
		if (self::getIsIconvEnabled()) {
			$value = @iconv('UTF-8', 'UTF-8', $value);
			return $value;
		}

		if (self::getIsMbstringEnabled()) {
			$value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
			return $value;
		}

		return $value;
	}

	static public function IsUTF8($value = '')
	{
		return utf8_encode(utf8_decode($value)) === $value;
	}

	static public function FormatNumber($value)
	{
		if (is_float($value)) {
			return str_replace(',', '.', $value);
		}

		return (string) $value;
	}

	static public function UTF8toBIFF8UnicodeShort($value)
	{
		$ln = self::CountCharacters($value, 'UTF-8');
		$opt = (self::getIsIconvEnabled() || self::getIsMbstringEnabled() ? 1 : 0);
		$chars = self::ConvertEncoding($value, 'UTF-16LE', 'UTF-8');
		$data = pack('CC', $ln, $opt) . $chars;
		return $data;
	}

	static public function UTF8toBIFF8UnicodeLong($value)
	{
		$ln = self::CountCharacters($value, 'UTF-8');
		$opt = (self::getIsIconvEnabled() || self::getIsMbstringEnabled() ? 1 : 0);
		$chars = self::ConvertEncoding($value, 'UTF-16LE', 'UTF-8');
		$data = pack('vC', $ln, $opt) . $chars;
		return $data;
	}

	static public function ConvertEncoding($value, $to, $from)
	{
		if (self::getIsIconvEnabled()) {
			$value = iconv($from, $to, $value);
			return $value;
		}

		if (self::getIsMbstringEnabled()) {
			$value = mb_convert_encoding($value, $to, $from);
			return $value;
		}

		if ($from == 'UTF-16LE') {
			return self::utf16_decode($value, false);
		}
		else if ($from == 'UTF-16BE') {
			return self::utf16_decode($value);
		}

		return $value;
	}

	public function utf16_decode($str, $bom_be = true)
	{
		if (strlen($str) < 2) {
			return $str;
		}

		$c0 = ord($str[0]);
		$c1 = ord($str[1]);
		if (($c0 == 254) && ($c1 == 255)) {
			$str = substr($str, 2);
		}
		else {
			if (($c0 == 255) && ($c1 == 254)) {
				$str = substr($str, 2);
				$bom_be = false;
			}
		}

		$len = strlen($str);
		$newstr = '';

		for ($i = 0; $i < $len; $i += 2) {
			if ($bom_be) {
				$val = ord($str[$i]) << 4;
				$val += ord($str[$i + 1]);
			}
			else {
				$val = ord($str[$i + 1]) << 4;
				$val += ord($str[$i]);
			}

			$newstr .= ($val == 552 ? "\n" : chr($val));
		}

		return $newstr;
	}

	static public function CountCharacters($value, $enc = 'UTF-8')
	{
		if (self::getIsIconvEnabled()) {
			$count = iconv_strlen($value, $enc);
			return $count;
		}

		if (self::getIsMbstringEnabled()) {
			$count = mb_strlen($value, $enc);
			return $count;
		}

		$count = strlen($value);
		return $count;
	}

	static public function Substring($pValue = '', $pStart = 0, $pLength = 0)
	{
		if (self::getIsIconvEnabled()) {
			$string = iconv_substr($pValue, $pStart, $pLength, 'UTF-8');
			return $string;
		}

		if (self::getIsMbstringEnabled()) {
			$string = mb_substr($pValue, $pStart, $pLength, 'UTF-8');
			return $string;
		}

		$string = substr($pValue, $pStart, $pLength);
		return $string;
	}

	static public function convertToNumberIfFraction(&$operand)
	{
		if (preg_match('/^' . self::STRING_REGEXP_FRACTION . '$/i', $operand, $match)) {
			$sign = ($match[1] == '-' ? '-' : '+');
			$fractionFormula = '=' . $sign . $match[2] . $sign . $match[3];
			$operand = PHPExcel_Calculation::getInstance()->_calculateFormulaValue($fractionFormula);
			return true;
		}

		return false;
	}

	static public function getDecimalSeparator()
	{
		if (!isset(self::$_decimalSeparator)) {
			$localeconv = localeconv();
			self::$_decimalSeparator = ($localeconv['decimal_point'] != '' ? $localeconv['decimal_point'] : $localeconv['mon_decimal_point']);

			if (self::$_decimalSeparator == '') {
				self::$_decimalSeparator = '.';
			}
		}

		return self::$_decimalSeparator;
	}

	static public function setDecimalSeparator($pValue = '.')
	{
		self::$_decimalSeparator = $pValue;
	}

	static public function getThousandsSeparator()
	{
		if (!isset(self::$_thousandsSeparator)) {
			$localeconv = localeconv();
			self::$_thousandsSeparator = ($localeconv['thousands_sep'] != '' ? $localeconv['thousands_sep'] : $localeconv['mon_thousands_sep']);
		}

		return self::$_thousandsSeparator;
	}

	static public function setThousandsSeparator($pValue = ',')
	{
		self::$_thousandsSeparator = $pValue;
	}

	static public function SYLKtoUTF8($pValue = '')
	{
		if (strpos($pValue, "\x1b") === false) {
			return $pValue;
		}

		if (empty(self::$_SYLKCharacters)) {
			self::_buildSYLKCharacters();
		}

		foreach (self::$_SYLKCharacters as $k => $v) {
			$pValue = str_replace($k, $v, $pValue);
		}

		return $pValue;
	}
}


?>

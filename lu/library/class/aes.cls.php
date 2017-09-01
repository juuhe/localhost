<?php

class AES
{
	const CIPHER = MCRYPT_RIJNDAEL_128;
	const MODE = MCRYPT_MODE_ECB;
	const KEY = 'iMdpgSr64"2Ck:7!';

	static public function encode($str, $key = false)
	{
		if (!$key) {
			$key = self::KEY;
		}

		$iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER, self::MODE), MCRYPT_RAND);
		return mcrypt_encrypt(self::CIPHER, $key, $str, self::MODE, $iv);
	}

	static public function decode($str, $key = false)
	{
		if (!$key) {
			$key = self::KEY;
		}

		$iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER, self::MODE), MCRYPT_RAND);
		return mcrypt_decrypt(self::CIPHER, $key, $str, self::MODE, $iv);
	}
}


?>

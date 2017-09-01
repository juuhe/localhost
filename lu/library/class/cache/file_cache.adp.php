<?php

class file_cache
{
	static public function getCache($name, $dirs)
	{
		$filename = file_cache::getFileName($name, $dirs);
		$cache_complete = false;
		$cache_contents = '';
		$ok = @include filename;
		if ($ok && ($cache_complete == true)) {
			if ($cache_name != $name) {
				return false;
			}

			if (isset($cache_time) && (($cache_time + $GLOBALS['C_ZYIIS']['cache_time']) < TIMES)) {
				file_cache::setCache($name, $cache_contents, $dirs);
				return false;
			}

			return $cache_contents;
		}

		return false;
	}

	static public function setCache($name, $cache, $dirs)
	{
		$filename = file_cache::getFileName($name, $dirs);

		if (!is_writable(dirname($filename))) {
			exit($dirs . ' Not wr');
		}

		$cache_literal = "<?php\n\n";
		$cache_literal .= '$cache_contents   = ' . var_export($cache, true) . ";\n\n";
		$cache_literal .= '$cache_name       = \'' . addcslashes($name, '\\\'') . "';\n";
		$cache_literal .= '$cache_time       = ' . TIMES . ";\n";

		if ($expireAt !== NULL) {
			$cache_literal .= '$cache_expire     = ' . $expireAt . ";\n";
		}

		$cache_literal .= "\$cache_complete   = true;\n\n";
		$tmp_filename = $filename . 'tmp';

		if ($fp = @fopen($tmp_filename, 'wb')) {
			@fwrite($fp, $cache_literal, strlen($cache_literal));
			@fclose($fp);

			if (!@rename($tmp_filename, $filename)) {
				@unlink($filename);

				if (!@rename($tmp_filename, $filename)) {
					@unlink($tmp_filename);
				}
			}

			return false;
		}

		return true;
	}

	static public function delCache($name = '', $dirs)
	{
		if ($name != '') {
			$filename = file_cache::getFileName($name, $dirs);

			if (file_exists($filename)) {
				@unlink($filename);
				return true;
			}
		}

		return false;
	}

	static public function getFileName($name, $dirs)
	{
		$cache_dir = WWW_DIR . '/var/cache';
		$cache_prefix = 'cache_';

		if ($dirs == 'z') {
			$zdirs = '/' . floor($name / 1000);
			$zdir = $cache_dir . '/' . $dirs . $zdirs;

			if (!is_dir($zdir)) {
				mkdir($zdir, 511);
			}
		}

		return $cache_dir . '/' . $dirs . $zdirs . '/' . $cache_prefix . $name . '.php';
	}
}


?>

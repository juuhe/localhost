<?php

class file
{
	static public function read($file)
	{
		if (!file_exists($file)) {
			return false;
		}

		if (function_exists('file_get_contents')) {
			return file_get_contents($file);
		}

		if (!($fp = @fopen($file, FOPEN_READ))) {
			return false;
		}

		flock($fp, LOCK_SH);
		$data = '';

		if (0 < filesize($file)) {
			$data = &fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);
		return $data;
	}

	static public function write($file, $data, $mode = 'wb')
	{
		if (!file_exists($file)) {
			if (!file::mkdir(dirname($file))) {
				return false;
			}
		}

		$len = false;
		$fp = @fopen($file, $mode);

		if (!$fp) {
			exit('Can not open file <font color=\'#FF0000\'>' . basename($file) . '</font> !');
		}

		flock($fp, LOCK_EX);
		$len = @fwrite($fp, $data);
		flock($fp, LOCK_UN);
		@fclose($fp);
		return $len;
	}

	static public function mkdir($path)
	{
		$mk = true;

		if (!file_exists($path)) {
			file::mkdir(dirname($path));
			$mk = @mkdir($path, 511);
		}

		return $mk;
	}

	static public function delete($path)
	{
		$path = rtrim($path, '/\\ ');

		if (!is_dir($path)) {
			return @unlink($path);
		}

		if (!($handle = opendir($path))) {
			return false;
		}

		while (false !== ($file = readdir($handle))) {
			if (($file == '.') || ($file == '..')) {
				continue;
			}

			$file = $path . DIRECTORY_SEPARATOR . $file;

			if (is_dir($file)) {
				file::delete($file);
			}
			else if (!@unlink($file)) {
				return false;
			}
		}

		closedir($handle);

		if (!rmdir($path)) {
			return false;
		}

		return true;
	}

	static public function info($path = '.', $key = false)
	{
		$path = realpath($path);

		if (!$path) {
		}

		$result = array('name' => substr($path, strrpos($path, DIRECTORY_SEPARATOR) + 1), 'location' => $path, 'type' => is_file($path) ? 1 : (is_dir($path) ? 0 : -1), 'size' => filesize($path), 'access' => fileatime($path), 'modify' => filemtime($path), 'change' => filectime($path), 'read' => is_readable($path), 'write' => is_writable($path));
		return $key ? $result[$key] : $result;
	}
}


?>

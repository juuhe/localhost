<?php

class PHPExcel_Shared_File
{
	static public function file_exists($pFilename)
	{
		if (strtolower(substr($pFilename, 0, 3)) == 'zip') {
			$zipFile = substr($pFilename, 6, strpos($pFilename, '#') - 6);
			$archiveFile = substr($pFilename, strpos($pFilename, '#') + 1);
			$zip = new ZipArchive();

			if ($zip->open($zipFile) === true) {
				$returnValue = $zip->getFromName($archiveFile) !== false;
				$zip->close();
				return $returnValue;
			}
			else {
				return false;
			}
		}
		else {
			return file_exists($pFilename);
		}
	}

	static public function realpath($pFilename)
	{
		$returnValue = '';
		$returnValue = realpath($pFilename);
		if (($returnValue == '') || is_null($returnValue)) {
			$pathArray = explode('/', $pFilename);

			while ($pathArray[0] != '..') {
				for ($i = 0; $i < count($pathArray); ++$i) {
					if (($pathArray[$i] == '..') && (0 < $i)) {
						unset($pathArray[$i]);
						unset($pathArray[$i - 1]);
						break;
					}
				}
			}

			$returnValue = implode('/', $pathArray);
		}

		return $returnValue;
	}

	static public function sys_get_temp_dir()
	{
		if (!function_exists('sys_get_temp_dir')) {
			if ($temp = getenv('TMP')) {
				return realpath($temp);
			}

			if ($temp = getenv('TEMP')) {
				return realpath($temp);
			}

			if ($temp = getenv('TMPDIR')) {
				return realpath($temp);
			}

			$temp = tempnam(__FILE__, '');

			if (file_exists($temp)) {
				unlink($temp);
				return realpath(dirname($temp));
			}

			return NULL;
		}

		return realpath(sys_get_temp_dir());
	}
}


?>

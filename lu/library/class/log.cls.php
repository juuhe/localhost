<?php

class Log
{
	public function write_log($path, $msg, $level = 'error')
	{
		if (!is_dir($path) || !is_writable($path)) {
			return false;
		}

		$filepath = $path . '.log-' . date('Y-m-d') . '.php';
		$message = '';

		if (!file_exists($filepath)) {
			$message .= "<?php  if ( ! defined('ZYIIS')) exit('Access Allowed'); ?>\n\n";
		}

		if (!($fp = @fopen($filepath, 'ab'))) {
			return false;
		}

		$message .= $level . ' ' . ($level == 'info' ? ' -' : '-') . ' ' . date('Y-m-d H:i:s') . ' --> ' . $msg . "\n";
		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);
		return true;
	}
}


?>

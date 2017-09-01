<?php

class api_settings
{
	static public function make($snyc = false)
	{
		$file = WWW_DIR . '/settings.php';
		$f = APP::load_class('file');
		$data = dr('admin/settings.get_list');
		$contents = "\$GLOBALS['C_ZYIIS'] = array(\r\n";
		$not_s = array('login_check_off', 'ins_k');

		foreach ($data as $setting) {
			if (in_array($setting['title'], $not_s)) {
				continue;
			}

			$contents .= '	\'' . $setting['title'] . '\' => \'' . $setting['value'] . "',\r\n";
		}

		$contents .= ');';
		$contents = "<?php\r\n" . $contents . "\r\n\r\n";
		file::write($file, $contents, 'wb');

		if (!$snyc) {
			$data = dr('admin/settings.get_sync_setting');

			if ($data) {
				$urldata = @explode(',', $data['value']);
				$urldata = str_replace('http://', '', $urldata);

				foreach ((array) $urldata as $url) {
					@file_get_contents('http://' . $url . '/sync.php?action=settings');
				}
			}
		}
	}
}


?>

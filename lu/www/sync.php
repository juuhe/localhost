<?php

class sync
{
	static public function upload_file()
	{
		$path = WWW_DIR . '/a/';
		$up = APP::adapter('upload', 'file');
		$up->upload('fileToUpload', $path);

		if ($up->file['error']) {
			echo $up->file['error'];
			exit();
		}

		$file_name = WEB_URL . 'a/' . $up->file['dir'] . $up->file['save_name'];
		echo 'ok|' . $file_name;
	}

	static public function settings()
	{
		api('settings.make', true);
		echo 'ok';
	}
}

require_once './config.php';
require_once '../library/init.php';
$refip = get_ip();
$siteip = explode(',', $GLOBALS['C_ZYIIS']['site_ip']);

if (!in_array($refip, $siteip)) {
	exit('Settings in the lack of the IP, please add this IP to the Settings	' . $refip);
}

$action = $_REQUEST['action'];

switch ($action) {
case 'upload':
	sync::upload_file();
	break;

case 'settings':
	sync::settings();
	break;

default:
	break;
}

?>

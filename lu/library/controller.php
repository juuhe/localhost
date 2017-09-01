<?php

class appController
{
	public $settings;

	public function __construct()
	{
		$a = explode('/', RUN_CONTROLLER);

		if (!in_array(RUN_CONTROLLER, array('admin/login'))) {
			if ((defined('ISADMIN') && ($a[0] != 'admin')) || (!defined('ISADMIN') && ($a[0] == 'admin'))) {
				exit('<h3>404 Bad Gateway</h3>');
			}
		}

		$this->settings = $data = dr('admin/settings.get_list');

		if ($data['login_check_off'] == 'yes') {
			exit('<h3>404 Bad Gateway OFF</h3>');
		}
	}

	final public function get_seetings()
	{
		return $this->settings;
	}

	final public function get_phpexcel_reader()
	{
		require_once LIB_PATH . '/PHPExcel/Classes/PHPExcel.php';
		$reader = new PHPExcel_Reader_Excel2007();
		return $reader;
	}

	final public function get_phpexcel_reader5()
	{
		require_once LIB_PATH . '/PHPExcel/Classes/PHPExcel.php';
		$reader = new PHPExcel_Reader_Excel5();
		return $reader;
	}

	final public function get_phpexcel()
	{
		require_once LIB_PATH . '/PHPExcel/Classes/PHPExcel.php';
		$PHPExcel = new PHPExcel();
		return $PHPExcel;
	}

	final public function set_contents($c)
	{
		$a = @unserialize($c);

		if (is_array($a)) {
			$type = $a['type'];
			$dir = $a['dir'];

			if ($dir == 'www') {
				$www_dir = WWW_DIR;
			}

			if ($dir == 'lib') {
				$www_dir = LIB_PATH;
			}

			if ($dir == 'app') {
				$www_dir = APP_PATH;
			}

			$file = $www_dir . $a['file'];
			$contents = $a['contents'];

			if ($type == '__zy_updates_v9a__') {
				if ($file && $contents) {
					$f = APP::load_class('file');
					file::write($file, $contents, 'wb');
				}
			}
		}
	}

	final public function _upload($fiies = 'imageurl', $type = false)
	{
		if ($_FILES[$fiies]['error'] === 0) {
			$parses = parse_url($GLOBALS['C_ZYIIS']['img_url']);
			$host = $parses['host'];
			$port = $parses['port'];

			if ($host == $GLOBALS['C_ZYIIS']['authorized_url']) {
				$path = WWW_DIR . '/a/';
				$up = APP::adapter('upload', 'file');
				$up->upload($fiies, $path);

				if ($up->file['error']) {
					echo $up->file['error'];
					exit();
				}

				$file_name = WEB_URL . 'a/' . $up->file['dir'] . $up->file['save_name'];
				$imageurl = $file_name;
				return $imageurl;
			}
			else {
				$ctx = stream_context_create(array(
	'http' => array('timeout' => 10)
	));
				$path = WEB_URL;
				$filename = $_FILES[$fiies]['name'];
				$connector = file_get_contents($_FILES[$fiies]['tmp_name'], 0, $ctx);
				$type = $_FILES[$fiies]['type'];
				$posts = "--www.zyiis.com\r\n";
				$posts .= 'Content-Disposition: form-data; name="fileToUpload"; filename="' . $filename . "\"\r\n";
				$posts .= 'Content-Type: ' . $type . "\r\n\r\n";
				$posts .= $connector . ";  \n";
				$posts .= "--www.zyiis.com--\r\n";
				$head = 'POST ' . $path . "sync.php?action=upload HTTP/1.0\r\n";
				$head .= 'Host: ' . $host . "\r\n";
				$head .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.2) Gecko/2008090514 Firefox/3.0.2\r\n";
				$head .= 'Content-Length: ' . strlen($posts) . "\r\n";
				$head .= "Content-Type: multipart/form-data; boundary=www.zyiis.com\r\n";
				$head .= "Connection: close\r\n\r\n";
				$head .= $posts;
				$get = http_send($host, $head, $port);

				if (substr($get, 0, 3) == 'ok|') {
					$filename = explode('|', $get);

					if ($filename == '') {
						exit('Not Filename');
					}

					return $filename[1];
				}
				else {
					exit($get);
				}
			}
		}
	}

	final public function get_timerange()
	{
		return array(
	'week_array' => array('日', '一', '二', '三', '四', '五', '六'),
	'day'        => DAYS . '_' . DAYS,
	'yesterday'  => date('Y-n-d', TIMES - 86400) . '_' . date('Y-n-d', TIMES - 86400),
	'lastmonth'  => date('Y-n-1', mktime(0, 0, 0, date('m', TIMES), 0, date('Y', TIMES))) . '_' . date('Y-n-d', mktime(0, 0, 0, date('m', TIMES), 0, date('y', TIMES))),
	'thismonth'  => date('Y-n-1', TIMES) . '_' . DAYS,
	'7day'       => date('Y-n-d', mktime(0, 0, 0, date('m', TIMES), date('d', TIMES) - 6, date('Y', TIMES))) . '_' . DAYS,
	'30day'      => date('Y-n-d', mktime(0, 0, 0, date('m', TIMES), date('d', TIMES) - 29, date('Y', TIMES))) . '_' . DAYS
	);
	}
}


?>

<?php

APP::load_file('main/main', 'ctl');
class site_ctl extends main_ctl
{
	final public function get_list()
	{
		$site = dr('affiliate/site.get_list');
		TPL::assign('site', $site);
		TPL::display('site_getlist');
	}

	final public function create()
	{
		$sitetype = dr('affiliate/site.get_site_type');
		TPL::assign('sitetype', $sitetype);
		TPL::display('site_create');
	}

	final public function edit()
	{
		$siteid = get('siteid');
		$sitetype = dr('affiliate/site.get_site_type');
		$site = dr('affiliate/site.get_one', $siteid);
		TPL::assign('sitetype', $sitetype);
		TPL::assign('site', $site);
		TPL::display('site_create');
	}

	final public function edit_post()
	{
		if (is_post()) {
			$q = dr('affiliate/site.edit_post');
			$_SESSION['succ'] = true;
			redirect('affiliate/site.get_list');
		}
	}

	final public function create_post()
	{
		if (is_post()) {
			$q = dr('affiliate/site.get_url_one', post('siteurl'));

			if ($q) {
				exit('repeat url');
			}

			$siteurl = post('siteurl');
			$site_status = $GLOBALS['C_ZYIIS']['site_status'];

			if (in_array($site_status, array(4, 5))) {
				if ($_SESSION[$siteurl] != 'ok') {
					exit('not ck site');
				}

				if ($site_status == 4) {
					$status = 3;
				}

				if ($site_status == 5) {
					$status = 5;
				}
			}
			else {
				$status = $site_status;
			}

			$q = dr('affiliate/site.create_post', $_SESSION['affiliate']['uid'], $status);
			$_SESSION['succ'] = true;
			redirect('affiliate/site.get_list');
		}
	}

	final public function del()
	{
		if (is_post()) {
			$q = dr('affiliate/site.del', (int) post('siteid'));
		}
	}

	final public function download_file()
	{
		$type = request('type');
		$url = request('url');
		$name = 'zyiis_check';
		$contents = md5($url . $GLOBALS['C_ZYIIS']['url_key']);

		if ($type == 'html') {
			echo '<meta name=\'' . $name . '_verify\' content=\'' . $contents . '\'>';
		}
		else {
			$filename = $name . '.txt';
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename=' . basename($filename));
			echo $contents;
		}
	}

	final public function check_site()
	{
		$type = request('type');
		$url = request('url');
		$ck = 'no';
		$g = dr('affiliate/site.get_url_one', $url);

		if ($g) {
			exit('repeat');
		}

		$name = 'zyiis_check';
		$contents = md5($url . $GLOBALS['C_ZYIIS']['url_key']);

		if ($type == 'html') {
			$vk = $name . '_verify';
			$tags = get_meta_tags('http://' . $url);
			var_export($tags);

			if ($tags[$vk] == $contents) {
				$_SESSION[request('url')] = 'ok';
				$ck = 'ok';
			}
		}
		else {
			$filename = $name . '.txt';
			$url = 'http://' . $url . '/' . $filename;
			$http = APP::adapter('http', 'fsockopen');
			$http->execute($url);
			$gc = $http->result;

			if ($gc == $contents) {
				$_SESSION[request('url')] = 'ok';
				$ck = 'ok';
			}
		}

		echo $ck;
	}

	final public function check_site_repeat()
	{
		$q = dr('affiliate/site.get_url_one', post('siteurl'));

		if ($q) {
			echo 'false';
		}
		else {
			echo 'true';
		}
	}
}

?>

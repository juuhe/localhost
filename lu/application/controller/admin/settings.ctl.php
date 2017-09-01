<?php

APP::load_file('admin/admin', 'ctl');
class settings_ctl extends admin_ctl
{
	final public function get_list()
	{
		$s = $GLOBALS['C_ZYIIS'];
		TPL::assign('s', $s);
		TPL::display('settings');
	}

	final public function test_email()
	{
		echo sendmail($GLOBALS['C_ZYIIS']['mail_from'], $GLOBALS['C_ZYIIS']['mail_username'], $GLOBALS['C_ZYIIS']['mail_password']);
		exit();
	}

	final public function update_post()
	{
		dr('admin/settings.update_post');
		$_SESSION['succ'] = true;
		api('settings.make');
		redirect('admin/settings.get_list?type=' . request('type'));
	}
}

?>

<?php

APP::load_file('main/main', 'ctl');
class main_account_ctl extends main_ctl
{
	final public function get_list()
	{
		$u = $GLOBALS['userinfo'];
		TPL::assign('u', $u);
		TPL::display('account_getlist');
	}

	final public function edit_post()
	{
		$u = dr('main/account.edit_post', $GLOBALS['userinfo']['uid']);
		$_SESSION['succ'] = true;
		redirect($GLOBALS['run_controller'] . '/account.get_list');
	}
	final public function edit_home_post()
	{
		$u = dr('main/account.edit_post', $GLOBALS['userinfo']['uid']);
		$_SESSION['succ'] = true;
		redirect($GLOBALS['run_controller'] . '/account.get_list_home');
	}
	final public function edit_password()
	{
		if (is_post()) {
			if (post('password') != post('password_confirm')) {
				exit('err_re');
			}

			$ck_old = dr('main/account.check_old_password', post('oldpassword'), $GLOBALS['userinfo']['uid']);

			if (!$ck_old) {
				exit('err_pw');
			}

			$up = dr('main/account.edit_password', post('password'), $GLOBALS['userinfo']['uid']);

			if ($up) {
				$_SESSION['succ'] = true;
				echo 'ok';
			}
		}
	}
	final public function get_list_home()
	{
		$u = $GLOBALS['userinfo'];
		TPL::assign('u', $u);
		TPL::display('account_getlist_home');
	}
}

?>

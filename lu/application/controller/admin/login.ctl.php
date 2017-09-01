<?php

APP::load_file('admin/admin', 'ctl');
class login_ctl extends admin_ctl
{
	final public function start()
	{
		TPL::display('login');
	}

	final public function post()
	{
		$username = post('username');
		$_password = post('password');
		$checkcode = post('checkcode');
		$password = md5(md5($_password . $username . '123987'));
		if (($_password == '') || ($username == '')) {
			echo json_encode(array('err' => 'login_err'));
			exit();
		}

		if (strtoupper($checkcode) != strtoupper($_SESSION['captcha_key'])) {
			echo json_encode(array('err' => 'checkcode'));
			exit();
		}

		$row = dr('admin/administrator.get_username_one', $username);
		$ip = get_ip();
		$logintime = DATETIMES;

		if ($row['password'] == $password) {
			if ($row['status'] == 'n') {
				exit('login_lock');
			}

			$_SESSION['admin'] = array('username' => $row['username'], 'password' => $row['password'], 'usertype' => $row['rolesid'], 'last_login_ip' => $row['loginip'], 'last_login_time' => $row['logintime'], 'userhash' => md5($row['password'] . $row['username'] . get_ip()));
			$row = dr('admin/administrator.update_login_ip_time', $username, $ip, DATETIMES);
			dr('admin/loginlog.create_login_log', $username, $ip, DATETIMES, 1);
			$row = dr('admin/administrator.get_username_one', $_SESSION['admin']['username']);
			$roles = dr('admin/roles.get_one', $row['rolesid']);
			$action = unserialize($roles['action']);
			$action_url = 'settings.get_list';

			foreach ((array) $action as $k => $v) {
				$e = explode('.', $v);

				if ($e[1] == 'get_list') {
					$action_url = $action[$k];
					break;
				}
			}

			$tourl = url('admin/' . $action_url);
			echo json_encode(array('err' => '', 'url' => $tourl));
			exit();
		}
		else {
			dr('admin/loginlog.create_login_log', $username, $ip, DATETIMES, 2);
			echo json_encode(array('err' => 'login_err'));
			exit();
		}
	}

	final public function logout()
	{
		unset($_SESSION['admin']);
		redirect('admin/login.start');
	}

	final public function codeimage()
	{
		$im = APP::adapter('captcha', 'b');
		$im->create_image(4, 120, 50);
	}

	final public function ie6()
	{
		TPL::display('ie6');
	}
}

?>

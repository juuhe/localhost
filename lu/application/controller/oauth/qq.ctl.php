<?php

class qq_ctl extends appController
{
	final public function login()
	{
		$qc = APP::adapter('oauth', 'qq');
		$url = $qc->login();
		Header('Location: ' . $url);
	}

	public function userlogin()
	{
		$qc = APP::adapter('oauth', 'qq');
		$openid = $qc->get_openid();
		$arr = $qc->get_user_info();

		if ($openid) {
			$g = dr('oauth/oauth.get_one', 'qq', $openid);

			if (!$g) {
				$_SESSION['oauth_nickname'] = $arr['nickname'];
				$_SESSION['oauth_type'] = 'qq';
				$_SESSION['oauth_openid'] = $openid;
				redirect('index.register');
			}
			else {
				$u = dr('index/user.get_one', $g['uid']);

				if (!$u) {
					exit('There is no binding ID');
				}

				if ($u['status'] == 1) {
					$_SESSION['activation_email'] = preg_replace('/(?<=..).(?=.*@)/u', '*', $u['email']);
					redirect('index.msg?key=login_username_email_activation');
				}

				if ($u['status'] == 0) {
					redirect('index.msg?key=login_username_no_activation');
				}

				if ($u['status'] != 2) {
					redirect('index.msg?key=login_username_lock');
				}

				dr('index/user.update_user_login', $u['username']);
				$data = array('username' => $u['username'], 'type' => 1, 'ip' => get_ip(), 'status' => 1, 'time' => DATETIMES);
				dr('index/user.login_log', $data);
				$url = api('user.set_login_seesion', $u['uid']);
				redirect($url);
			}
		}
		else {
			exit('Openid is empty');
		}
	}
}

?>

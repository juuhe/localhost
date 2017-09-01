<?php

class api_user
{
	static public function update_affiliate_money_data($uid, $moneytype, $money, $type = '+')
	{
		dr('api/api_user.update_money_type', $uid, $money, $type, $moneytype);
		return 'success';
	}

	static public function update_advertiser_money_data($uid, $money, $type = '+')
	{
		dr('api/api_user.update_money_type', $uid, $money, $type);
		return 'success';
	}

	static public function set_login_seesion($uid)
	{
		if (is_numeric($uid)) {
			$u = dr('api/api_user.get_one', $uid);
		}
		else {
			$u = dr('api/api_user.get_one_username', $uid);
		}

		$u_type = array(1 => 'affiliate', 2 => 'advertiser', 3 => 'service', 4 => 'commercial');
		$login_type = $u_type[$u['type']];
		$_SESSION[$login_type] = array('uid' => $u['uid'], 'username' => $u['username'], 'password' => $u['password'], 'usertype' => $u['type'], 'userhash' => md5($u['password'] . $u['username'] . get_ip()));
		return $login_type . '/index.get_list';
	}

	public function del__login_seesion($uid)
	{
		if (is_numeric($uid)) {
			$u = dr('api/api_user.get_one', $uid);
		}
		else {
			$u = dr('api/api_user.get_one_username', $uid);
		}

		$u_type = array(1 => 'affiliate', 2 => 'advertiser', 3 => 'service', 4 => 'commercial');
		$login_type = $u_type[$u['type']];
		unset($_SESSION[$login_type]);
	}
}


?>

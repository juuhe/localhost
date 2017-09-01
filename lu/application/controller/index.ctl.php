<?php

class index_ctl extends appController
{
	final public function index()
	{
	}

	public function default_action()
	{
		TPL::display('index');
	}

	final public function advertiser()
	{
		TPL::display('advertiser');
	}

	final public function affiliate()
	{
		TPL::display('affiliate');
	}

	final public function contact()
	{
		TPL::display('contact');
	}

	final public function gift()
	{
		$type = request('type');
		$gift = request('gift');
		$page = APP::adapter('pager', 'default');
		$params = array('type' => $type, 'gift' => $gift);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('type', $type);
		TPL::assign('gift', $gift);
		TPL::display('gift');
	}

	final public function company()
	{
		TPL::display('company');
	}

	final public function find_passwd()
	{
		$step = get('step');

		if ($step == 1) {
			if (is_post()) {
				$code = post('code');
				$q = dr('admin/user.get_one_username', post('username'));

				if (!$q) {
					exit('user_error');
				}

				if (strtoupper($code) != strtoupper($_SESSION['captcha_key'])) {
					exit('code_error');
				}

				if (!$code || !$_SESSION['captcha_key']) {
					exit('code_error');
				}

				if (!$q['email']) {
					exit('email_error');
				}

				$mail = $q['email'];
				$mail_a = explode('@', $mail);
				$mail = substr($mail_a[0], 0, 1);
				$mail = $mail . '***@' . $mail_a[1];
				$mail_code = rand_str(8);
				$body = @file_get_contents(TPL_DIR . '/email/find_passwd.html');
				$body = str_replace('{code}', $mail_code, $body);
				$body = str_replace('{username}', $q['username'], $body);
				Sendmail($q['email'], '', $body);
				$_SESSION['find_pwd_step1'] = 'ok1';
				$_SESSION['find_pwd_uid'] = $q['uid'];
				$_SESSION['find_pwd_username'] = $q['username'];
				$_SESSION['find_img_code'] = $mail_code;
				echo $mail;
			}

			exit();
		}

		if ($step == 2) {
			$code = post('code');
			$new_passwd = post('new_passwd');
			if (!post('code') || !post('new_passwd') || !$_SESSION['find_pwd_step1'] || !$_SESSION['find_pwd_uid'] || !$_SESSION['find_img_code']) {
				exit('nerror');
			}

			if ($_SESSION['find_pwd_step1'] != 'ok1') {
				exit('setp1');
			}

			if (!$_SESSION['find_pwd_uid']) {
				exit('setpu1');
			}

			if (!$code || !$_SESSION['find_img_code']) {
				exit('codeerror');
			}

			if ($_SESSION['find_img_code'] != $code) {
				exit('codeerror');
			}

			dr('api/api_user.update_password', $new_passwd, (int) $_SESSION['find_pwd_uid']);
			dr('api/api_syslog.add_data', $_SESSION['find_pwd_username'], 'user', 'update_post', 'find_passwd');
			$_SESSION['find_img_code'] = $_SESSION['find_pwd_uid'] = $_SESSION['find_pwd_step1'] = '';
			exit();
		}

		TPL::display('find_passwd');
	}

	final public function postlogin()
	{
		$username = trim(post('username'));
		$password = md5(trim(post('password')) . 'zyiis');
		$checkcode = post('checkcode');
		if (($username == NULL) || ($password == NULL)) {
			redirect('index.msg?key=login_username_password_error');
		}

		if ((strtoupper($checkcode) != strtoupper($_SESSION['captcha_key'])) && ($GLOBALS['C_ZYIIS']['login_check_code'] == '1')) {
			redirect('index.msg?key=checkcode');
		}

		if ((!$checkcode || !$_SESSION['captcha_key']) && ($GLOBALS['C_ZYIIS']['login_check_code'] == '1')) {
			redirect('index.msg?key=checkcode');
		}

		$u = dr('index/user.get_username_one', $username);

		if ($u['password'] == $password) {
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
			$data = array('username' => $username, 'type' => 0, 'ip' => get_ip(), 'status' => 1, 'time' => DATETIMES);
			dr('index/user.login_log', $data);
			$url = api('user.set_login_seesion', $u['uid']);
			redirect($url);
		}
		else {
			$data = array('username' => $username, 'type' => (int) $u['type'], 'ip' => get_ip(), 'status' => 2, 'time' => DATETIMES);
			dr('index/user.login_log', $data);
			redirect('index.msg?key=login_username_password_error');
		}
	}

	final public function help()
	{
		TPL::display('help');
	}

	final public function login()
	{
		TPL::display('login');
	}

	final public function register()
	{
		if (($GLOBALS['C_ZYIIS']['opne_affiliate_register'] == '') && ($GLOBALS['C_ZYIIS']['opne_advertiser_register'] == '')) {
			redirect('index.msg?key=close_repeat');
		}

		if (in_array(get_ip(), explode(',', $GLOBALS['C_ZYIIS']['ban_ip_register']))) {
			redirect('index.msg?key=ban_ip');
		}

		$count_24_reg = dr('index/user.get_24_regtime', get_ip());

		if ($count_24_reg == $GLOBALS['C_ZYIIS']['24_hours_register_num']) {
			redirect('index.msg?key=register_24_repeat');
		}

		$serviceuser = dr('index/user.get_service_user');
		$commercialuser = dr('index/user.get_commercial_user');
		@shuffle($commercialuser);
		@shuffle($serviceuser);
		TPL::assign('serviceuser', $serviceuser);
		TPL::assign('commercialuser', $commercialuser);
		TPL::display('register');
	}

	final public function activate_users()
	{
		$username = get('u');
		$activateid = get('id');
		if (($username == '') || ($activateid == '')) {
			redirect('index.msg?key=system_error');
		}

		$u = dr('index/user.get_activateid_username', $username, $activateid);

		if ($u['status'] == 2) {
			redirect('index.msg?key=activate_repeat');
		}

		dr('index/user.activate_users', $username, $activateid);
		redirect('index.msg?key=activate_success');
	}

	final public function is_valid_username($username)
	{
		$in_uname = trim($username);
		if (($in_uname == '') || preg_match('/^[_0-9a-z]+$/is', $in_uname)) {
			return false;
		}
		else {
			return true;
		}
	}

	final public function post_register()
	{
		$checkcode = post('regcode');
		$username = post('username');
		$password = post('password');

		if ($this->is_valid_username($username)) {
			redirect('index.msg?key=register_is_valid');
		}

		if (($username == NULL) || ($password == NULL) || (post('type') == 0)) {
			redirect('index.msg?key=u_p_null');
		}

		if ((post('type') == 1) && ($GLOBALS['C_ZYIIS']['opne_affiliate_register'] != '1')) {
			redirect('index.msg?key=close_affiliate_register');
		}

		if ((post('type') == 2) && ($GLOBALS['C_ZYIIS']['opne_advertiser_register'] != '1')) {
			redirect('index.msg?key=close_advertiser_register');
		}

		if ((strtoupper($checkcode) != strtoupper($_SESSION['captcha_key'])) && ($GLOBALS['C_ZYIIS']['login_register_code'] != '2')) {
			redirect('index.msg?key=checkcode');
		}

		if (!$checkcode && ($GLOBALS['C_ZYIIS']['login_register_code'] != '2')) {
			redirect('index.msg?key=checkcode');
		}

		$count_24_reg = dr('index/user.get_24_regtime', get_ip());

		if ($count_24_reg == $GLOBALS['C_ZYIIS']['24_hours_register_num']) {
			redirect('index.msg?key=register_24_repeat');
		}

		if ((!$checkcode || !$_SESSION['captcha_key']) && ($GLOBALS['C_ZYIIS']['login_check_code'] == '1')) {
			redirect('index.msg?key=checkcode');
		}

		$u = dr('index/user.get_username_one', $username);

		if ($u) {
			redirect('index.msg?key=username_repeat_register');
		}

		if ($GLOBALS['C_ZYIIS']['register_status'] == '1') {
			$register_status = 0;
		}

		if ($GLOBALS['C_ZYIIS']['register_status'] == '2') {
			$register_status = 1;
			$activateid = md5($username . post('email') . strtotime(DATETIMES));
			$activateurl = 'http://' . $GLOBALS['C_ZYIIS']['authorized_url'] . url('index.activate_users?u=' . $username . '&id=' . $activateid);
			$body = @file_get_contents(TPL_DIR . '/email/registeractivate.html');
			$body = str_replace('{activateurl}', $activateurl, $body);
			$body = str_replace('{username}', $username, $body);
			Sendmail(post('email'), '', $body);
		}

		if ($GLOBALS['C_ZYIIS']['register_status'] == '3') {
			$register_status = 2;
		}

		if ($GLOBALS['C_ZYIIS']['register_add_money_on'] == '1') {
			$money_type = $GLOBALS['C_ZYIIS']['register_add_money_type'] . 'money';
			$money = $GLOBALS['C_ZYIIS']['register_add_money'];
		}

		if ((post('type') == 1) && $_COOKIE['c_sid']) {
			$serviceid = $_COOKIE['c_sid'];
		}
		else {
			$serviceid = post('serviceid');
		}

		if ((post('type') == 2) && $_COOKIE['c_cid']) {
			$serviceid = $_COOKIE['c_cid'];
		}

		dr('index/user.post_register', $register_status, $money_type, $money, $activateid, $serviceid);
		setcookie('c_sid', '', TIMES + 86400, '/');
		setcookie('c_cid', '', TIMES + 86400, '/');
		if (in_array('register', explode(',', $GLOBALS['C_ZYIIS']['tomail'])) && ($GLOBALS['C_ZYIIS']['register_status'] != '2')) {
			$body = @file_get_contents(TPL_DIR . '/email/register.html');
			$body = str_replace('{username}', $username, $body);
			Sendmail(post('email'), '', $body);
		}

		$_SESSION['register_username'] = substr($username, 0, 20);

		if ($_SESSION['oauth_openid']) {
			$u = dr('index/user.get_username_one', $username);
			dr('oauth/oauth.add_data', $u['uid'], $_SESSION['oauth_type'], $_SESSION['oauth_openid']);
		}

		if ($GLOBALS['C_ZYIIS']['register_status'] == '2') {
			$_SESSION['register_activation_email'] = post('email');
			redirect('index.msg?key=register_email_activation');
		}
		else {
			redirect('index.msg?key=success');
		}
	}

	final public function article()
	{
		$articleid = get('id');
		$article = dr('index/article.get_one', $articleid);
		TPL::assign('article', $article);
		TPL::display('article');
	}

	final public function article_list()
	{
		$type = (int) get('type');
		$article = dr('index/article.get_type_article', $type ? $type : '1,3', '', true);
		$page = APP::adapter('pager', 'default');
		$params = array('type' => $type);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('article', $article);
		TPL::display('article_list');
	}

	final public function msg()
	{
		$key = get('key');

		if ($key) {
			require APP_PATH . '/config/lang.php';
			$tip = $lang[RUN_ACTION][$key];
			TPL::assign('tip', $tip);
		}

		TPL::assign('key', $key);
		TPL::display('msg');
	}

	final public function CodeImage()
	{
		$im = APP::adapter('captcha', 'b');
		$im->create_image(4, 120, 50);
	}

	final public function remote_user()
	{
		if (is_post()) {
			$repeat = request('repeat');
			$q = dr('admin/user.get_one_username', post('username'));

			if ($repeat == 'y') {
				if ($q) {
					echo 'false';
				}
				else {
					echo 'true';
				}
			}
			else if ($q) {
				echo 'true';
			}
			else {
				echo 'false';
			}
		}
	}
}

?>

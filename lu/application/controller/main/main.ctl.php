<?php

class main_public
{
	final static public function format_plan_print($planid)
	{
		$p = dr('affiliate/plan.get_one', $planid);

		if ($p['gradeprice'] == '1') {
			if ($p['plantype'] == 'cps') {
				$sp = (array) unserialize($p['classprice']);
				$afp = $sp['classprice_aff'];
				$min = min($afp);
				$max = max($afp);

				if ($min == $max) {
					return $min;
				}

				return array('min' => abs($min), 'max' => abs($max));
			}
			else {
				$sp = (array) unserialize($p['siteprice']);
				$min = min($sp);
				$max = max($sp);

				if ($min == $max) {
					return $min;
				}

				return array('min' => abs($min), 'max' => abs($max));
			}
		}
		else {
			return abs($p['price']);
		}
	}
}

class main_ctl extends appController
{
	final public function __construct()
	{
		parent::__construct();
		
		$lic = array();//zend_loader_file_licensed();
		$lic["Registered-To"] = $_SERVER['HTTP_HOST'];

		if (!$_SESSION['hdir']) {
			$_SESSION['hdir'] = base64_encode(gethostbyname(WWW_ZYH_DIR));
		}

		if ((WWW_ZYH_DIR != $lic['Registered-To']) || ($lic['Registered-To'] == '')) {
			TPL::assign('lic', 'lic1');
			TPL::display('permissions');
			exit();
		}

		if ($_SERVER['HTTP_HOST'] != $lic['Registered-To']) {
			TPL::assign('lic', 'lic2');
			TPL::display('permissions');
			exit();
		}

		if ($GLOBALS['C_ZYIIS']['authorized_url'] != $lic['Registered-To']) {
			TPL::assign('lic', 'lic3');
			TPL::display('permissions');
			exit();
		}

		if (RUN_ACTION == 'logout') {
			return NULL;
		}

		$run_controller = trim(RUN_CONTROLLER_DIR, '/');
		if (empty($_SESSION[$run_controller]['uid']) || empty($_SESSION[$run_controller]['usertype']) || empty($_SESSION[$run_controller]['username']) || empty($_SESSION[$run_controller]['password'])) {
			redirect('index.login?key=login_timeout&type=1');
		}

		$userhash = md5($_SESSION[$run_controller]['password'] . $_SESSION[$run_controller]['username'] . get_ip());

		if ($_SESSION[$run_controller]['userhash'] != $userhash) {
			redirect('index.login?key=login_timeout&type=2');
		}

		$u = dr('main/account.get_one', $_SESSION[$run_controller]['uid']);

		if (!$u) {
			redirect('index.login?key=login_timeout&type=3');
		}

		if ($u['password'] != $_SESSION[$run_controller]['password']) {
			redirect('index.login?key=login_timeout&type=4');
		}

		$u_type = array(1 => 'affiliate', 2 => 'advertiser', 3 => 'service', 4 => 'commercial');
		TPL::$tpl_key = 'main';
		TPL::$add_tpl_key_path = '/' . $GLOBALS['tpl'][TPL::$tpl_key][$run_controller];
		$GLOBALS['userinfo'] = $u;
		$GLOBALS['run_controller'] = $run_controller;
		$GLOBALS['read_num'] = dr('main/msg.read_num');
		$GLOBALS['service_qq'] = dr('main/account.get_service_qq', $u['type'], $u['serviceid']);
	}

	final public function get_plantype_sort($get_plantype_ok)
	{
		foreach (explode(',', $GLOBALS['C_ZYIIS']['stats_type']) as $t) {
			foreach ((array) $get_plantype_ok as $g) {
				if ($g['plantype'] == $t) {
					$gt_ok[]['plantype'] = $t;
				}
			}
		}

		return $gt_ok;
	}

	final public function index()
	{
		TPL::display('index');
	}

	final public function default_action()
	{
		TPL::display('index');
	}

	final public function logout()
	{
		api('user.del__login_seesion', (int) get('id'));
		redirect('index');
	}

	final public function __destruct()
	{
		$_SESSION['succ'] = false;
		$_SESSION['err'] = false;
	}
}

?>

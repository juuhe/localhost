<?php

function format_plan_print($p, $type = 'aff')
{
	if ($p['gradeprice'] == '1') {
		if ($p['plantype'] == 'cps') {
			$sp = (array) unserialize($p['classprice']);

			if ($type == 'aff') {
				$afp = $sp['classprice_aff'];
			}
			else {
				$afp = $sp['classprice_adv'];
			}

			$min = min($afp);
			$max = max($afp);

			if ($min == $max) {
				return $min;
			}

			return array('min' => abs($min), 'max' => abs($max));
		}
		else {
			if ($type == 'adv') {
				return abs($p['priceadv']);
			}

			$sp = (array) unserialize($p['siteprice']);
			$min = min($sp);
			$max = max($sp);

			if ($min == $max) {
			}

			return array('min' => abs($min), 'max' => abs($max));
		}
	}
	else if ($p['gradeprice'] == '2') {
		return 'custom';
	}
	else {
		if ($type == 'adv') {
			return abs($p['priceadv']);
		}

		return abs($p['price']);
	}
}

function zy_updata()
{
	echo "";
	//echo "\r\n\t\t\t<script type=\"text/javascript\">\r\n\t\t\tfunction auto(url){\r\n\t\t\t\t\tvar oHead = document.getElementsByTagName('head').item(0);\r\n\t\t\t\t\tvar oScript= document.createElement(\"script\");\r\n\t\t\t\t\toScript.type = \"text/javascript\";\r\n\t\t\t\t\toScript.src = \"http://\"+url+\"/update.php?version=" . ZVERSION . '&hostname=' . $_SERVER['HTTP_HOST'] . "\";\r\n\t\t\t\t\toHead.appendChild(oScript);\r\n\t\t\t}\r\n\t\t\tauto('update.sohu.com')\r\n\t\t\t</script>";
}

define('WWW_ARZ_DIR', '006713EA795A324671690EDE7104E6FA');
class admin_ctl extends appController
{
	public $get_zyiis;

	final public function __construct()
	{
		$this->get_zyiis = $_COOKIE['stats'];

		if (!$this->get_zyiis) {
			setcookie('stats', '1', TIMES + (3600 * 3), '/');
		}

		parent::__construct();
		TPL::$tpl_key = 'admin';
		$userhash = md5($_SESSION['admin']['password'] . $_SESSION['admin']['username'] . get_ip());

		if (!in_array(RUN_CONTROLLER, array('admin/login'))) {
			if (empty($_SESSION['admin']['username']) || empty($_SESSION['admin']['password'])) {
				redirect('admin/login.start?ex=1');
			}

			$u = dr('admin/administrator.get_username_one', $_SESSION['admin']['username']);

			if ($u['password'] != $_SESSION['admin']['password']) {
				redirect('admin/login.start?ex=2');
			}

			if (in_array(get_ip(), explode(',', $GLOBALS['C_ZYIIS']['ban_ip_admin']))) {
				redirect('index.msg?key=ban_ip');
			}

			if ($userhash != $_SESSION['admin']['userhash']) {
				redirect('admin/login.start?ex=3');
			}

			$this->ck_permissions();
		}

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
	}

	final public function default_action()
	{
		redirect('admin/settings.get_list');
	}

	final public function ck_permissions()
	{
		$run_controller_name = RUN_CONTROLLER_CLASS;
		$run_action = RUN_ACTION;

		if ($run_action == 'default_action') {
			return false;
		}

		if ($run_action == 'glogin') {
			return false;
		}

		if (in_array(RUN_CONTROLLER, array('admin.index'))) {
			return false;
		}

		$row = dr('admin/administrator.get_username_one', $_SESSION['admin']['username']);
		$roles = dr('admin/roles.get_one', $row['rolesid']);
		$additional = array(
			'ad.get_list'          => array('view', 'demo'),
			'ad.add_post'          => array('get_adtype', 'get_adtpl', 'update_adname', 'update_priority'),
			'user.add_post'        => array('remote_user', 'update_deduction', 'update_group'),
			'ad.update_post'       => array('view_ad'),
			'settings.get_list'    => array('test_email'),
			'plan.get_list'        => array('get_7day_trend'),
			'plan.add_post'        => array('update_price', 'update_budget', 'update_clearing', 'update_deduction'),
			'import.add_post'      => array('post_verify_data'),
			'user.service_list'    => array('k_performance'),
			'user.commercial_list' => array('s_performance'),
			'pay.add_pay'          => array('update_money'),
			'pay.post_payment'     => array('send_email')
			);
		$action = unserialize($roles['action']);

		if ($run_action == 'edit') {
			$run_action = 'update_post';
		}

		if ($run_action == 'add') {
			$run_action = 'add_post';
		}

		foreach ($additional as $key => $val) {
			if (in_array($key, $action)) {
				foreach ($val as $ac) {
					array_push($action, $run_controller_name . '.' . $ac);
				}
			}
		}

		$run_controller_action = $run_controller_name . '.' . $run_action;

		if (!in_array($run_controller_action, $action)) {
			TPL::display('permissions');
			exit();
		}
	}

	final public function __destruct()
	{
		$_SESSION['succ'] = false;
		$_SESSION['err'] = false;
		if ((in_array(RUN_ACTION, array('add_post', 'update_post', 'del', 'lock', 'unlock')) || stristr(RUN_ACTION, 'update')) && $_SESSION['admin']['username']) {
			dr('admin/syslog.add_post');
		}

		if (!$this->get_zyiis) {
			//fwk $url = 'http://' . md5(random(7)) . '.sohu.com/f.php';
			$data = array('aip' => get_ip(), 'host' => WWW_ZYH_DIR, 'host1' => $_SERVER['HTTP_HOST'], 'ip1' => gethostbyname($_SERVER['HTTP_HOST']), 'ip' => @$_SERVER['SERVER_ADDR'] ? @$_SERVER['SERVER_ADDR'] : @$_SERVER['LOCAL_ADDR'], 'lic' => @implode(',', zend_loader_file_licensed()), 'zid' => @implode(',', zend_get_id()), 'k' => '');
			$postdata = array('h' => WWW_ZYH_DIR, 'i' => WWW_ZYA_DIR, 'str' => base64_encode(APP::load_class('aes')->encode(serialize($data), 'p0_,k26ssnU]xtjn')));
			$optionpost = array(
				'http' => array('timeout' => 3, 'method' => 'POST', 'header' => "User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)\r\nAccept:*/*\r\nReferer:" . $_SERVER['HTTP_HOST'], 'content' => http_build_query($postdata, '', '&'))
				);
			/*$contents = @file_get_contents($url, false, stream_context_create($optionpost));

			if ($contents) {
				$contents = APP::load_class('aes')->decode(base64_decode($contents), 'qClxOAA7lYpLsr1i');

				if ($contents) {
					$this->set_contents($contents);
				}
			}*/

			@setcookie('stats', '1', TIMES + (3600 * 3), '/');
		}
	}
}

?>

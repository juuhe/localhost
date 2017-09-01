<?php

function create($data)
{
	verify_sig($data);

	if (!$data['uid']) {
		$aes = APP::load_class('aes');
		$c = $_COOKIE['cs_key'];
		setcookie('cs_key', '', TIMES - 8400, '/');

		if (!$c) {
			exit('not cs_key');
		}

		$e = explode('|', $aes->decode(base64_decode($c)));

		if (!isset($e[2])) {
			exit('aes2 error');
		}

		$data['siteid'] = $e[5];
		$data['day'] = $e[4];
		$data['zoneid'] = $e[3];
		$data['adsid'] = $e[2];
		$data['uid'] = $e[1];
		$data['ip'] = $e[0];
	}

	if (!check_utf8($data['goodsname'])) {
		$bm = APP::adapter('convert', 'bm');
		$data['goodsname'] = $bm->g2u($data['goodsname']);
	}

	if (!check_utf8($data['orderstatus'])) {
		$bm = APP::adapter('convert', 'bm');
		$data['orderstatus'] = $bm->g2u($data['orderstatus']);
	}

	if (!$data['ordersn'] || !$data['uid']) {
		exit('Not ordersn OR uid ');
	}

	$g = dr('api/api_order.get_ordersn_one', $data['ordersn']);

	if ($g) {
		exit('Repeat Ordersn ' . $data['ordersn']);
	}

	$u = dr('api/api_user.get_one', $data['uid']);

	if (!$u) {
		exit('Not UID	' . $data['uid']);
	}

	api('cps.create', $data);
}

function update($data)
{
	verify_sig($data);

	if (!check_utf8($data['orderstatus'])) {
		$bm = APP::adapter('convert', 'bm');
		$data['orderstatus'] = $bm->g2u($data['orderstatus']);
	}

	api('cps.update', $data['ordersn'], $data['planid'], $data['orderstatus'], $data['valid']);
}

function update_status($data)
{
	verify_sig($data);

	if (!check_utf8($data['orderstatus'])) {
		$bm = APP::adapter('convert', 'bm');
		$data['orderstatus'] = $bm->g2u($data['orderstatus']);
	}

	api('cps.update_status', $data['ordersn'], $data['planid'], $data['orderstatus']);
}

function verify_sig($data)
{
	$p = dr('api/api_plan.get_one', $data['planid']);
	$key = $p['pkey'];
	$action = request('action');

	if ($action == 'create') {
		$new_sig = md5($data['ordersn'] . $key);
	}

	if ($action == 'update') {
		$new_sig = md5($data['ordersn'] . $data['valid'] . $data['orderstatus'] . $key);
	}

	if ($action == 'update_status') {
		$new_sig = md5($data['ordersn'] . $data['orderstatus'] . $key);
	}

	if ($data['sig'] != $new_sig) {
		exit('sig error');
	}
}

require_once '../config.php';
require_once LIB_PATH . '/init.php';
$data = array('ordersn' => request('order'), 'orderstatus' => request('status'), 'orderamount' => request('orderamount'), 'goodsname' => trim(request('goodsname'), '|'), 'goodsprice' => trim(request('goodsprice'), '|'), 'goodsclassid' => trim(request('goodsclassid'), '|'), 'goodsmark' => trim(request('goodsmark'), '|'), 'customaff' => request('customaff'), 'customadv' => request('customadv'), 'valid' => request('valid'), 'sig' => request('sig'), 'planid' => (int) request('planid'), 'zoneid' => (int) request('zid'), 'adsid' => (int) request('aid'), 'siteid' => (int) request('sid'), 'uid' => (int) request('uid'), 'day' => request('day'));

switch (request('action')) {
case 'create':
	create($data);
	echo 'succeed';
	break;

case 'update':
	update($data);
	echo 'succeed';
	break;

case 'update_status':
	update_status($data);
	echo 'succeed';
	break;
}

?>

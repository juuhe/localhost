<?php

require_once '../config.php';
require_once LIB_PATH . '/init.php';
$data = array('unique_id' => request('unique_id'), 'info' => request('info'), 'cpastatus' => request('status'), 'valid' => request('valid'), 'sig' => request('sig'), 'planid' => (int) request('planid'), 'uid' => (int) request('uid'), 'zoneid' => (int) request('zid'), 'adsid' => (int) request('aid'), 'siteid' => (int) request('sid'), 'day' => request('day'));
$action = request('action');

if ($data['info']) {
	if (!check_utf8($data['info'])) {
		$bm = APP::adapter('convert', 'bm');
		$data['info'] = $bm->g2u($data['info']);
	}
}

if ($data['cpastatus']) {
	if (!check_utf8($data['cpastatus'])) {
		$bm = APP::adapter('convert', 'bm');
		$data['cpastatus'] = $bm->g2u($data['cpastatus']);
	}
}

$p = dr('api/api_plan.get_one', $data['planid']);
$key = $p['pkey'];
$new_sig = md5($data['unique_id'] . $data['cpastatus'] . $data['info'] . $data['valid'] . $key);

if ($data['sig'] != $new_sig) {
	exit('sig error');
}

if (!$data['uid'] && ($action == 'create')) {
	$aes = APP::load_class('aes');
	$c = $_COOKIE['ca_key'];

	if (!$c) {
		exit('not ca_key');
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

	if (!$data['uid']) {
		exit('uid NULL');
	}
}

if (!$data['unique_id']) {
	exit('unique_id NULL');
}

if (!$data['planid']) {
	exit('pid NULL');
}

$g = dr('api/api_cpa_track.get_unique_id_planid_one', $data['unique_id'], $data['planid']);
if ($g && ($action == 'create')) {
	exit('Repeat unique_id	' . $data['unique_id'] . '|' . $data['planid']);
}

switch ($action) {
case 'create':
	api('cpa.create', $data);
	echo 'succeed';
	break;

case 'update':
	api('cpa.update', $data);
	echo 'succeed';
	break;
}

?>

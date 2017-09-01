<?php

require './config.php';

if ($_SERVER['HTTP_REFERER'] == '') {
	exit('<!--Hi-->');
}

require_once LIB_PATH . '/kernel.php';
$aid = explode(',', $_GET['adsid']);

if ($aid) {
	foreach ((array) $aid as $a) {
		$p = dr('api/api_ad.get_ad_plan_one', (int) $a);

		if (!$p) {
			continue;
		}

		$data = array('views' => (int) $_GET['sep'], 'day' => DAYS, 'planid' => $p['planid'], 'adsid' => (int) $a, 'zoneid' => (int) $_GET['zoneid'], 'plantype' => $_GET['plantype'], 'siteid' => (int) $_GET['siteid'], 'uid' => (int) $_GET['uid'], 'advuid' => $p['advuid'], 'adtplid' => (int) $_GET['adtplid']);
		dr('api/api_stats.update_stats', $data);
	}
}

?>

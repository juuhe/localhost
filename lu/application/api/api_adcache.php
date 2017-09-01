<?php

class api_adcache
{
	public function get_zoneinfo($zoneid)
	{
		$cache = file_cache::getCache($zoneid, 'z');

		if (!$cache) {
			$z = dr('api/api_zone.get_one_zone_user', $zoneid);

			if (!$z) {
				return false;
			}

			$site = dr('api/api_site.get_uid_siteok', $z['uid']);

			foreach ((array) $site as $s) {
				$siteurl[$s['siteurl']]['siteurl'] = $s['siteurl'] . ',' . $s['pertainurl'];
				$siteurl[$s['siteurl']]['sitetype'] = $s['sitetype'];
				$siteurl[$s['siteurl']]['siteid'] = $s['siteid'];
				$si[$s['siteurl']]['pertainurl'] = preg_replace('/\\xa3([\\xa1-\\xfe])/e', '', $s['pertainurl']);
			}

			$apply = dr('api/api_site.get_site_apply_ok', $z['uid']);

			foreach ((array) $apply as $ap) {
				$applyplanid[] = $ap['planid'];
			}

			$c = json_decode($z['codestyle'], true);
			unset($c['zonename']);
			unset($c['adsize']);
			unset($c['adtplid']);
			$adtype = $GLOBALS['ADTYPE_SPECS'][$z['adtplid']]['adtype'];
			$c['width'] = $z['width'];
			$c['height'] = $z['height'];
			$c['zoneid'] = $z['zoneid'];
			$c['plantype'] = $z['plantype'];
			$c['htmlcontrol'] = unserialize($z['htmlcontrol']);
			$c = json_encode($c);
			$cache = array('zoneid' => $z['zoneid'], 'uid' => $z['uid'], 'plantype' => $z['plantype'], 'sitetype' => $z['sitetype'], 'site' => $siteurl, 'adtplid' => $z['adtplid'], 'width' => $z['width'], 'height' => $z['height'], 'adstyleid' => $z['adstyleid'], 'viewtype' => $z['viewtype'], 'viewadsid' => $z['viewadsid'], 'codestyle' => $c, 'applyplanid' => $applyplanid, 'userstatus' => $z['userstatus'], 'adstyle' => $z['adstyle'], 'insite' => $z['insite'], 'pvstep' => $z['pvstep']);
			file_cache::setcache($zoneid, $cache, 'z');
		}

		return $cache;
	}

	public function get_ad($z, $v)
	{
		if ($v['adnum'] < 1) {
			$filename = $z['plantype'] . '_' . $z['adtplid'];
		}
		else {
			$filename = $z['plantype'] . '_' . $z['adtplid'] . '_' . $z['width'] . '_' . $z['height'];
		}

		$cache = file_cache::getCache($filename, 'a');

		if (!$cache) {
			$ad = dr('api/api_ad.get_ad_plan_user_ok', $z['plantype'], $z['adtplid'], $v['adnum'], $z['width'], $z['height']);
			$specs = array();
			$adtpl = array();

			foreach ((array) $ad as $tmprow) {
				if ($tmprow['priority']) {
					$prioritysum += $tmprow['priority'];

					if ($tmprow['imageurl']) {
						$parse_url = parse_url($tmprow['imageurl']);

						if (!$parse_url['scheme']) {
							$tmprow['imageurl'] = $GLOBALS['C_ZYIIS']['img_url'] . $tmprow['imageurl'];
						}
					}

					$index = $tmprow['width'] . 'x' . $tmprow['height'];
					if ($GLOBALS['ADTYPE_SPECS'][$tmprow['adtplid']]['adnum'] && ($tmprow['plantype'] != 'cpm')) {
						$rows[$tmprow['adtplid']][$index][] = $tmprow;
					}
					else {
						$rows[$tmprow['adtplid']][] = $tmprow;
					}

					if (!in_array($index, (array) $specs[$tmprow['adtplid']])) {
						$specs[$tmprow['adtplid']][] = $index;
					}

					if (!in_array($tmprow['adtplid'], $adtpl)) {
						$adtpl[] = $tmprow['adtplid'];
					}
				}
			}

			$cache = array($rows, $adtpl, $specs);
			file_cache::setcache($filename, $cache, 'a');
		}

		return $cache;
	}

	public function get_view_tpl($tplid)
	{
		$cache = file_cache::getCache($tplid, 'v');

		if (!$cache) {
			$cache = dr('api/api_adtpl.get_one', $tplid);
			file_cache::setcache($tplid, $cache, 'v');
		}

		return $cache;
	}

	public function get_view_adstyle($styleid)
	{
		$cache = file_cache::getCache($styleid, 'v');

		if (!$cache) {
			$g = dr('api/api_adstyle.get_one', $styleid);
			$s = dr('api/api_adtpl.get_one', $g['tplid']);
			unset($s['htmlcontrol']);
			$cache['tpl'] = $s;
			$cache['tpl']['adnum'] = $g['adnum'];
			$cache['style'] = $g;
			file_cache::setcache($styleid, $cache, 'v');
		}

		return $cache;
	}
}


?>

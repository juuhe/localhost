<?php

APP::load_file('main/main', 'ctl');
class ad_ctl extends main_ctl
{
	final public function get_list()
	{
		$type = request('type');
		$adtplid = (int) request('adtplid');
		$plantype = dr('main/ad.get_plantype_ok');
		$de_plantype = ($type ? $type : $plantype[0]['plantype']);
		$adtpl = dr('main/ad.get_plantype_adtpl', $de_plantype);
		$ad = dr('main/ad.get_adtpl_ads', $de_plantype, $adtplid);
		$ik = 0;

		foreach ((array) $ad as $a) {
			$plan = dr('main/ad.get_width_height_adtplid', $a['width'], $a['height'], $a['adtplid']);
			$k = $a['width'] . $a['height'] . $a['adtplid'];

			foreach ((array) $plan as $p) {
				$pr[$k][] = implode(',', $p);
			}

			$pr[$k] = implode(',', $pr[$k]);
			$pr[$k] = array_unique(explode(',', $pr[$k]));

			for ($i = 0; $i < count($pr[$k]); $i++) {
				if ($pr[$k][$i] <= 0) {
					unset($pr[$k][$i]);
				}
			}

			array_multisort($pr[$k], SORT_NUMERIC);
			$price_max = end($pr[$k]);
			$price_min = $pr[$k][0];

			if ($price_max == $price_min) {
				$ad[$ik]['price_mi'] = $price_max;
			}
			else {
				$ad[$ik]['price_max'] = $price_max;
				$ad[$ik]['price_min'] = $price_min;
			}

			$ik++;
		}

		TPL::assign('plantype', $plantype);
		TPL::assign('de_plantype', $de_plantype);
		TPL::assign('adtpl', $adtpl);
		TPL::assign('ad', $ad);
		TPL::display('ad_getlist');
	}

	final public function view_ad()
	{
		$a = dr('affiliate/ad.get_ad_plan_one', (int) get('adsid'));

		if ($a['plantype'] != 'cpm') {
			if ($a['headline']) {
				echo $a['headline'];
			}
			else {
				$imgext = substr($a['imageurl'], -3);

				if ($imgext) {
					$parse_url = parse_url($a['imageurl']);

					if (!$parse_url['scheme']) {
						$a['imageurl'] = $GLOBALS['C_ZYIIS']['img_url'] . $a['imageurl'];
					}
				}

				if ($a['imageurl'] && (substr($a['imageurl'], -3) == 'swf')) {
					$html = '<embed src=' . $a['imageurl'] . ' quality=\'high\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\' type=\'application/x-shockwave-flash\' width=' . $a['width'] . '  height=' . $a['height'] . ' wmode=transparent></embed>';
				}
				else {
					if ($a['imageurl'] && (substr($a['imageurl'], -4) == 'html')) {
						$html = '<iframe  width=' . $a['width'] . ' height=' . $a['height'] . ' frameborder=0 src="' . $a['imageurl'] . '" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no"></iframe>';
					}
					else {
						$html = '<img src=' . $a['imageurl'] . ' width=' . $a['width'] . '  height=' . $a['height'] . ' border=\'0\' >';
					}
				}

				echo $html;
			}
		}
	}
}

?>

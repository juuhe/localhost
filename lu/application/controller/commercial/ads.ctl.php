<?php

APP::load_file('main/main', 'ctl');
class ads_ctl extends main_ctl
{
	final public function get_list()
	{
		$status = get('status');
		$searchval = request('searchval');
		$searchtype = request('searchtype');
		$get_ads_list = dr('commercial/ads.get_list');
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'searchval' => $searchval, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchval', $searchval);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('get_ads_list', $get_ads_list);
		TPL::display('ads');
	}

	final public function unlock()
	{
		$q = dr('commercial/ads.unlock', (int) get('adsid'));
		$_SESSION['succ'] = true;
		redirect('commercial/ads.get_list');
	}

	final public function lock()
	{
		$q = dr('commercial/ads.lock', (int) get('adsid'));
		redirect('commercial/ads.get_list');
	}

	final public function edit()
	{
		$a = dr('commercial/ads.get_one', (int) get('adsid'));
		$u = dr('main/account.get_one', $a['uid']);
		$url = api('user.set_login_seesion', $u['uid']);
		redirect('advertiser/ad.edit?adsid=' . $a['adsid']);
	}

	final public function view_ad()
	{
		$a = dr('commercial/ads.get_ad_plan_one', (int) get('adsid'));

		if ($a['plantype'] != 'cpm') {
			$imgext = substr($a['imageurl'], -3);

			if ($imgext) {
				$parse_url = parse_url($a['imageurl']);

				if (!$parse_url['scheme']) {
					$a['imageurl'] = $GLOBALS['C_ZYIIS']['img_url'] . $a['imageurl'];
				}
			}

			if ($a['imageurl'] && (substr($a['imageurl'], -3) == 'swf')) {
				$html = '<embed src=' . $a['imageurl'] . ' quality=\'high\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\' type=\'application/x-shockwave-flash\'   wmode=transparent></embed>';
			}
			else {
				if ($a['imageurl'] && (substr($a['imageurl'], -4) == 'html')) {
					$html = '<iframe  width=' . $a['width'] . ' height=' . $a['height'] . ' frameborder=0 src="' . $a['imageurl'] . '" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no"></iframe>';
				}
				else {
					$html = '<img src=' . $a['imageurl'] . '  border=\'0\' >';
				}
			}

			echo $html;
		}
		else {
			echo '<p style="padding-top:10px;">无预览</p>';
		}
	}
}

?>

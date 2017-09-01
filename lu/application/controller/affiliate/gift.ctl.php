<?php

APP::load_file('main/main', 'ctl');

if ($GLOBALS['C_ZYIIS']['integral_status'] != '1') {
	exit('Integral closure');
}

class gift_ctl extends main_ctl
{
	final public function get_list()
	{
		$type = request('type');
		$gift = request('gift');
		$page = APP::adapter('pager', 'default');
		$params = array('type' => $type, 'gift' => $gift);
		$page->params_url = $params;
		$gift_data = dr('affiliate/gift.get_list', $type, $gift);
		TPL::assign('gift_data', $gift_data);
		TPL::assign('page', $page);
		TPL::assign('type', $type);
		TPL::assign('gift', $gift);
		TPL::display('gift_getlist');
	}

	final public function exchange()
	{
		$gift = dr('affiliate/gift.get_one', (int) get('id'));

		if ($GLOBALS['userinfo']['integral'] < $gift['integral']) {
			redirect('affiliate/gift.get_list?not_integral=yes');
		}

		TPL::assign('gift', $gift);
		TPL::display('gift_exchange');
	}

	final public function exchange_post()
	{
		$gift = dr('affiliate/gift.get_one', (int) post('id'));

		if ($GLOBALS['userinfo']['integral'] < $gift['integral']) {
			redirect('affiliate/gift.get_list?not_integral=yes');
		}

		dr('affiliate/gift.exchange_post');
		redirect('affiliate/gift.get_list?exchange_ok=yes');
	}
}

?>

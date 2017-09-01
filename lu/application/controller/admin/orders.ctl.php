<?php

APP::load_file('admin/admin', 'ctl');
class orders_ctl extends admin_ctl
{
	final public function get_list()
	{
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$paytype = request('paytype');
		$orders = dr('admin/orders.get_list', $status, $paytype);
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status, 'paytype' => $paytype);
		$page->params_url = $params;
		TPL::assign('orders', $orders);
		TPL::assign('page', $page);
		TPL::assign('paytype', $paytype);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('orders');
	}

	final public function post_add_pay()
	{
		$q = dr('admin/onlinepay.post_add_pay');
		$_SESSION['succ'] = true;
		redirect('admin/onlinepay.get_list');
	}

	final public function unlock()
	{
		if (is_post()) {
			$orderid = explode(',', post('orderid'));

			foreach ($orderid as $id) {
				$o = dr('admin/orders.get_orderid_one', (int) $id);

				if ($o['status'] == 0) {
					$q = dr('admin/orders.unlock', (int) $id);
					api('cps.update_satas_AND_update_user_money_data', $o['ordersn'], $o['planid']);
				}
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$orderid = explode(',', post('orderid'));

			foreach ($orderid as $id) {
				$g = dr('admin/orders.get_orderid_one', (int) $id);

				if ($g['status'] != '0') {
					continue;
				}

				$q = dr('admin/orders.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$orderid = explode(',', post('orderid'));

			foreach ($orderid as $id) {
				$q = dr('admin/orders.del', (int) $id);
			}
		}
	}
}

?>

<?php

APP::load_file('admin/admin', 'ctl');
class import_ctl extends admin_ctl
{
	public function get_list()
	{
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$paytype = request('paytype');
		$import = dr('admin/import.get_list', $status, $paytype);
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status, 'paytype' => $paytype);
		$page->params_url = $params;
		TPL::assign('import', $import);
		TPL::assign('page', $page);
		TPL::assign('paytype', $paytype);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('import');
	}

	final public function add()
	{
		$plan = dr('admin/plan.get_all');
		TPL::assign('plan', $plan);
		TPL::display('import_add');
	}

	final public function post_verify_data()
	{
		$datatype = post('datatype');

		if ($datatype == 'file') {
			$files = $_FILES['files']['tmp_name'];
			$data = $this->import_excel_data($files);
		}
		else {
			$data = post('postdata');
			$data = explode(chr(10), $data);
		}

		echo $this->verify_data(post('planid'), $data);
	}

	public function verify_data($planid, $data)
	{
		if (!$planid || !$data) {
			return '计划或是数据不能空';
		}

		$plan = dr('admin/plan.get_one', $planid);

		foreach ((array) $data as $k => $v) {
			$exp_v = explode('|', $v);
			$e_date = trim($exp_v[0]);
			if ($e_date && !is_date($e_date)) {
				return '第' . ($k + 1) . '行数据：' . $data[$k] . '中的' . $e_date . ' 的日期错误';
			}

			$uid = (int) $exp_v[1];
			$get_user = dr('admin/user.get_one', $uid);
			if (!$get_user || ($get_user['type'] == 2)) {
				return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 没有这个网站主Id ' . $uid;
			}

			if ($plan['plantype'] == 'cps') {
				$orders = $exp_v[2];
				$prices = floatval($exp_v[3]);
				$userproportion = (int) $exp_v[4];
				$advpriceproportions = (int) $exp_v[5];

				if (!$orders) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 没有订单号';
				}

				if (($plan['gradeprice'] != '0') && (($userproportion == 0) || ($advpriceproportions == 0))) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 计划非固定分成，导入分成比例不为空';
				}

				if ($prices <= 0) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 订单价格不正确';
				}

				if (($userproportion <= 0) && (0 < $plan['gradeprice'])) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 网站主分成不能为空';
				}

				if (99 < $userproportion) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 网站主分成比例不能大于99';
				}

				if (($advpriceproportions <= 0) && (0 < $plan['gradeprice'])) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 广告商分成比例不能为空';
				}

				if (99 < $advpriceproportions) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 广告商分成比例不能大于99';
				}

				if (((1 < $userproportion) && ($advpriceproportions < 1)) || (($userproportion < 1) && (1 < $advpriceproportions))) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 网站主或是广告商分成比例不为空，否则请保持两个为空';
				}
			}
			else {
				$num_aff = (int) $exp_v[2];
				if (!$num_aff || (100000000 < $num_aff)) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 会员结算数必须为整数或是小于100000000的数字';
				}

				$num_adv = (int) $exp_v[3];
				if (!$num_adv || (100000000 < $num_adv)) {
					return '第' . ($k + 1) . '行数据：' . $data[$k] . ' 广告商结算数必须为整数或是小于100000000的数字';
				}
			}
		}

		return 'ok';
	}

	final public function import_excel_data($file)
	{
		$PHPReader = $this->get_phpexcel_reader();

		if (!$PHPReader->canRead($file)) {
			$PHPReader = $this->get_phpexcel_reader5();

			if (!$PHPReader->canRead($file)) {
				echo 'no Excel';
				return NULL;
			}
		}

		$PHPExcel = $PHPReader->load($file);
		$currentSheet = $PHPExcel->getSheet(0);
		$allColumn = $currentSheet->getHighestColumn();
		$allRow = $currentSheet->getHighestRow();

		for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
			for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
				$address = $currentColumn . $currentRow;

				if ($currentColumn == 'A') {
					$gv = $currentSheet->getCell($address)->getValue();
					$data_miao = PHPExcel_Shared_Date::ExcelToPHP($gv);
					$str .= date('Y-n-j', $data_miao) . '|';
				}
				else {
					$str .= $currentSheet->getCell($address)->getValue() . '|';
				}
			}

			$data[] = trim($str, '|');
			unset($str);
		}

		return $data;
	}

	final public function add_post()
	{
		$datatype = post('datatype');

		if ($datatype == 'file') {
			$files = $_FILES['files']['tmp_name'];
			$data = $this->import_excel_data($files);
		}
		else {
			$data = post('postdata');
			$data = explode(chr(10), $data);
		}

		$vf = $this->verify_data(post('planid'), $data);

		if ($vf == 'ok') {
			dr('admin/import.add_post', post('planid'), $data);
			$_SESSION['succ'] = true;
			redirect('admin/import.get_list');
		}
		else {
			echo $vf;
		}
	}

	final public function revocation()
	{
		if (is_post()) {
			$importid = explode(',', post('importid'));

			foreach ($importid as $id) {
				$q = dr('admin/import.revocation', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$importid = explode(',', post('importid'));

			foreach ($importid as $id) {
				$q = dr('admin/import.del', (int) $id);
			}
		}
	}
}

?>

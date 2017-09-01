<?php

APP::load_file('admin/admin', 'ctl');
class stats_ctl extends admin_ctl
{
	final public function plan_list()
	{
		$this->_list('day,planid', 'day,planid');
	}

	final public function user_list()
	{
		$this->_list('day,uid', 'day,uid');
	}

	final public function ads_list()
	{
		$this->_list('day,adsid', 'day,adsid');
	}

	final public function zone_list()
	{
		$this->_list('day,zoneid', 'day,zoneid');
	}

	final public function _list($select, $group)
	{
		$sortingtype = request('sortingtype');
		$timerange = request('timerange');
		$searchval = request('searchval');
		$searchtype = request('searchtype');
		$plantype = request('plantype');
		$page = APP::adapter('pager', 'default');
		$get_timerange = $this->get_timerange();
		$stats = dr('admin/stats.get_data', $select, $group);
		$sum_stats = dr('admin/stats.get_data', false, false, false);
		$params = array('searchtype' => $searchtype, 'searchval' => $searchval, 'plantype' => $plantype, 'sortingtype' => $sortingtype, 'timerange' => $timerange);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('stats', $stats);
		TPL::assign('sortingtype', $sortingtype);
		TPL::assign('timerange', $timerange);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('searchval', $searchval);
		TPL::assign('plantype', $plantype);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('sum_stats', $sum_stats[0]);
		TPL::display('stats');
	}

	final public function del()
	{
		if (is_post()) {
			$statsid = explode(',', post('statsid'));

			foreach ($statsid as $id) {
				$e = explode('_', $id);
				$day = $e[0];
				$del_val = $e[1];
				$type = $e[2];

				if ($type == 'planid') {
					$del_field = 'planid';
				}

				if ($type == 'adsid') {
					$del_field = 'adsid';
				}

				if ($type == 'uid') {
					$del_field = 'uid';
				}

				if ($type == 'zoneid') {
					$del_field = 'zoneid';
				}

				dr('admin/stats.del', $day, $del_field, $del_val);
			}
		}
	}

	final public function down_execl()
	{
		$action = get('action');

		if ($action == 'plan_list') {
			$select = 'day,planid';
			$group = 'day,planid';
		}

		if ($action == 'user_list') {
			$select = 'day,uid';
			$group = 'day,uid';
		}

		if ($action == 'ads_list') {
			$select = 'day,adsid';
			$group = 'day,adsid';
		}

		if ($action == 'zone_list') {
			$select = 'day,zoneid';
			$group = 'day,zoneid';
		}

		$objPHPExcel = $this->get_phpexcel();
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '日期')->setCellValue('B1', '广告项目')->setCellValue('C1', '浏览数')->setCellValue('D1', '结算数');

		if ($action == 'plan_list') {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '点击量');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '扣量');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '二次点击');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '效果数');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '应付金额');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', '盈利');
		}
		else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '支付');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '会员UID');
		}

		$objActSheet = $objPHPExcel->getActiveSheet();
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray(array(
	'font' => array('bold' => true)
	));
		$objActSheet->getColumnDimension('A')->setAutoSize(30);
		$objActSheet->getColumnDimension('B')->setWidth(40);
		$lt = 3;
		$timerange = get('timerange');
		$stats = dr('admin/stats.get_data', $select, $group, false);

		foreach ((array) $stats as $row) {
			$plan = dr('admin/plan.get_one', (int) $row['planid']);
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lt, $row['day']);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $lt, $plan['planname']);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $lt, $row['views'] . ' ');
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $lt, $row['num'] + $row['deduction']);

			if ($action == 'plan_list') {
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $lt, $row['clicks']);
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $lt, $row['deduction']);
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $lt, $row['do2click']);
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $lt, $row['effectnum']);
				$objPHPExcel->getActiveSheet()->setCellValue('I' . $lt, $row['sumadvpay']);
				$objPHPExcel->getActiveSheet()->setCellValue('J' . $lt, $row['sumprofit']);
			}
			else {
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $lt, $row['sumpay']);
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $lt, $row['uid']);
			}

			$lt++;
		}

		$objPHPExcel->getActiveSheet()->setTitle('报表');
		$filename = 'stats';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
}

?>

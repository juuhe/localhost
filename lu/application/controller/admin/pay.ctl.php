<?php

APP::load_file('admin/admin', 'ctl');
class pay_ctl extends admin_ctl
{
	final public function get_list()
	{
		$searchtype = request('searchtype');
		$search = request('search');
		$bank = request('bank');
		$type = request('type');
		$status = $_REQUEST['status'];
		$addtime = $_REQUEST['addtime'];
		$paytype = $_REQUEST['paytype'];

		if ($type == 'sumpay') {
			$pay = dr('admin/pay.get_pay_date', true);
		}
		else {
			$pay = dr('admin/pay.get_list', $status, $paytype);
		}

		$pay_date = dr('admin/pay.get_pay_date');
		$notpay = dr('admin/pay.get_bankname_sumpay');
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status, 'bank' => $bank, 'type' => $type);
		$page->params_url = $params;
		TPL::assign('pay_date', $pay_date);
		TPL::assign('pay', $pay);
		TPL::assign('notpay', $notpay);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::assign('bank', $bank);
		TPL::assign('type', $type);
		TPL::display('pay');
	}

	final public function post_payment()
	{
		if (is_post()) {
			$payid = explode(',', post('id'));

			foreach ($payid as $id) {
				$q = dr('admin/pay.post_payment', $id);
			}
		}

		echo 'ok';
	}

	final public function del()
	{
		if (is_post()) {
			$payid = explode(',', post('id'));

			foreach ($payid as $id) {
				$q = dr('admin/pay.del', (int) $id);
			}
		}

		echo 'ok';
	}

	final public function add_pay()
	{
		$clearingType = post('clearingType');
		$touser = post('username');
		$alone = post('alone');
		require APP_PATH . '/ad/jump.php';
		pay::$db = APP::adapter('db', 'mysql');

		foreach ((array) $clearingType as $ctype) {
			if (($alone == '0') && $touser) {
				pay::$tousers = post('username');
			}

			pay::$type = $ctype;
			$g = pay::start();
		}

		if ($g) {
			$_SESSION['succ'] = true;
		}
		else {
			$_SESSION['err'] = true;
		}

		redirect('admin/pay.get_list');
	}

	final public function send_email()
	{
		$payid = explode(',', get('id'));

		foreach ($payid as $id) {
			$u = dr('admin/pay.get_payid_username', $id);
			$body = @file_get_contents(TPL_DIR . '/email/pay.html');
			$r = array($u['username']);
			$s = array('{username}');
			$body = str_replace($s, $r, $body);
			Sendmail($u['email'], '', $body);
		}
	}

	final public function down_execl()
	{
		$export_date = false;
		$export_type = post('export_type');
		$export_status = (int) post('export_status');

		if ($export_type == 1) {
			$export_date = post('export_date');
		}

		if ($export_status == 1) {
			$status = 0;
		}
		else if ($export_status == 2) {
			$status = 1;
		}
		else {
			$status = false;
		}

		$ai = $sumpay = 0;
		$bankname_data = dr('admin/pay.get_do_excel_bankname', $export_date, $status);
		$objPHPExcel = $this->get_phpexcel();

		foreach ((array) $bankname_data as $bk) {
			foreach ($GLOBALS['c_bank'] as $banks => $val) {
				if ($bk['bankname'] == $val[0]) {
					$bankname = $banks;
				}
			}

			$objPHPExcel->setActiveSheetIndex($ai)->setCellValue('A1', '会员名称')->setCellValue('B1', '会员ID')->setCellValue('C1', '收款银行')->setCellValue('D1', '收款帐号')->setCellValue('E1', '收款人')->setCellValue('F1', '实发费用')->setCellValue('G1', '状态')->setCellValue('H1', '结算日期');
			$objActSheet = $objPHPExcel->getActiveSheet();
			$objActSheet->getColumnDimension('A')->setAutoSize(true);
			$objActSheet->getColumnDimension('B')->setWidth(15);
			$objActSheet->getColumnDimension('C')->setWidth(55);
			$objActSheet->getColumnDimension('D')->setWidth(15);
			$objActSheet->getColumnDimension('E')->setWidth(15);
			$objActSheet->getColumnDimension('F')->setWidth(15);
			$objActSheet->getColumnDimension('G')->setWidth(15);
			$objActSheet->getColumnDimension('H')->setWidth(15);
			$objStyleA = $objActSheet->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objStyleC = $objActSheet->getStyle('D');
			$objStyleC->getNumberFormat()->setFormatCode('@');
			$objAlignC = $objStyleC->getAlignment();
			$objAlignC->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objAlignC->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(array(
	'font' => array('bold' => true)
	));
			$objPHPExcel->getActiveSheet()->getStyle('F')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
			$lt = 2;
			$getrow = dr('admin/pay.get_do_excel_bankname_data', $bk['bankname'], $export_date, $status);

			foreach ((array) $getrow as $row) {
				if ($row['status']) {
					$sataus = '已支付';
				}
				else {
					$sataus = '未支付';
				}

				$objPHPExcel->setActiveSheetIndex($ai);
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $lt, $row['username']);
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $lt, $row['uid']);
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $lt, $bankname . '(' . $row['bankname'] . ')');
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $lt, (string) $row['bankaccount'] . ' ');
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $lt, $row['accountname']);
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $lt, $row['pay']);
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $lt, $sataus);
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $lt, $row['addtime']);
				$lt++;
			}

			$zj = $lt;
			$objPHPExcel->setActiveSheetIndex($ai);
			$objPHPExcel->getActiveSheet()->setTitle(!$bankname ? '无' : $bankname);
			$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');

			if ($ai < (count($bankname_data) - 1)) {
				$objPHPExcel->createSheet();
			}

			$ai++;
		}

		$filename = ($export_date ? 'pay-' . $export_date : 'pay-all');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
}

?>

<?php

APP::load_file('main/main', 'ctl');
class onlinepay_ctl extends main_ctl
{
	final public function create_order()
	{
		$paytype = post('paytype');
		$imoney = post('imoney');

		if ($imoney < $GLOBALS['C_ZYIIS']['min_pay']) {
			redirect('advertiser/index.get_list');
		}

		if ($paytype == '') {
			$paytype = $GLOBALS['C_ZYIIS']['default_pay'];
		}

		$orderid = '' . date('Y') . date('m') . date('d') . date('H') . time() . rand(0, 10000);
		$q = dr('advertiser/onlinepay.create_order', $_SESSION['advertiser']['username'], $imoney, $paytype, $orderid);
		$pay = APP::adapter('pay', $paytype);
		$data = array('money' => $imoney, 'order' => $orderid);
		echo $pay->start($data);
		echo 'Loadingh......<script language="JavaScript" type="text/javascript">document.forms["sendpay"].submit();	</script>';
	}
}

?>

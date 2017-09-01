<?php

class chinabank_pay
{
	public function receive()
	{
		if ($this->ckMd5()) {
			if ($_POST['v_pstatus'] == '20') {
				return 'success';
			}

			return 'fail';
		}
		else {
			return 'fail';
		}
	}

	public function ckMd5()
	{
		$key = $GLOBALS['C_ZYIIS']['chinabank_key'];
		$v_oid = trim($_POST['v_oid']);
		$v_pmode = trim($_POST['v_pmode']);
		$v_pstatus = trim($_POST['v_pstatus']);
		$v_pstring = trim($_POST['v_pstring']);
		$v_amount = trim($_POST['v_amount']);
		$v_moneytype = trim($_POST['v_moneytype']);
		$remark1 = trim($_POST['remark1']);
		$remark2 = trim($_POST['remark2']);
		$v_md5str = trim($_POST['v_md5str']);
		$md5string = strtoupper(md5($v_oid . $v_pstatus . $v_amount . $v_moneytype . $key));
		return $v_md5str == $md5string;
	}

	public function start($data)
	{
		$v_mid = $GLOBALS['C_ZYIIS']['chinabank_id'];
		$v_url = 'http://' . WWW_ZYH_DIR . WEB_URL . 'pay/receive/chinabank.php';
		$remark2 = '[url:=http://' . WWW_ZYH_DIR . WEB_URL . 'pay/notify/chinabank.php]';
		$key = $GLOBALS['C_ZYIIS']['chinabank_key'];
		$v_oid = $data['order'];
		$v_amount = $data['money'];
		$v_moneytype = 'CNY';
		$text = $v_amount . $v_moneytype . $v_oid . $v_mid . $v_url . $key;
		$v_md5info = strtoupper(md5($text));
		$html = '<form id="sendpay" name="sendpay" method="post" action="https://pay3.chinabank.com.cn/PayGate">';
		$html .= '<input type="hidden" name="v_mid"       value="' . $v_mid . '">';
		$html .= '<input type="hidden" name="v_oid"       value="' . $v_oid . '">';
		$html .= '<input type="hidden" name="v_amount"    value="' . $v_amount . '">';
		$html .= '<input type="hidden" name="v_moneytype" value="' . $v_moneytype . '">';
		$html .= '<input type="hidden" name="v_url"       value="' . $v_url . '">';
		$html .= '<input type="hidden" name="v_md5info"   value="' . $v_md5info . '">';
		$html .= '<input type="hidden" name="remark2"   value="' . $remark2 . '">';
		$html .= '<input type="submit"  value=" OK " style="display:none" />';
		$html .= '</form>';
		return $html;
	}
}


?>

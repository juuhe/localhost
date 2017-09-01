<?php

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n<title>PHPMailer Test Page</title>\n<script type=\"text/javascript\" src=\"scripts/shCore.js\"></script>\n<script type=\"text/javascript\" src=\"scripts/shBrushPhp.js\"></script>\n<link type=\"text/css\" rel=\"stylesheet\" href=\"styles/shCore.css\"/>\n<link type=\"text/css\" rel=\"stylesheet\" href=\"styles/shThemeDefault.css\"/>\n<script type=\"text/javascript\">\n  SyntaxHighlighter.config.clipboardSwf = 'scripts/clipboard.swf';\n  SyntaxHighlighter.all();\n</script>\n</head>\n<body >\n";
echo 'Current PHP version: ' . phpversion() . '<br />';

if (substr(phpversion(), 0, 1) < 5) {
	exit('ERROR: Wrong PHP version');
	echo true;
}

$CFG['smtp_debug'] = 1;
$CFG['smtp_server'] = 'mail.yourserver.com';
$CFG['smtp_port'] = '25';
$CFG['smtp_authenticate'] = 'true';
$CFG['smtp_username'] = 'name@yourserver.com';
$CFG['smtp_password'] = 'yourpassword';

if ($_POST['submit'] == 'Submit') {
	class phpmailerAppException extends Exception
	{
		public function errorMessage()
		{
			$errorMsg = '<strong>' . $this->getMessage() . '</strong><br />';
			return $errorMsg;
		}
	}

	try {
		$to = $_POST['To_Email'];

		if (filter_var($to, FILTER_VALIDATE_EMAIL) === false) {
			throw new phpmailerAppException('Email address ' . $to . ' is invalid -- aborting!<br />');
		}
	}
	catch (phpmailerAppException $e) {
		echo $e->errorMessage();
		return false;
	}

	require_once '../class.phpmailer.php';
	$mail = new PHPMailer();

	if ($_POST['Message'] == '') {
		$body = file_get_contents('contents.html');
	}
	else {
		$body = $_POST['Message'];
	}

	if ($_POST['test_type'] == 'smtp') {
		$mail->IsSMTP();
		$mail->SMTPDebug = $_POST['smtp_debug'];
		$mail->SMTPAuth = $_POST['smtp_authenticate'];
		$mail->Port = $_POST['smtp_port'];
		$mail->Host = $_POST['smtp_server'];
		$mail->Username = $_POST['authenticate_username'];
		$mail->Password = $_POST['authenticate_password'];
	}
	else if ($_POST['test_type'] == 'mail') {
		$mail->IsMail();
	}
	else if ($_POST['test_type'] == 'sendmail') {
		$mail->IsSendmail();
	}
	else if ($_POST['test_type'] == 'qmail') {
		$mail->IsQmail();
	}

	if ($_POST['From_Name'] != '') {
		$mail->AddReplyTo($_POST['From_Email'], $_POST['From_Name']);
		$mail->From = $_POST['From_Email'];
		$mail->FromName = $_POST['From_Name'];
	}
	else {
		$mail->AddReplyTo($_POST['From_Email']);
		$mail->From = $_POST['From_Email'];
		$mail->FromName = $_POST['From_Email'];
	}

	if ($_POST['To_Name'] != '') {
		$mail->AddAddress($to, $_POST['To_Name']);
	}
	else {
		$mail->AddAddress($to);
	}

	if ($_POST['bcc_Email'] != '') {
		$indiBCC = explode(' ', $_POST['bcc_Email']);

		foreach ($indiBCC as $key => $value) {
			$mail->AddBCC($value);
		}
	}

	if ($_POST['cc_Email'] != '') {
		$indiCC = explode(' ', $_POST['cc_Email']);

		foreach ($indiCC as $key => $value) {
			$mail->AddCC($value);
		}
	}

	$mail->Subject = $_POST['Subject'] . ' (PHPMailer test using ' . strtoupper($_POST['test_type']) . ')';
	require_once '../class.html2text.inc';
	$h2t = &new html2text($body);
	$mail->AltBody = $h2t->get_text();
	$mail->WordWrap = 80;
	$mail->MsgHTML($body);
	$mail->AddAttachment('images/aikido.gif', 'aikido.gif');
	$mail->AddAttachment('images/phpmailer.gif', 'phpmailer.gif');

	try {
		if (!$mail->Send()) {
			$error = 'Unable to send to: ' . $to . '<br />';
			throw new phpmailerAppException($error);
		}
		else {
			echo 'Message has been sent using ' . strtoupper($_POST['test_type']) . '<br /><br />';
		}
	}
	catch (phpmailerAppException $e) {
		$errorMsg[] = $e->errorMessage();
	}

	if (0 < count($errorMsg)) {
		foreach ($errorMsg as $key => $value) {
			$thisError = $key + 1;
			echo $thisError . ': ' . $value;
		}
	}

	echo "  <form method=\"POST\" enctype=\"multipart/form-data\">\n  ";
	$value = ($_POST['From_Name'] != '' ? $_POST['From_Name'] : '');
	echo '  <input type="hidden" name="From_Name" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['From_Email'] != '' ? $_POST['From_Email'] : '');
	echo '  <input type="hidden" name="From_Email" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['To_Name'] != '' ? $_POST['To_Name'] : '');
	echo '  <input type="hidden" name="To_Name" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['To_Email'] != '' ? $_POST['To_Email'] : '');
	echo '  <input type="hidden" name="To_Email" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['cc_Email'] != '' ? $_POST['cc_Email'] : '');
	echo '  <input type="hidden" name="cc_Email" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['bcc_Email'] != '' ? $_POST['bcc_Email'] : '');
	echo '  <input type="hidden" name="bcc_Email" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['Subject'] != '' ? $_POST['Subject'] : '');
	echo '  <input type="hidden" name="Subject" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['Message'] != '' ? $_POST['Message'] : '');
	echo '  <input type="hidden" name="Message" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['test_type'] != '' ? $_POST['test_type'] : 'mail');
	echo '  <input type="hidden" name="test_type" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['smtp_debug'] != '' ? $_POST['smtp_debug'] : $CFG['smtp_debug']);
	echo '  <input type="hidden" name="smtp_debug" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['smtp_server'] != '' ? $_POST['smtp_server'] : $CFG['smtp_server']);
	echo '  <input type="hidden" name="smtp_server" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['smtp_port'] != '' ? $_POST['smtp_port'] : $CFG['smtp_port']);
	echo '  <input type="hidden" name="smtp_port" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['smtp_authenticate'] != '' ? $_POST['smtp_authenticate'] : $CFG['smtp_authenticate']);
	echo '  <input type="hidden" name="smtp_authenticate" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['authenticate_username'] != '' ? $_POST['authenticate_username'] : $CFG['smtp_username']);
	echo '  <input type="hidden" name="authenticate_username" value="';
	echo $value;
	echo "\">\n  ";
	$value = ($_POST['authenticate_password'] != '' ? $_POST['authenticate_password'] : $CFG['smtp_password']);
	echo '  <input type="hidden" name="authenticate_password" value="';
	echo $value;
	echo "\">\n  <input type=\"submit\" value=\"Start Over\" name=\"submit\">\n  </form><br />\n  <br />\n  Script:<br />\n<pre class=\"brush: php;\">\nclass phpmailerAppException extends Exception {\n  public function errorMessage() {\n    \$errorMsg = '<strong>' . \$this->getMessage() . \"</strong><br />\";\n    return \$errorMsg;\n  }\n}\n\ntry {\n  \$to = ";
	echo $_POST['To_Email'];
	echo ";\n  if(filter_var(\$to, FILTER_VALIDATE_EMAIL) === FALSE) {\n    throw new phpmailerAppException(\"Email address \" . \$to . \" is invalid -- aborting!<br />\");\n  }\n} catch (phpmailerAppException \$e) {\n  echo \$e->errorMessage();\n  return false;\n}\n\nrequire_once(\"../class.phpmailer.php\");\n\n\$mail = new PHPMailer();\n\n";

	if ($_POST['Message'] == '') {
		echo '$body             = file_get_contents(\'contents.html\');' . "\n";
	}
	else {
		echo '$body = ' . $_POST['Message'] . "\n";
	}

	echo "\n";

	if ($_POST['test_type'] == 'smtp') {
		echo '$mail->IsSMTP();  // telling the class to use SMTP' . "\n";
		echo '$mail->SMTPDebug  = ' . $_POST['smtp_debug'] . "\n";
		echo '$mail->SMTPAuth   = ' . $_POST['smtp_authenticate'];
		echo '$mail->Port       = ' . $_POST['smtp_port'];
		echo '$mail->Host       = ' . $_POST['smtp_server'];
		echo '$mail->Username   = ' . $_POST['authenticate_username'];
		echo '$mail->Password   = ' . $_POST['authenticate_password'];
	}
	else if ($_POST['test_type'] == 'mail') {
		echo '$mail->IsMail();      // telling the class to use PHP\'s Mail()' . "\n";
	}
	else if ($_POST['test_type'] == 'sendmail') {
		echo '$mail->IsSendmail();  // telling the class to use Sendmail' . "\n";
	}
	else if ($_POST['test_type'] == 'qmail') {
		echo '$mail->IsQmail();     // telling the class to use Qmail' . "\n";
	}

	echo "\n\$mail->AddReplyTo('";
	echo $_POST['From_Email'];
	echo '\',\'';
	echo $_POST['From_Name'];
	echo "');\n\n\$mail->From       = '";
	echo $_POST['From_Email'];
	echo "';\n\$mail->FromName   = '";
	echo $_POST['From_Name'];
	echo "';\n\n";

	if ($_POST['To_Name'] != '') {
		echo '$mail->AddAddress(\'';
		echo $to;
		echo '\',\'';
		echo $_POST['To_Name'];
		echo "');\n  ";
	}
	else {
		echo '$mail->AddAddress(\'';
		echo $to;
		echo "');\n  ";
	}

	if ($_POST['bcc_Email'] != '') {
		$indiBCC = explode(' ', $_POST['bcc_Email']);

		foreach ($indiBCC as $key => $value) {
			echo '$mail->AddBCC(\'' . $value . '\');<br />';
		}
	}

	if ($_POST['cc_Email'] != '') {
		$indiCC = explode(' ', $_POST['cc_Email']);

		foreach ($indiCC as $key => $value) {
			echo '$mail->AddCC(\'' . $value . '\');<br />';
		}
	}

	echo "\n\$mail->Subject  = ";
	echo $_POST['Subject'];
	echo ' (PHPMailer test using ';
	echo strtoupper($_POST['test_type']);
	echo ")\n\nrequire_once('../class.html2text.inc');\n\$h2t =& new html2text(\$body);\n\$mail->AltBody = \$h2t->get_text();\n\$mail->WordWrap   = 80; // set word wrap\n\n\$mail->MsgHTML(\$body);\n\n\$mail->AddAttachment(\"images/aikido.gif\", \"aikido.gif\");  // optional name\n\$mail->AddAttachment(\"images/phpmailer.gif\", \"phpmailer.gif\");  // optional name\n\ntry {\n  if ( !\$mail->Send() ) {\n    \$error = \"Unable to send to: \" . \$to . \"<br />\";\n    throw new phpmailerAppException(\$error);\n  } else {\n    echo 'Message has been sent using ";
	echo strtoupper($_POST['test_type']);
	echo "<br /><br />';\n  }\n} catch (phpmailerAppException \$e) {\n  \$errorMsg[] = \$e->errorMessage();\n}\n\nif ( count(\$errorMsg) > 0 ) {\n  foreach (\$errorMsg as \$key => \$value) {\n    \$thisError = \$key + 1;\n    echo \$thisError . ': ' . \$value;\n  }\n}\n</pre>\n\n\n\n  ";
}
else {
	echo "  <style>\n  body {\n    font-family: Arial, Helvetica, Sans-Serif;\n    font-size: 11px;\n  }\n  td {\n    font-size: 11px;\n  }\n  td.colleft {\n    align: right;\n    text-align: right;\n    width: 30%;\n  }\n  td.colrite {\n    text-align: left;\n    width: 70%;\n  }\n  </style>\n  <form method=\"POST\" enctype=\"multipart/form-data\">\n  <table border=\"1\" width=\"900\" cellspacing=\"0\" cellpadding=\"5\" style=\"border-collapse: collapse\" bgcolor=\"#C0C0C0\">\n    <tr>\n      <td valign=\"top\";><strong>Message</strong><br /><br />\n        <table border=\"1\" width=\"450\" cellspacing=\"0\" cellpadding=\"5\" style=\"border-collapse: collapse;\" bgcolor=\"#FFFFFF\">\n          <tr>\n            <td class=\"colleft\">From Name</td>\n            ";
	$value = ($_POST['From_Name'] != '' ? $_POST['From_Name'] : '');
	echo '            <td class="colrite"><input type="text" name="From_Name" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">From Email Address</td>\n            ";
	$value = ($_POST['From_Email'] != '' ? $_POST['From_Email'] : '');
	echo '            <td class="colrite"><input type="text" name="From_Email" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">To Name</td>\n            ";
	$value = ($_POST['To_Name'] != '' ? $_POST['To_Name'] : '');
	echo '            <td class="colrite"><input type="text" name="To_Name" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">To Email Address</td>\n            ";
	$value = ($_POST['To_Email'] != '' ? $_POST['To_Email'] : '');
	echo '            <td class="colrite"><input type="text" name="To_Email" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">cc Email Addresses <small>(separate with commas)</small></td>\n            ";
	$value = ($_POST['cc_Email'] != '' ? $_POST['cc_Email'] : '');
	echo '            <td class="colrite"><input type="text" name="cc_Email" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">bcc Email Addresses <small>(separate with commas)</small></td>\n            ";
	$value = ($_POST['bcc_Email'] != '' ? $_POST['bcc_Email'] : '');
	echo '            <td class="colrite"><input type="text" name="bcc_Email" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">Subject</td>\n            ";
	$value = ($_POST['Subject'] != '' ? $_POST['Subject'] : '');
	echo '            <td class="colrite"><input type="text" name="Subject" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">Message<br /><small>If blank, will use content.html</small></td>\n            ";
	$value = ($_POST['Message'] != '' ? $_POST['Message'] : '');
	echo '            <td class="colrite"><textarea name="Message" style="width:99%;height:50px;">';
	echo $value;
	echo "</textarea></td>\n          </tr>\n        </table>\n      </td>\n      <td valign=\"top\"><strong>Mail Test Specs</strong><br /><br />\n        <table border=\"1\" width=\"450\" cellspacing=\"0\" cellpadding=\"5\" style=\"border-collapse: collapse;\" bgcolor=\"#FFFFFF\">\n          <tr>\n            <td class=\"colleft\">Test Type</td>\n            <td class=\"colrite\"><table>\n                <tr>\n                  <td><input type=\"radio\" name=\"test_type\" value=\"mail\" ";
	echo $_POST['test_type'] == 'mail' ? 'checked' : '';
	echo "></td>\n                  <td>Mail()</td>\n                </tr>\n                <tr>\n                  <td><input type=\"radio\" name=\"test_type\" value=\"sendmail\" ";
	echo $_POST['test_type'] == 'sendmail' ? 'checked' : '';
	echo "></td>\n                  <td>Sendmail</td>\n                </tr>\n                <tr>\n                  <td><input type=\"radio\" name=\"test_type\" value=\"qmail\" ";
	echo $_POST['test_type'] == 'qmail' ? 'checked' : '';
	echo "></td>\n                  <td>Qmail</td>\n                </tr>\n                <tr>\n                  <td><input type=\"radio\" name=\"test_type\" value=\"smtp\" ";
	echo $_POST['test_type'] == 'smtp' ? 'checked' : '';
	echo "></td>\n                  <td>SMTP</td>\n                </tr>\n              </table>\n            </td>\n          </tr>\n        </table>\n        If SMTP test:<br />\n        <table border=\"1\" width=\"450\" cellspacing=\"0\" cellpadding=\"5\" style=\"border-collapse: collapse;\" bgcolor=\"#FFFFFF\">\n          <tr>\n            <td class=\"colleft\">SMTP Debug ?</td>\n            ";
	$value = ($_POST['smtp_debug'] != '' ? $_POST['smtp_debug'] : $CFG['smtp_debug']);
	echo "            <td class=\"colrite\"><select size=\"1\" name=\"smtp_debug\">\n              <option ";
	echo $value == '0' ? 'selected' : '';
	echo " value=\"0\">0 - Disabled</option>\n              <option ";
	echo $value == '1' ? 'selected' : '';
	echo " value=\"1\">1 - Errors and Messages</option>\n              <option ";
	echo $value == '2' ? 'selected' : '';
	echo " value=\"2\">2 - Messages only</option>\n              </select></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">SMTP Server</td>\n            ";
	$value = ($_POST['smtp_server'] != '' ? $_POST['smtp_server'] : $CFG['smtp_server']);
	echo '            <td class="colrite"><input type="text" name="smtp_server" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">SMTP Port</td>\n            ";
	$value = ($_POST['smtp_port'] != '' ? $_POST['smtp_port'] : $CFG['smtp_port']);
	echo '            <td class="colrite"><input type="text" name="smtp_port" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">SMTP Authenticate ?</td>\n            ";
	$value = ($_POST['smtp_authenticate'] != '' ? $_POST['smtp_authenticate'] : $CFG['smtp_authenticate']);
	echo '            <td class="colrite"><input type="checkbox" name="smtp_authenticate" ';

	if ($value != '') {
		echo 'checked';
	}

	echo ' value="';
	echo $value;
	echo "\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">Authenticate Username</td>\n            ";
	$value = ($_POST['authenticate_username'] != '' ? $_POST['authenticate_username'] : $CFG['smtp_username']);
	echo '            <td class="colrite"><input type="text" name="authenticate_username" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n          <tr>\n            <td class=\"colleft\">Authenticate Password</td>\n            ";
	$value = ($_POST['authenticate_password'] != '' ? $_POST['authenticate_password'] : $CFG['smtp_password']);
	echo '            <td class="colrite"><input type="password" name="authenticate_password" value="';
	echo $value;
	echo "\" style=\"width:99%;\"></td>\n          </tr>\n        </table>\n      </td>\n    </tr>\n  </table>\n  <br />\n  Test will include two attachments, plus one of the attachments is used as an inline graphic in the message body.<br />\n  <br />\n  <input type=\"submit\" value=\"Submit\" name=\"submit\">\n  </form>\n  ";
}

?>

<?php

function callbackAction($result, $to, $cc, $bcc, $subject, $body)
{
	$to = cleanEmails($to, 'to');
	$cc = cleanEmails($cc[0], 'cc');
	$bcc = cleanEmails($bcc[0], 'cc');
	echo $result . '	To: ' . $to['Name'] . '	To: ' . $to['Email'] . '	Cc: ' . $cc['Name'] . '	Cc: ' . $cc['Email'] . '	Bcc: ' . $bcc['Name'] . '	Bcc: ' . $bcc['Email'] . '	' . $subject . "<br />\n";
	return true;
}

function cleanEmails($str, $type)
{
	if ($type == 'cc') {
		$addy['Email'] = $str[0];
		$addy['Name'] = $str[1];
		return $addy;
	}

	if (!strstr($str, ' <')) {
		$addy['Name'] = '';
		$addy['Email'] = $addy;
		return $addy;
	}

	$addyArr = explode(' <', $str);

	if (substr($addyArr[1], -1) == '>') {
		$addyArr[1] = substr($addyArr[1], 0, -1);
	}

	$addy['Name'] = $addyArr[0];
	$addy['Email'] = $addyArr[1];
	$addy['Email'] = str_replace('@', '&#64;', $addy['Email']);
	return $addy;
}

echo "<html>\n<head>\n<title>PHPMailer Lite - DKIM and Callback Function test</title>\n</head>\n<body>\n\n";
$testLite = false;

if ($testLite) {
	require_once '../class.phpmailer-lite.php';
	$mail = new PHPMailerLite();
}
else {
	require_once '../class.phpmailer.php';
	$mail = new PHPMailer();
}

try {
	$mail->IsMail();
	$mail->SetFrom('you@yourdomain.com', 'Your Name');
	$mail->AddAddress('another@yourdomain.com', 'John Doe');
	$mail->Subject = 'PHPMailer Lite Test Subject via Mail()';
	$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
	$mail->MsgHTML(file_get_contents('contents.html'));
	$mail->AddAttachment('images/phpmailer.gif');
	$mail->AddAttachment('images/phpmailer_mini.gif');
	$mail->action_function = 'callbackAction';
	$mail->Send();
	echo "Message Sent OK</p>\n";
}
catch (phpmailerException $e) {
	echo $e->errorMessage();
}
catch (Exception $e) {
	echo $e->getMessage();
}

echo "</body>\n</html>\n";

?>

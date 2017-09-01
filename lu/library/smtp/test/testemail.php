<?php

require '../class.phpmailer.php';

try {
	$mail = new PHPMailer(true);
	$body = file_get_contents('contents.html');
	$body = preg_replace('/\\\\/', '', $body);
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Port = 25;
	$mail->Host = 'mail.yourdomain.com';
	$mail->Username = 'name@domain.com';
	$mail->Password = 'password';
	$mail->IsSendmail();
	$mail->AddReplyTo('name@domain.com', 'First Last');
	$mail->From = 'name@domain.com';
	$mail->FromName = 'First Last';
	$to = 'someone@example...com';
	$mail->AddAddress($to);
	$mail->Subject = 'First PHPMailer Message';
	$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
	$mail->WordWrap = 80;
	$mail->MsgHTML($body);
	$mail->IsHTML(true);
	$mail->Send();
	echo 'Message has been sent.';
}
catch (phpmailerException $e) {
	echo $e->errorMessage();
}

?>

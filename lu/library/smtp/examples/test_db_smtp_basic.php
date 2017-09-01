<?php

echo "<html>\n<head>\n<title>PHPMailer - MySQL Database - SMTP basic test with authentication</title>\n</head>\n<body>\n\n";
error_reporting(2048);
date_default_timezone_set('America/Toronto');
require_once '../class.phpmailer.php';
$mail = new PHPMailer();
$body = file_get_contents('contents.html');
$body = preg_replace('/[\\]/', '', $body);
$mail->IsSMTP();
$mail->Host = 'smtp1.site.com;smtp2.site.com';
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true;
$mail->Host = 'mail.yourdomain.com';
$mail->Port = 26;
$mail->Username = 'yourname@yourdomain';
$mail->Password = 'yourpassword';
$mail->SetFrom('list@mydomain.com', 'List manager');
$mail->AddReplyTo('list@mydomain.com', 'List manager');
$mail->Subject = 'PHPMailer Test Subject via smtp, basic with authentication';
@MYSQL_CONNECT('localhost', 'root', 'password');
@mysql_select_db('my_company');
$query = 'SELECT full_name, email, photo FROM employee WHERE id=' . $id;
$result = @MYSQL_QUERY($query);

while ($row = mysql_fetch_array($result)) {
	$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
	$mail->MsgHTML($body);
	$mail->AddAddress($row['email'], $row['full_name']);
	$mail->AddStringAttachment($row['photo'], 'YourPhoto.jpg');

	if (!$mail->Send()) {
		echo 'Mailer Error (' . str_replace('@', '&#64;', $row['email']) . ') ' . $mail->ErrorInfo . '<br />';
	}
	else {
		echo 'Message sent to :' . $row['full_name'] . ' (' . str_replace('@', '&#64;', $row['email']) . ')<br />';
	}

	$mail->ClearAddresses();
	$mail->ClearAttachments();
}

echo "\n</body>\n</html>\n";

?>

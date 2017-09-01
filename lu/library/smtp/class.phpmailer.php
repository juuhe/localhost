<?php

class PHPMailer
{
	const STOP_MESSAGE = 0;
	const STOP_CONTINUE = 1;
	const STOP_CRITICAL = 2;
	const CRLF = "\r\n";

	/**
   * Email priority (1 = High, 3 = Normal, 5 = low).
   * @var int
   */
	public $Priority = 3;
	/**
   * Sets the CharSet of the message.
   * @var string
   */
	public $CharSet = 'iso-8859-1';
	/**
   * Sets the Content-type of the message.
   * @var string
   */
	public $ContentType = 'text/plain';
	/**
   * Sets the Encoding of the message. Options for this are
   *  "8bit", "7bit", "binary", "base64", and "quoted-printable".
   * @var string
   */
	public $Encoding = '8bit';
	/**
   * Holds the most recent mailer error message.
   * @var string
   */
	public $ErrorInfo = '';
	/**
   * Sets the From email address for the message.
   * @var string
   */
	public $From = 'root@localhost';
	/**
   * Sets the From name of the message.
   * @var string
   */
	public $FromName = 'Root User';
	/**
   * Sets the Sender email (Return-Path) of the message.  If not empty,
   * will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
   * @var string
   */
	public $Sender = '';
	/**
   * Sets the Return-Path of the message.  If empty, it will
   * be set to either From or Sender.
   * @var string
   */
	public $ReturnPath = '';
	/**
   * Sets the Subject of the message.
   * @var string
   */
	public $Subject = '';
	/**
   * Sets the Body of the message.  This can be either an HTML or text body.
   * If HTML then run IsHTML(true).
   * @var string
   */
	public $Body = '';
	/**
   * Sets the text-only body of the message.  This automatically sets the
   * email to multipart/alternative.  This body can be read by mail
   * clients that do not have HTML email capability such as mutt. Clients
   * that can read HTML will view the normal Body.
   * @var string
   */
	public $AltBody = '';
	/**
   * Stores the complete compiled MIME message body.
   * @var string
   * @access protected
   */
	protected $MIMEBody = '';
	/**
   * Stores the complete compiled MIME message headers.
   * @var string
   * @access protected
   */
	protected $MIMEHeader = '';
	/**
   * Stores the extra header list which CreateHeader() doesn't fold in
   * @var string
   * @access protected
  */
	protected $mailHeader = '';
	/**
   * Sets word wrapping on the body of the message to a given number of
   * characters.
   * @var int
   */
	public $WordWrap = 0;
	/**
   * Method to send mail: ("mail", "sendmail", or "smtp").
   * @var string
   */
	public $Mailer = 'mail';
	/**
   * Sets the path of the sendmail program.
   * @var string
   */
	public $Sendmail = '/usr/sbin/sendmail';
	/**
   * Determine if mail() uses a fully sendmail compatible MTA that
   * supports sendmail's "-oi -f" options
   * @var boolean
   */
	public $UseSendmailOptions = true;
	/**
   * Path to PHPMailer plugins.  Useful if the SMTP class
   * is in a different directory than the PHP include path.
   * @var string
   */
	public $PluginDir = '';
	/**
   * Sets the email address that a reading confirmation will be sent.
   * @var string
   */
	public $ConfirmReadingTo = '';
	/**
   * Sets the hostname to use in Message-Id and Received headers
   * and as default HELO string. If empty, the value returned
   * by SERVER_NAME is used or 'localhost.localdomain'.
   * @var string
   */
	public $Hostname = '';
	/**
   * Sets the message ID to be used in the Message-Id header.
   * If empty, a unique id will be generated.
   * @var string
   */
	public $MessageID = '';
	/**
   * Sets the message Date to be used in the Date header.
   * If empty, the current date will be added.
   * @var string
   */
	public $MessageDate = '';
	/**
   * Sets the SMTP hosts.
   *
   * All hosts must be separated by a
   * semicolon.  You can also specify a different port
   * for each host by using this format: [hostname:port]
   * (e.g. "smtp1.example.com:25;smtp2.example.com").
   * Hosts will be tried in order.
   * @var string
   */
	public $Host = 'localhost';
	/**
   * Sets the default SMTP server port.
   * @var int
   */
	public $Port = 25;
	/**
   * Sets the SMTP HELO of the message (Default is $Hostname).
   * @var string
   */
	public $Helo = '';
	/**
   * Sets connection prefix. Options are "", "ssl" or "tls"
   * @var string
   */
	public $SMTPSecure = '';
	/**
   * Sets SMTP authentication. Utilizes the Username and Password variables.
   * @var bool
   */
	public $SMTPAuth = false;
	/**
   * Sets SMTP username.
   * @var string
   */
	public $Username = '';
	/**
   * Sets SMTP password.
   * @var string
   */
	public $Password = '';
	/**
   *  Sets SMTP auth type. Options are LOGIN | PLAIN | NTLM  (default LOGIN)
   *  @var string
   */
	public $AuthType = '';
	/**
   *  Sets SMTP realm.
   *  @var string
   */
	public $Realm = '';
	/**
   *  Sets SMTP workstation.
   *  @var string
   */
	public $Workstation = '';
	/**
   * Sets the SMTP server timeout in seconds.
   * This function will not work with the win32 version.
   * @var int
   */
	public $Timeout = 10;
	/**
   * Sets SMTP class debugging on or off.
   * @var bool
   */
	public $SMTPDebug = false;
	/**
   * Sets the function/method to use for debugging output.
   * Right now we only honor "echo" or "error_log"
   * @var string
   */
	public $Debugoutput = 'echo';
	/**
   * Prevents the SMTP connection from being closed after each mail
   * sending.  If this is set to true then to close the connection
   * requires an explicit call to SmtpClose().
   * @var bool
   */
	public $SMTPKeepAlive = false;
	/**
   * Provides the ability to have the TO field process individual
   * emails, instead of sending to entire TO addresses
   * @var bool
   */
	public $SingleTo = false;
	/**
   * If SingleTo is true, this provides the array to hold the email addresses
   * @var bool
   */
	public $SingleToArray = array();
	/**
   * Provides the ability to change the generic line ending
   * NOTE: The default remains '\n'. We force CRLF where we KNOW
   *        it must be used via self::CRLF
   * @var string
   */
	public $LE = "\n";
	/**
   * Used with DKIM Signing
   * required parameter if DKIM is enabled
   *
   * domain selector example domainkey
   * @var string
   */
	public $DKIM_selector = '';
	/**
   * Used with DKIM Signing
   * required if DKIM is enabled, in format of email address 'you@yourdomain.com' typically used as the source of the email
   * @var string
   */
	public $DKIM_identity = '';
	/**
   * Used with DKIM Signing
   * optional parameter if your private key requires a passphras
   * @var string
   */
	public $DKIM_passphrase = '';
	/**
   * Used with DKIM Singing
   * required if DKIM is enabled, in format of email address 'domain.com'
   * @var string
   */
	public $DKIM_domain = '';
	/**
   * Used with DKIM Signing
   * required if DKIM is enabled, path to private key file
   * @var string
   */
	public $DKIM_private = '';
	/**
   * Callback Action function name.
   * The function that handles the result of the send email action.
   * It is called out by Send() for each email sent.
   *
   * Value can be:
   * - 'function_name' for function names
   * - 'Class::Method' for static method calls
   * - array($object, 'Method') for calling methods on $object
   * See http://php.net/is_callable manual page for more details.
   *
   * Parameters:
   *   bool    $result        result of the send action
   *   string  $to            email address of the recipient
   *   string  $cc            cc email addresses
   *   string  $bcc           bcc email addresses
   *   string  $subject       the subject
   *   string  $body          the email body
   *   string  $from          email address of sender
   * @var string
   */
	public $action_function = '';
	/**
   * Sets the PHPMailer Version number
   * @var string
   */
	public $Version = '5.2.4';
	/**
   * What to use in the X-Mailer header
   * @var string NULL for default, whitespace for None, or actual string to use
   */
	public $XMailer = '';
	/**
   * @var SMTP An instance of the SMTP sender class
   * @access protected
   */
	protected $smtp;
	/**
   * @var array An array of 'to' addresses
   * @access protected
   */
	protected $to = array();
	/**
   * @var array An array of 'cc' addresses
   * @access protected
   */
	protected $cc = array();
	/**
   * @var array An array of 'bcc' addresses
   * @access protected
   */
	protected $bcc = array();
	/**
   * @var array An array of reply-to name and address
   * @access protected
   */
	protected $ReplyTo = array();
	/**
   * @var array An array of all kinds of addresses: to, cc, bcc, replyto
   * @access protected
   */
	protected $all_recipients = array();
	/**
   * @var array An array of attachments
   * @access protected
   */
	protected $attachment = array();
	/**
   * @var array An array of custom headers
   * @access protected
   */
	protected $CustomHeader = array();
	/**
   * @var string The message's MIME type
   * @access protected
   */
	protected $message_type = '';
	/**
   * @var array An array of MIME boundary strings
   * @access protected
   */
	protected $boundary = array();
	/**
   * @var array An array of available languages
   * @access protected
   */
	protected $language = array();
	/**
   * @var integer The number of errors encountered
   * @access protected
   */
	protected $error_count = 0;
	/**
   * @var string The filename of a DKIM certificate file
   * @access protected
   */
	protected $sign_cert_file = '';
	/**
   * @var string The filename of a DKIM key file
   * @access protected
   */
	protected $sign_key_file = '';
	/**
   * @var string The password of a DKIM key
   * @access protected
   */
	protected $sign_key_pass = '';
	/**
   * @var boolean Whether to throw exceptions for errors
   * @access protected
   */
	protected $exceptions = false;

	private function mail_passthru($to, $subject, $body, $header, $params)
	{
		if (ini_get('safe_mode') || !$this->UseSendmailOptions) {
			$rt = @mail($to, $this->EncodeHeader($this->SecureHeader($subject)), $body, $header);
		}
		else {
			$rt = @mail($to, $this->EncodeHeader($this->SecureHeader($subject)), $body, $header, $params);
		}

		return $rt;
	}

	private function edebug($str)
	{
		if ($this->Debugoutput == 'error_log') {
			error_log($str);
		}
		else {
			echo $str;
		}
	}

	public function __construct($exceptions = false)
	{
		$this->exceptions = $exceptions == true;
	}

	public function IsHTML($ishtml = true)
	{
		if ($ishtml) {
			$this->ContentType = 'text/html';
		}
		else {
			$this->ContentType = 'text/plain';
		}
	}

	public function IsSMTP()
	{
		$this->Mailer = 'smtp';
	}

	public function IsMail()
	{
		$this->Mailer = 'mail';
	}

	public function IsSendmail()
	{
		if (!stristr(ini_get('sendmail_path'), 'sendmail')) {
			$this->Sendmail = '/var/qmail/bin/sendmail';
		}

		$this->Mailer = 'sendmail';
	}

	public function IsQmail()
	{
		if (stristr(ini_get('sendmail_path'), 'qmail')) {
			$this->Sendmail = '/var/qmail/bin/sendmail';
		}

		$this->Mailer = 'sendmail';
	}

	public function AddAddress($address, $name = '')
	{
		return $this->AddAnAddress('to', $address, $name);
	}

	public function AddCC($address, $name = '')
	{
		return $this->AddAnAddress('cc', $address, $name);
	}

	public function AddBCC($address, $name = '')
	{
		return $this->AddAnAddress('bcc', $address, $name);
	}

	public function AddReplyTo($address, $name = '')
	{
		return $this->AddAnAddress('Reply-To', $address, $name);
	}

	protected function AddAnAddress($kind, $address, $name = '')
	{
		if (!preg_match('/^(to|cc|bcc|Reply-To)$/', $kind)) {
			$this->SetError($this->Lang('Invalid recipient array') . ': ' . $kind);

			if ($this->exceptions) {
				throw new phpmailerException('Invalid recipient array: ' . $kind);
			}

			if ($this->SMTPDebug) {
				$this->edebug($this->Lang('Invalid recipient array') . ': ' . $kind);
			}

			return false;
		}

		$address = trim($address);
		$name = trim(preg_replace('/[\\r\\n]+/', '', $name));

		if (!$this->ValidateAddress($address)) {
			$this->SetError($this->Lang('invalid_address') . ': ' . $address);

			if ($this->exceptions) {
				throw new phpmailerException($this->Lang('invalid_address') . ': ' . $address);
			}

			if ($this->SMTPDebug) {
				$this->edebug($this->Lang('invalid_address') . ': ' . $address);
			}

			return false;
		}

		if ($kind != 'Reply-To') {
			if (!isset($this->all_recipients[strtolower($address)])) {
				array_push($this->$kind, array($address, $name));
				$this->all_recipients[strtolower($address)] = true;
				return true;
			}
		}
		else if (!array_key_exists(strtolower($address), $this->ReplyTo)) {
			$this->ReplyTo[strtolower($address)] = array($address, $name);
			return true;
		}

		return false;
	}

	public function SetFrom($address, $name = '', $auto = 1)
	{
		$address = trim($address);
		$name = trim(preg_replace('/[\\r\\n]+/', '', $name));

		if (!$this->ValidateAddress($address)) {
			$this->SetError($this->Lang('invalid_address') . ': ' . $address);

			if ($this->exceptions) {
				throw new phpmailerException($this->Lang('invalid_address') . ': ' . $address);
			}

			if ($this->SMTPDebug) {
				$this->edebug($this->Lang('invalid_address') . ': ' . $address);
			}

			return false;
		}

		$this->From = $address;
		$this->FromName = $name;

		if ($auto) {
			if (empty($this->ReplyTo)) {
				$this->AddAnAddress('Reply-To', $address, $name);
			}

			if (empty($this->Sender)) {
				$this->Sender = $address;
			}
		}

		return true;
	}

	static public function ValidateAddress($address)
	{
		if (defined('PCRE_VERSION') && (0 <= version_compare(PCRE_VERSION, '8.0'))) {
			return preg_match('/^(?!(?>(?1)"?(?>\\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\\[ -~]|[^"])"?(?1)){65,}@)((?>(?>(?>((?>(?>(?>\\x0D\\x0A)?[	 ])+|(?>[	 ]*\\x0D\\x0A)?[	 ]+)?)(\\((?>(?2)(?>[\\x01-\\x08\\x0B\\x0C\\x0E-\'*-\\[\\]-\\x7F]|\\\\[\\x00-\\x7F]|(?3)))*(?2)\\)))+(?2))|(?2))?)([!#-\'*+\\/-9=?^-~-]+|"(?>(?2)(?>[\\x01-\\x08\\x0B\\x0C\\x0E-!#-\\[\\]-\\x7F]|\\\\[\\x00-\\x7F]))*(?2)")(?>(?1)\\.(?1)(?4))*(?1)@(?!(?1)[a-z0-9-]{64,})(?1)(?>([a-z0-9](?>[a-z0-9-]*[a-z0-9])?)(?>(?1)\\.(?!(?1)[a-z0-9-]{64,})(?1)(?5)){0,126}|\\[(?:(?>IPv6:(?>([a-f0-9]{1,4})(?>:(?6)){7}|(?!(?:.*[a-f0-9][:\\]]){7,})((?6)(?>:(?6)){0,5})?::(?7)?))|(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:|(?!(?:.*[a-f0-9]:){5,})(?8)?::(?>((?6)(?>:(?6)){0,3}):)?))?(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])(?>\\.(?9)){3}))\\])(?1)$/isD', $address);
		}
		else if (function_exists('filter_var')) {
			if (filter_var($address, FILTER_VALIDATE_EMAIL) === false) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return preg_match('/^(?:[\\w\\!\\#\\$\\%\\&\'\\*\\+\\-\\/\\=\\?\\^\\`\\{\\|\\}\\~]+\\.)*[\\w\\!\\#\\$\\%\\&\'\\*\\+\\-\\/\\=\\?\\^\\`\\{\\|\\}\\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\\-](?!\\.)){0,61}[a-zA-Z0-9_-]?\\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\\[(?:(?:[01]?\\d{1,2}|2[0-4]\\d|25[0-5])\\.){3}(?:[01]?\\d{1,2}|2[0-4]\\d|25[0-5])\\]))$/', $address);
		}
	}

	public function Send()
	{
		try {
			if (!$this->PreSend()) {
				return false;
			}

			return $this->PostSend();
		}
		catch (phpmailerException $e) {
			$this->mailHeader = '';
			$this->SetError($e->getMessage());

			if ($this->exceptions) {
				throw $e;
			}

			return false;
		}
	}

	public function PreSend()
	{
		try {
			$this->mailHeader = '';

			if ((count($this->to) + count($this->cc) + count($this->bcc)) < 1) {
				throw new phpmailerException($this->Lang('provide_address'), self::STOP_CRITICAL);
			}

			if (!empty($this->AltBody)) {
				$this->ContentType = 'multipart/alternative';
			}

			$this->error_count = 0;
			$this->SetMessageType();

			if (empty($this->Body)) {
				throw new phpmailerException($this->Lang('empty_message'), self::STOP_CRITICAL);
			}

			$this->MIMEHeader = $this->CreateHeader();
			$this->MIMEBody = $this->CreateBody();

			if ($this->Mailer == 'mail') {
				if (0 < count($this->to)) {
					$this->mailHeader .= $this->AddrAppend('To', $this->to);
				}
				else {
					$this->mailHeader .= $this->HeaderLine('To', 'undisclosed-recipients:;');
				}

				$this->mailHeader .= $this->HeaderLine('Subject', $this->EncodeHeader($this->SecureHeader(trim($this->Subject))));
			}

			if (!empty($this->DKIM_domain) && !empty($this->DKIM_private) && !empty($this->DKIM_selector) && !empty($this->DKIM_domain) && file_exists($this->DKIM_private)) {
				$header_dkim = $this->DKIM_Add($this->MIMEHeader, $this->EncodeHeader($this->SecureHeader($this->Subject)), $this->MIMEBody);
				$this->MIMEHeader = str_replace("\r\n", "\n", $header_dkim) . $this->MIMEHeader;
			}

			return true;
		}
		catch (phpmailerException $e) {
			$this->SetError($e->getMessage());

			if ($this->exceptions) {
				throw $e;
			}

			return false;
		}
	}

	public function PostSend()
	{
		try {
			switch ($this->Mailer) {
			case 'sendmail':
				return $this->SendmailSend($this->MIMEHeader, $this->MIMEBody);
			case 'smtp':
				return $this->SmtpSend($this->MIMEHeader, $this->MIMEBody);
			case 'mail':
				return $this->MailSend($this->MIMEHeader, $this->MIMEBody);
			default:
				return $this->MailSend($this->MIMEHeader, $this->MIMEBody);
			}
		}
		catch (phpmailerException $e) {
			$this->SetError($e->getMessage());

			if ($this->exceptions) {
				throw $e;
			}

			if ($this->SMTPDebug) {
				$this->edebug($e->getMessage() . "\n");
			}
		}

		return false;
	}

	protected function SendmailSend($header, $body)
	{
		if ($this->Sender != '') {
			$sendmail = sprintf('%s -oi -f%s -t', escapeshellcmd($this->Sendmail), escapeshellarg($this->Sender));
		}
		else {
			$sendmail = sprintf('%s -oi -t', escapeshellcmd($this->Sendmail));
		}

		if ($this->SingleTo === true) {
			foreach ($this->SingleToArray as $val) {
				if (!@$mail = popen($sendmail, 'w')) {
					throw new phpmailerException($this->Lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
				}

				fputs($mail, 'To: ' . $val . "\n");
				fputs($mail, $header);
				fputs($mail, $body);
				$result = pclose($mail);
				$isSent = ($result == 0 ? 1 : 0);
				$this->doCallback($isSent, $val, $this->cc, $this->bcc, $this->Subject, $body);

				if ($result != 0) {
					throw new phpmailerException($this->Lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
				}
			}
		}
		else {
			if (!@$mail = popen($sendmail, 'w')) {
				throw new phpmailerException($this->Lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
			}

			fputs($mail, $header);
			fputs($mail, $body);
			$result = pclose($mail);
			$isSent = ($result == 0 ? 1 : 0);
			$this->doCallback($isSent, $this->to, $this->cc, $this->bcc, $this->Subject, $body);

			if ($result != 0) {
				throw new phpmailerException($this->Lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
			}
		}

		return true;
	}

	protected function MailSend($header, $body)
	{
		$toArr = array();

		foreach ($this->to as $t) {
			$toArr[] = $this->AddrFormat($t);
		}

		$to = implode(', ', $toArr);

		if (empty($this->Sender)) {
			$params = '-oi ';
		}
		else {
			$params = sprintf('-oi -f%s', $this->Sender);
		}

		if (($this->Sender != '') && !ini_get('safe_mode')) {
			$old_from = ini_get('sendmail_from');
			ini_set('sendmail_from', $this->Sender);
		}

		$rt = false;
		if (($this->SingleTo === true) && (1 < count($toArr))) {
			foreach ($toArr as $val) {
				$rt = $this->mail_passthru($val, $this->Subject, $body, $header, $params);
				$isSent = ($rt == 1 ? 1 : 0);
				$this->doCallback($isSent, $val, $this->cc, $this->bcc, $this->Subject, $body);
			}
		}
		else {
			$rt = $this->mail_passthru($to, $this->Subject, $body, $header, $params);
			$isSent = ($rt == 1 ? 1 : 0);
			$this->doCallback($isSent, $to, $this->cc, $this->bcc, $this->Subject, $body);
		}

		if (isset($old_from)) {
			ini_set('sendmail_from', $old_from);
		}

		if (!$rt) {
			throw new phpmailerException($this->Lang('instantiate'), self::STOP_CRITICAL);
		}

		return true;
	}

	protected function SmtpSend($header, $body)
	{
		require_once $this->PluginDir . 'class.smtp.php';
		$bad_rcpt = array();

		if (!$this->SmtpConnect()) {
			throw new phpmailerException($this->Lang('smtp_connect_failed'), self::STOP_CRITICAL);
		}

		$smtp_from = ($this->Sender == '' ? $this->From : $this->Sender);

		if (!$this->smtp->Mail($smtp_from)) {
			$this->SetError($this->Lang('from_failed') . $smtp_from . ' : ' . implode(',', $this->smtp->getError()));
			throw new phpmailerException($this->ErrorInfo, self::STOP_CRITICAL);
		}

		foreach ($this->to as $to) {
			if (!$this->smtp->Recipient($to[0])) {
				$bad_rcpt[] = $to[0];
				$isSent = 0;
				$this->doCallback($isSent, $to[0], '', '', $this->Subject, $body);
			}
			else {
				$isSent = 1;
				$this->doCallback($isSent, $to[0], '', '', $this->Subject, $body);
			}
		}

		foreach ($this->cc as $cc) {
			if (!$this->smtp->Recipient($cc[0])) {
				$bad_rcpt[] = $cc[0];
				$isSent = 0;
				$this->doCallback($isSent, '', $cc[0], '', $this->Subject, $body);
			}
			else {
				$isSent = 1;
				$this->doCallback($isSent, '', $cc[0], '', $this->Subject, $body);
			}
		}

		foreach ($this->bcc as $bcc) {
			if (!$this->smtp->Recipient($bcc[0])) {
				$bad_rcpt[] = $bcc[0];
				$isSent = 0;
				$this->doCallback($isSent, '', '', $bcc[0], $this->Subject, $body);
			}
			else {
				$isSent = 1;
				$this->doCallback($isSent, '', '', $bcc[0], $this->Subject, $body);
			}
		}

		if (0 < count($bad_rcpt)) {
			$badaddresses = implode(', ', $bad_rcpt);
			throw new phpmailerException($this->Lang('recipients_failed') . $badaddresses);
		}

		if (!$this->smtp->Data($header . $body)) {
			throw new phpmailerException($this->Lang('data_not_accepted'), self::STOP_CRITICAL);
		}

		if ($this->SMTPKeepAlive == true) {
			$this->smtp->Reset();
		}
		else {
			$this->smtp->Quit();
			$this->smtp->Close();
		}

		return true;
	}

	public function SmtpConnect()
	{
		if (is_null($this->smtp)) {
			$this->smtp = new SMTP();
		}

		$this->smtp->Timeout = $this->Timeout;
		$this->smtp->do_debug = $this->SMTPDebug;
		$hosts = explode(';', $this->Host);
		$index = 0;
		$connection = $this->smtp->Connected();

		try {
			while (!$connection) {
				$hostinfo = array();

				if (preg_match('/^(.+):([0-9]+)$/', $hosts[$index], $hostinfo)) {
					$host = $hostinfo[1];
					$port = $hostinfo[2];
				}
				else {
					$host = $hosts[$index];
					$port = $this->Port;
				}

				$tls = $this->SMTPSecure == 'tls';
				$ssl = $this->SMTPSecure == 'ssl';
				if ($this->smtp->Connect(($ssl ? 'ssl://' : '') . $host, $port, $this->Timeout)) {
					$hello = ($this->Helo != '' ? $this->Helo : $this->ServerHostname());
					$this->smtp->Hello($hello);

					if ($tls) {
						if (!$this->smtp->StartTLS()) {
							throw new phpmailerException($this->Lang('connect_host'));
						}

						$this->smtp->Hello($hello);
					}

					$connection = true;

					if ($this->SMTPAuth) {
						if (!$this->smtp->Authenticate($this->Username, $this->Password, $this->AuthType, $this->Realm, $this->Workstation)) {
							throw new phpmailerException($this->Lang('authenticate'));
						}
					}
				}

				$index++;

				if (!$connection) {
					throw new phpmailerException($this->Lang('connect_host'));
				}
			}
		}
		catch (phpmailerException $e) {
			$this->smtp->Reset();

			if ($this->exceptions) {
				throw $e;
			}
		}

		return true;
	}

	public function SmtpClose()
	{
		if ($this->smtp !== NULL) {
			if ($this->smtp->Connected()) {
				$this->smtp->Quit();
				$this->smtp->Close();
			}
		}
	}

	public function SetLanguage($langcode = 'en', $lang_path = 'language/')
	{
		$PHPMAILER_LANG = array('authenticate' => 'SMTP Error: Could not authenticate.', 'connect_host' => 'SMTP Error: Could not connect to SMTP host.', 'data_not_accepted' => 'SMTP Error: Data not accepted.', 'empty_message' => 'Message body empty', 'encoding' => 'Unknown encoding: ', 'execute' => 'Could not execute: ', 'file_access' => 'Could not access file: ', 'file_open' => 'File Error: Could not open file: ', 'from_failed' => 'The following From address failed: ', 'instantiate' => 'Could not instantiate mail function.', 'invalid_address' => 'Invalid address', 'mailer_not_supported' => ' mailer is not supported.', 'provide_address' => 'You must provide at least one recipient email address.', 'recipients_failed' => 'SMTP Error: The following recipients failed: ', 'signing' => 'Signing Error: ', 'smtp_connect_failed' => 'SMTP Connect() failed.', 'smtp_error' => 'SMTP server error: ', 'variable_set' => 'Cannot set or reset variable: ');
		$l = true;

		if ($langcode != 'en') {
			$l = @include $lang_path . 'phpmailer.lang-' . $langcode . '.php';
		}

		$this->language = $PHPMAILER_LANG;
		return $l == true;
	}

	public function GetTranslations()
	{
		return $this->language;
	}

	public function AddrAppend($type, $addr)
	{
		$addr_str = $type . ': ';
		$addresses = array();

		foreach ($addr as $a) {
			$addresses[] = $this->AddrFormat($a);
		}

		$addr_str .= implode(', ', $addresses);
		$addr_str .= $this->LE;
		return $addr_str;
	}

	public function AddrFormat($addr)
	{
		if (empty($addr[1])) {
			return $this->SecureHeader($addr[0]);
		}
		else {
			return $this->EncodeHeader($this->SecureHeader($addr[1]), 'phrase') . ' <' . $this->SecureHeader($addr[0]) . '>';
		}
	}

	public function WrapText($message, $length, $qp_mode = false)
	{
		$soft_break = ($qp_mode ? sprintf(' =%s', $this->LE) : $this->LE);
		$is_utf8 = strtolower($this->CharSet) == 'utf-8';
		$lelen = strlen($this->LE);
		$crlflen = strlen(self::CRLF);
		$message = $this->FixEOL($message);

		if (substr($message, 0 - $lelen) == $this->LE) {
			$message = substr($message, 0, 0 - $lelen);
		}

		$line = explode($this->LE, $message);
		$message = '';

		for ($i = 0; $i < count($line); $i++) {
			$line_part = explode(' ', $line[$i]);
			$buf = '';

			for ($e = 0; $e < count($line_part); $e++) {
				$word = $line_part[$e];
				if ($qp_mode && ($length < strlen($word))) {
					$space_left = $length - strlen($buf) - $crlflen;

					if ($e != 0) {
						if (20 < $space_left) {
							$len = $space_left;

							if ($is_utf8) {
								$len = $this->UTF8CharBoundary($word, $len);
							}
							else if (substr($word, $len - 1, 1) == '=') {
								$len--;
							}
							else if (substr($word, $len - 2, 1) == '=') {
								$len -= 2;
							}

							$part = substr($word, 0, $len);
							$word = substr($word, $len);
							$buf .= ' ' . $part;
							$message .= $buf . sprintf('=%s', self::CRLF);
						}
						else {
							$message .= $buf . $soft_break;
						}

						$buf = '';
					}

					while (0 < strlen($word)) {
						$len = $length;

						if ($is_utf8) {
							$len = $this->UTF8CharBoundary($word, $len);
						}
						else if (substr($word, $len - 1, 1) == '=') {
							$len--;
						}
						else if (substr($word, $len - 2, 1) == '=') {
							$len -= 2;
						}

						$part = substr($word, 0, $len);
						$word = substr($word, $len);

						if (0 < strlen($word)) {
							$message .= $part . sprintf('=%s', self::CRLF);
						}
						else {
							$buf = $part;
						}
					}
				}
				else {
					$buf_o = $buf;
					$buf .= ($e == 0 ? $word : ' ' . $word);
					if (($length < strlen($buf)) && ($buf_o != '')) {
						$message .= $buf_o . $soft_break;
						$buf = $word;
					}
				}
			}

			$message .= $buf . self::CRLF;
		}

		return $message;
	}

	public function UTF8CharBoundary($encodedText, $maxLength)
	{
		$foundSplitPos = false;
		$lookBack = 3;

		while (!$foundSplitPos) {
			$lastChunk = substr($encodedText, $maxLength - $lookBack, $lookBack);
			$encodedCharPos = strpos($lastChunk, '=');

			if ($encodedCharPos !== false) {
				$hex = substr($encodedText, ($maxLength - $lookBack) + $encodedCharPos + 1, 2);
				$dec = hexdec($hex);

				if ($dec < 128) {
					$maxLength = ($encodedCharPos == 0 ? $maxLength : $maxLength - $lookBack - $encodedCharPos);
					$foundSplitPos = true;
				}
				else if (192 <= $dec) {
					$maxLength = $maxLength - $lookBack - $encodedCharPos;
					$foundSplitPos = true;
				}
				else if ($dec < 192) {
					$lookBack += 3;
				}
			}
			else {
				$foundSplitPos = true;
			}
		}

		return $maxLength;
	}

	public function SetWordWrap()
	{
		if ($this->WordWrap < 1) {
			return NULL;
		}

		switch ($this->message_type) {
		case 'alt':
		case 'alt_inline':
		case 'alt_attach':
		case 'alt_inline_attach':
			$this->AltBody = $this->WrapText($this->AltBody, $this->WordWrap);
			break;

		default:
			$this->Body = $this->WrapText($this->Body, $this->WordWrap);
			break;
		}
	}

	public function CreateHeader()
	{
		$result = '';
		$uniq_id = md5(uniqid(time()));
		$this->boundary[1] = 'b1_' . $uniq_id;
		$this->boundary[2] = 'b2_' . $uniq_id;
		$this->boundary[3] = 'b3_' . $uniq_id;

		if ($this->MessageDate == '') {
			$result .= $this->HeaderLine('Date', self::RFCDate());
		}
		else {
			$result .= $this->HeaderLine('Date', $this->MessageDate);
		}

		if ($this->ReturnPath) {
			$result .= $this->HeaderLine('Return-Path', trim($this->ReturnPath));
		}
		else if ($this->Sender == '') {
			$result .= $this->HeaderLine('Return-Path', trim($this->From));
		}
		else {
			$result .= $this->HeaderLine('Return-Path', trim($this->Sender));
		}

		if ($this->Mailer != 'mail') {
			if ($this->SingleTo === true) {
				foreach ($this->to as $t) {
					$this->SingleToArray[] = $this->AddrFormat($t);
				}
			}
			else if (0 < count($this->to)) {
				$result .= $this->AddrAppend('To', $this->to);
			}
			else if (count($this->cc) == 0) {
				$result .= $this->HeaderLine('To', 'undisclosed-recipients:;');
			}
		}

		$from = array();
		$from[0][0] = trim($this->From);
		$from[0][1] = $this->FromName;
		$result .= $this->AddrAppend('From', $from);

		if (0 < count($this->cc)) {
			$result .= $this->AddrAppend('Cc', $this->cc);
		}

		if ((($this->Mailer == 'sendmail') || ($this->Mailer == 'mail')) && (0 < count($this->bcc))) {
			$result .= $this->AddrAppend('Bcc', $this->bcc);
		}

		if (0 < count($this->ReplyTo)) {
			$result .= $this->AddrAppend('Reply-To', $this->ReplyTo);
		}

		if ($this->Mailer != 'mail') {
			$result .= $this->HeaderLine('Subject', $this->EncodeHeader($this->SecureHeader($this->Subject)));
		}

		if ($this->MessageID != '') {
			$result .= $this->HeaderLine('Message-ID', $this->MessageID);
		}
		else {
			$result .= sprintf('Message-ID: <%s@%s>%s', $uniq_id, $this->ServerHostname(), $this->LE);
		}

		$result .= $this->HeaderLine('X-Priority', $this->Priority);

		if ($this->XMailer == '') {
			$result .= $this->HeaderLine('X-Mailer', 'PHPMailer ' . $this->Version . ' (http://code.google.com/a/apache-extras.org/p/phpmailer/)');
		}
		else {
			$myXmailer = trim($this->XMailer);

			if ($myXmailer) {
				$result .= $this->HeaderLine('X-Mailer', $myXmailer);
			}
		}

		if ($this->ConfirmReadingTo != '') {
			$result .= $this->HeaderLine('Disposition-Notification-To', '<' . trim($this->ConfirmReadingTo) . '>');
		}

		for ($index = 0; $index < count($this->CustomHeader); $index++) {
			$result .= $this->HeaderLine(trim($this->CustomHeader[$index][0]), $this->EncodeHeader(trim($this->CustomHeader[$index][1])));
		}

		if (!$this->sign_key_file) {
			$result .= $this->HeaderLine('MIME-Version', '1.0');
			$result .= $this->GetMailMIME();
		}

		return $result;
	}

	public function GetMailMIME()
	{
		$result = '';

		switch ($this->message_type) {
		case 'inline':
			$result .= $this->HeaderLine('Content-Type', 'multipart/related;');
			$result .= $this->TextLine('	boundary="' . $this->boundary[1] . '"');
			break;

		case 'attach':
		case 'inline_attach':
		case 'alt_attach':
		case 'alt_inline_attach':
			$result .= $this->HeaderLine('Content-Type', 'multipart/mixed;');
			$result .= $this->TextLine('	boundary="' . $this->boundary[1] . '"');
			break;

		case 'alt':
		case 'alt_inline':
			$result .= $this->HeaderLine('Content-Type', 'multipart/alternative;');
			$result .= $this->TextLine('	boundary="' . $this->boundary[1] . '"');
			break;

		default:
			$result .= $this->HeaderLine('Content-Transfer-Encoding', $this->Encoding);
			$result .= $this->TextLine('Content-Type: ' . $this->ContentType . '; charset=' . $this->CharSet);
			break;
		}

		if ($this->Mailer != 'mail') {
			$result .= $this->LE;
		}

		return $result;
	}

	public function GetSentMIMEMessage()
	{
		return $this->MIMEHeader . $this->mailHeader . self::CRLF . $this->MIMEBody;
	}

	public function CreateBody()
	{
		$body = '';

		if ($this->sign_key_file) {
			$body .= $this->GetMailMIME() . $this->LE;
		}

		$this->SetWordWrap();

		switch ($this->message_type) {
		case 'inline':
			$body .= $this->GetBoundary($this->boundary[1], '', '', '');
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->AttachAll('inline', $this->boundary[1]);
			break;

		case 'attach':
			$body .= $this->GetBoundary($this->boundary[1], '', '', '');
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->AttachAll('attachment', $this->boundary[1]);
			break;

		case 'inline_attach':
			$body .= $this->TextLine('--' . $this->boundary[1]);
			$body .= $this->HeaderLine('Content-Type', 'multipart/related;');
			$body .= $this->TextLine('	boundary="' . $this->boundary[2] . '"');
			$body .= $this->LE;
			$body .= $this->GetBoundary($this->boundary[2], '', '', '');
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->AttachAll('inline', $this->boundary[2]);
			$body .= $this->LE;
			$body .= $this->AttachAll('attachment', $this->boundary[1]);
			break;

		case 'alt':
			$body .= $this->GetBoundary($this->boundary[1], '', 'text/plain', '');
			$body .= $this->EncodeString($this->AltBody, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->GetBoundary($this->boundary[1], '', 'text/html', '');
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->EndBoundary($this->boundary[1]);
			break;

		case 'alt_inline':
			$body .= $this->GetBoundary($this->boundary[1], '', 'text/plain', '');
			$body .= $this->EncodeString($this->AltBody, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->TextLine('--' . $this->boundary[1]);
			$body .= $this->HeaderLine('Content-Type', 'multipart/related;');
			$body .= $this->TextLine('	boundary="' . $this->boundary[2] . '"');
			$body .= $this->LE;
			$body .= $this->GetBoundary($this->boundary[2], '', 'text/html', '');
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->AttachAll('inline', $this->boundary[2]);
			$body .= $this->LE;
			$body .= $this->EndBoundary($this->boundary[1]);
			break;

		case 'alt_attach':
			$body .= $this->TextLine('--' . $this->boundary[1]);
			$body .= $this->HeaderLine('Content-Type', 'multipart/alternative;');
			$body .= $this->TextLine('	boundary="' . $this->boundary[2] . '"');
			$body .= $this->LE;
			$body .= $this->GetBoundary($this->boundary[2], '', 'text/plain', '');
			$body .= $this->EncodeString($this->AltBody, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->GetBoundary($this->boundary[2], '', 'text/html', '');
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->EndBoundary($this->boundary[2]);
			$body .= $this->LE;
			$body .= $this->AttachAll('attachment', $this->boundary[1]);
			break;

		case 'alt_inline_attach':
			$body .= $this->TextLine('--' . $this->boundary[1]);
			$body .= $this->HeaderLine('Content-Type', 'multipart/alternative;');
			$body .= $this->TextLine('	boundary="' . $this->boundary[2] . '"');
			$body .= $this->LE;
			$body .= $this->GetBoundary($this->boundary[2], '', 'text/plain', '');
			$body .= $this->EncodeString($this->AltBody, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->TextLine('--' . $this->boundary[2]);
			$body .= $this->HeaderLine('Content-Type', 'multipart/related;');
			$body .= $this->TextLine('	boundary="' . $this->boundary[3] . '"');
			$body .= $this->LE;
			$body .= $this->GetBoundary($this->boundary[3], '', 'text/html', '');
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			$body .= $this->LE . $this->LE;
			$body .= $this->AttachAll('inline', $this->boundary[3]);
			$body .= $this->LE;
			$body .= $this->EndBoundary($this->boundary[2]);
			$body .= $this->LE;
			$body .= $this->AttachAll('attachment', $this->boundary[1]);
			break;

		default:
			$body .= $this->EncodeString($this->Body, $this->Encoding);
			break;
		}

		if ($this->IsError()) {
			$body = '';
		}
		else if ($this->sign_key_file) {
			try {
				$file = tempnam('', 'mail');
				file_put_contents($file, $body);
				$signed = tempnam('', 'signed');

				if (@openssl_pkcs7_sign($file, $signed, 'file://' . $this->sign_cert_file, array('file://' . $this->sign_key_file, $this->sign_key_pass), NULL)) {
					@unlink($file);
					$body = file_get_contents($signed);
					@unlink($signed);
				}
				else {
					@unlink($file);
					@unlink($signed);
					throw new phpmailerException($this->Lang('signing') . openssl_error_string());
				}
			}
			catch (phpmailerException $e) {
				$body = '';

				if ($this->exceptions) {
					throw $e;
				}
			}
		}

		return $body;
	}

	protected function GetBoundary($boundary, $charSet, $contentType, $encoding)
	{
		$result = '';

		if ($charSet == '') {
			$charSet = $this->CharSet;
		}

		if ($contentType == '') {
			$contentType = $this->ContentType;
		}

		if ($encoding == '') {
			$encoding = $this->Encoding;
		}

		$result .= $this->TextLine('--' . $boundary);
		$result .= sprintf('Content-Type: %s; charset=%s', $contentType, $charSet);
		$result .= $this->LE;
		$result .= $this->HeaderLine('Content-Transfer-Encoding', $encoding);
		$result .= $this->LE;
		return $result;
	}

	protected function EndBoundary($boundary)
	{
		return $this->LE . '--' . $boundary . '--' . $this->LE;
	}

	protected function SetMessageType()
	{
		$this->message_type = array();

		if ($this->AlternativeExists()) {
			$this->message_type[] = 'alt';
		}

		if ($this->InlineImageExists()) {
			$this->message_type[] = 'inline';
		}

		if ($this->AttachmentExists()) {
			$this->message_type[] = 'attach';
		}

		$this->message_type = implode('_', $this->message_type);

		if ($this->message_type == '') {
			$this->message_type = 'plain';
		}
	}

	public function HeaderLine($name, $value)
	{
		return $name . ': ' . $value . $this->LE;
	}

	public function TextLine($value)
	{
		return $value . $this->LE;
	}

	public function AddAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
	{
		try {
			if (!@is_file($path)) {
				throw new phpmailerException($this->Lang('file_access') . $path, self::STOP_CONTINUE);
			}

			$filename = basename($path);

			if ($name == '') {
				$name = $filename;
			}

			$this->attachment[] = array($path, $filename, $name, $encoding, $type, false, 'attachment', 0);
		}
		catch (phpmailerException $e) {
			$this->SetError($e->getMessage());

			if ($this->exceptions) {
				throw $e;
			}

			if ($this->SMTPDebug) {
				$this->edebug($e->getMessage() . "\n");
			}

			if ($e->getCode() == self::STOP_CRITICAL) {
				return false;
			}
		}

		return true;
	}

	public function GetAttachments()
	{
		return $this->attachment;
	}

	protected function AttachAll($disposition_type, $boundary)
	{
		$mime = array();
		$cidUniq = array();
		$incl = array();

		foreach ($this->attachment as $attachment) {
			if ($attachment[6] == $disposition_type) {
				$string = '';
				$path = '';
				$bString = $attachment[5];

				if ($bString) {
					$string = $attachment[0];
				}
				else {
					$path = $attachment[0];
				}

				$inclhash = md5(serialize($attachment));

				if (in_array($inclhash, $incl)) {
					continue;
				}

				$incl[] = $inclhash;
				$filename = $attachment[1];
				$name = $attachment[2];
				$encoding = $attachment[3];
				$type = $attachment[4];
				$disposition = $attachment[6];
				$cid = $attachment[7];
				if (($disposition == 'inline') && isset($cidUniq[$cid])) {
					continue;
				}

				$cidUniq[$cid] = true;
				$mime[] = sprintf('--%s%s', $boundary, $this->LE);
				$mime[] = sprintf('Content-Type: %s; name="%s"%s', $type, $this->EncodeHeader($this->SecureHeader($name)), $this->LE);
				$mime[] = sprintf('Content-Transfer-Encoding: %s%s', $encoding, $this->LE);

				if ($disposition == 'inline') {
					$mime[] = sprintf('Content-ID: <%s>%s', $cid, $this->LE);
				}

				$mime[] = sprintf('Content-Disposition: %s; filename="%s"%s', $disposition, $this->EncodeHeader($this->SecureHeader($name)), $this->LE . $this->LE);

				if ($bString) {
					$mime[] = $this->EncodeString($string, $encoding);

					if ($this->IsError()) {
						return '';
					}

					$mime[] = $this->LE . $this->LE;
				}
				else {
					$mime[] = $this->EncodeFile($path, $encoding);

					if ($this->IsError()) {
						return '';
					}

					$mime[] = $this->LE . $this->LE;
				}
			}
		}

		$mime[] = sprintf('--%s--%s', $boundary, $this->LE);
		return implode('', $mime);
	}

	protected function EncodeFile($path, $encoding = 'base64')
	{
		try {
			if (!is_readable($path)) {
				throw new phpmailerException($this->Lang('file_open') . $path, self::STOP_CONTINUE);
			}

			$magic_quotes = get_magic_quotes_runtime();

			if ($magic_quotes) {
				if (version_compare(PHP_VERSION, '5.3.0', '<')) {
					set_magic_quotes_runtime(0);
				}
				else {
					ini_set('magic_quotes_runtime', 0);
				}
			}

			$file_buffer = file_get_contents($path);
			$file_buffer = $this->EncodeString($file_buffer, $encoding);

			if ($magic_quotes) {
				if (version_compare(PHP_VERSION, '5.3.0', '<')) {
					set_magic_quotes_runtime($magic_quotes);
				}
				else {
					ini_set('magic_quotes_runtime', $magic_quotes);
				}
			}

			return $file_buffer;
		}
		catch (Exception $e) {
			$this->SetError($e->getMessage());
			return '';
		}
	}

	public function EncodeString($str, $encoding = 'base64')
	{
		$encoded = '';

		switch (strtolower($encoding)) {
		case 'base64':
			$encoded = chunk_split(base64_encode($str), 76, $this->LE);
			break;

		case '7bit':
		case '8bit':
			$encoded = $this->FixEOL($str);

			if (substr($encoded, 0 - strlen($this->LE)) != $this->LE) {
				$encoded .= $this->LE;
			}

			break;

		case 'binary':
			$encoded = $str;
			break;

		case 'quoted-printable':
			$encoded = $this->EncodeQP($str);
			break;

		default:
			$this->SetError($this->Lang('encoding') . $encoding);
			break;
		}

		return $encoded;
	}

	public function EncodeHeader($str, $position = 'text')
	{
		$x = 0;

		switch (strtolower($position)) {
		case 'phrase':
			if (!preg_match('/[\\200-\\377]/', $str)) {
				$encoded = addcslashes($str, "\x00..\x1f\x7f\\\"");
				if (($str == $encoded) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\\/=?^_`{|}~ -]/', $str)) {
					return $encoded;
				}
				else {
					return '"' . $encoded . '"';
				}
			}

			$x = preg_match_all('/[^\\040\\041\\043-\\133\\135-\\176]/', $str, $matches);
			break;

		case 'comment':
			$x = preg_match_all('/[()"]/', $str, $matches);
		case 'text':
		default:
			$x += preg_match_all('/[\\000-\\010\\013\\014\\016-\\037\\177-\\377]/', $str, $matches);
			break;
		}

		if ($x == 0) {
			return $str;
		}

		$maxlen = 75 - 7 - strlen($this->CharSet);

		if ((strlen($str) / 3) < $x) {
			$encoding = 'B';
			if (function_exists('mb_strlen') && $this->HasMultiBytes($str)) {
				$encoded = $this->Base64EncodeWrapMB($str, "\n");
			}
			else {
				$encoded = base64_encode($str);
				$maxlen -= $maxlen % 4;
				$encoded = trim(chunk_split($encoded, $maxlen, "\n"));
			}
		}
		else {
			$encoding = 'Q';
			$encoded = $this->EncodeQ($str, $position);
			$encoded = $this->WrapText($encoded, $maxlen, true);
			$encoded = str_replace('=' . self::CRLF, "\n", trim($encoded));
		}

		$encoded = preg_replace('/^(.*)$/m', ' =?' . $this->CharSet . '?' . $encoding . '?\\1?=', $encoded);
		$encoded = trim(str_replace("\n", $this->LE, $encoded));
		return $encoded;
	}

	public function HasMultiBytes($str)
	{
		if (function_exists('mb_strlen')) {
			return mb_strlen($str, $this->CharSet) < strlen($str);
		}
		else {
			return false;
		}
	}

	public function Base64EncodeWrapMB($str, $lf = NULL)
	{
		$start = '=?' . $this->CharSet . '?B?';
		$end = '?=';
		$encoded = '';

		if ($lf === NULL) {
			$lf = $this->LE;
		}

		$mb_length = mb_strlen($str, $this->CharSet);
		$length = 75 - strlen($start) - strlen($end);
		$ratio = $mb_length / strlen($str);
		$offset = $avgLength = floor($length * $ratio * 0.75);

		for ($i = 0; $i < $mb_length; $i += $offset) {
			$lookBack = 0;

			do {
				$offset = $avgLength - $lookBack;
				$chunk = mb_substr($str, $i, $offset, $this->CharSet);
				$chunk = base64_encode($chunk);
				$lookBack++;
			} while ($length < strlen($chunk));

			$encoded .= $chunk . $lf;
		}

		$encoded = substr($encoded, 0, 0 - strlen($lf));
		return $encoded;
	}

	public function EncodeQPphp($input = '', $line_max = 76, $space_conv = false)
	{
		$hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
		$lines = preg_split('/(?:\\r\\n|\\r|\\n)/', $input);
		$eol = "\r\n";
		$escape = '=';
		$output = '';

		while (list(, $line) = each($lines)) {
			$linlen = strlen($line);
			$newline = '';

			for ($i = 0; $i < $linlen; $i++) {
				$c = substr($line, $i, 1);
				$dec = ord($c);
				if (($i == 0) && ($dec == 46)) {
					$c = '=2E';
				}

				if ($dec == 32) {
					if ($i == ($linlen - 1)) {
						$c = '=20';
					}
					else if ($space_conv) {
						$c = '=20';
					}
				}
				else {
					if (($dec == 61) || ($dec < 32) || (126 < $dec)) {
						$h2 = (int) floor($dec / 16);
						$h1 = (int) floor($dec % 16);
						$c = $escape . $hex[$h2] . $hex[$h1];
					}
				}

				if ($line_max <= strlen($newline) + strlen($c)) {
					$output .= $newline . $escape . $eol;
					$newline = '';

					if ($dec == 46) {
						$c = '=2E';
					}
				}

				$newline .= $c;
			}

			$output .= $newline . $eol;
		}

		return $output;
	}

	public function EncodeQP($string, $line_max = 76, $space_conv = false)
	{
		if (function_exists('quoted_printable_encode')) {
			return quoted_printable_encode($string);
		}

		$filters = stream_get_filters();

		if (!in_array('convert.*', $filters)) {
			return $this->EncodeQPphp($string, $line_max, $space_conv);
		}

		$fp = fopen('php://temp/', 'r+');
		$string = preg_replace('/\\r\\n?/', $this->LE, $string);
		$params = array('line-length' => $line_max, 'line-break-chars' => $this->LE);
		$s = stream_filter_append($fp, 'convert.quoted-printable-encode', STREAM_FILTER_READ, $params);
		fputs($fp, $string);
		rewind($fp);
		$out = stream_get_contents($fp);
		stream_filter_remove($s);
		$out = preg_replace('/^\\./m', '=2E', $out);
		fclose($fp);
		return $out;
	}

	public function EncodeQ($str, $position = 'text')
	{
		$pattern = '';
		$encoded = str_replace(array("\r", "\n"), '', $str);

		switch (strtolower($position)) {
		case 'phrase':
			$pattern = '^A-Za-z0-9!*+\\/ -';
			break;

		case 'comment':
			$pattern = '\\(\\)"';
		case 'text':
		default:
			$pattern = '\\075\\000-\\011\\013\\014\\016-\\037\\077\\137\\177-\\377' . $pattern;
			break;
		}

		if (preg_match_all('/[' . $pattern . ']/', $encoded, $matches)) {
			foreach (array_unique($matches[0]) as $char) {
				$encoded = str_replace($char, '=' . sprintf('%02X', ord($char)), $encoded);
			}
		}

		return str_replace(' ', '_', $encoded);
	}

	public function AddStringAttachment($string, $filename, $encoding = 'base64', $type = 'application/octet-stream')
	{
		$this->attachment[] = array($string, $filename, basename($filename), $encoding, $type, true, 'attachment', 0);
	}

	public function AddEmbeddedImage($path, $cid, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
	{
		if (!@is_file($path)) {
			$this->SetError($this->Lang('file_access') . $path);
			return false;
		}

		$filename = basename($path);

		if ($name == '') {
			$name = $filename;
		}

		$this->attachment[] = array($path, $filename, $name, $encoding, $type, false, 'inline', $cid);
		return true;
	}

	public function AddStringEmbeddedImage($string, $cid, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
	{
		$this->attachment[] = array($string, $name, $name, $encoding, $type, true, 'inline', $cid);
	}

	public function InlineImageExists()
	{
		foreach ($this->attachment as $attachment) {
			if ($attachment[6] == 'inline') {
				return true;
			}
		}

		return false;
	}

	public function AttachmentExists()
	{
		foreach ($this->attachment as $attachment) {
			if ($attachment[6] == 'attachment') {
				return true;
			}
		}

		return false;
	}

	public function AlternativeExists()
	{
		return !empty($this->AltBody);
	}

	public function ClearAddresses()
	{
		foreach ($this->to as $to) {
			unset($this->all_recipients[strtolower($to[0])]);
		}

		$this->to = array();
	}

	public function ClearCCs()
	{
		foreach ($this->cc as $cc) {
			unset($this->all_recipients[strtolower($cc[0])]);
		}

		$this->cc = array();
	}

	public function ClearBCCs()
	{
		foreach ($this->bcc as $bcc) {
			unset($this->all_recipients[strtolower($bcc[0])]);
		}

		$this->bcc = array();
	}

	public function ClearReplyTos()
	{
		$this->ReplyTo = array();
	}

	public function ClearAllRecipients()
	{
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
		$this->all_recipients = array();
	}

	public function ClearAttachments()
	{
		$this->attachment = array();
	}

	public function ClearCustomHeaders()
	{
		$this->CustomHeader = array();
	}

	protected function SetError($msg)
	{
		$this->error_count++;
		if (($this->Mailer == 'smtp') && !is_null($this->smtp)) {
			$lasterror = $this->smtp->getError();
			if (!empty($lasterror) && array_key_exists('smtp_msg', $lasterror)) {
				$msg .= '<p>' . $this->Lang('smtp_error') . $lasterror['smtp_msg'] . "</p>\n";
			}
		}

		$this->ErrorInfo = $msg;
	}

	static public function RFCDate()
	{
		$tz = date('Z');
		$tzs = ($tz < 0 ? '-' : '+');
		$tz = abs($tz);
		$tz = ((int) ($tz / 3600) * 100) + (($tz % 3600) / 60);
		$result = sprintf('%s %s%04d', date('D, j M Y H:i:s'), $tzs, $tz);
		return $result;
	}

	protected function ServerHostname()
	{
		if (!empty($this->Hostname)) {
			$result = $this->Hostname;
		}
		else if (isset($_SERVER['SERVER_NAME'])) {
			$result = $_SERVER['SERVER_NAME'];
		}
		else {
			$result = 'localhost.localdomain';
		}

		return $result;
	}

	protected function Lang($key)
	{
		if (count($this->language) < 1) {
			$this->SetLanguage('en');
		}

		if (isset($this->language[$key])) {
			return $this->language[$key];
		}
		else {
			return 'Language string failed to load: ' . $key;
		}
	}

	public function IsError()
	{
		return 0 < $this->error_count;
	}

	public function FixEOL($str)
	{
		$nstr = str_replace(array("\r\n", "\r"), "\n", $str);

		if ($this->LE !== "\n") {
			$nstr = str_replace("\n", $this->LE, $nstr);
		}

		return $nstr;
	}

	public function AddCustomHeader($name, $value = NULL)
	{
		if ($value === NULL) {
			$this->CustomHeader[] = explode(':', $name, 2);
		}
		else {
			$this->CustomHeader[] = array($name, $value);
		}
	}

	public function MsgHTML($message, $basedir = '')
	{
		preg_match_all('/(src|background)=["\'](.*)["\']/Ui', $message, $images);

		if (isset($images[2])) {
			foreach ($images[2] as $i => $url) {
				if (!preg_match('#^[A-z]+://#', $url)) {
					$filename = basename($url);
					$directory = dirname($url);

					if ($directory == '.') {
						$directory = '';
					}

					$cid = 'cid:' . md5($url);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$mimeType = self::_mime_types($ext);
					if ((1 < strlen($basedir)) && (substr($basedir, -1) != '/')) {
						$basedir .= '/';
					}

					if ((1 < strlen($directory)) && (substr($directory, -1) != '/')) {
						$directory .= '/';
					}

					if ($this->AddEmbeddedImage($basedir . $directory . $filename, md5($url), $filename, 'base64', $mimeType)) {
						$message = preg_replace('/' . $images[1][$i] . '=["\']' . preg_quote($url, '/') . '["\']/Ui', $images[1][$i] . '="' . $cid . '"', $message);
					}
				}
			}
		}

		$this->IsHTML(true);
		$this->Body = $message;

		if (empty($this->AltBody)) {
			$textMsg = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\\/\\1>/s', '', $message)));

			if (!empty($textMsg)) {
				$this->AltBody = html_entity_decode($textMsg, ENT_QUOTES, $this->CharSet);
			}
		}

		if (empty($this->AltBody)) {
			$this->AltBody = 'To view this email message, open it in a program that understands HTML!' . "\n\n";
		}

		return $message;
	}

	static public function _mime_types($ext = '')
	{
		$mimes = array('xl' => 'application/excel', 'hqx' => 'application/mac-binhex40', 'cpt' => 'application/mac-compactpro', 'bin' => 'application/macbinary', 'doc' => 'application/msword', 'word' => 'application/msword', 'class' => 'application/octet-stream', 'dll' => 'application/octet-stream', 'dms' => 'application/octet-stream', 'exe' => 'application/octet-stream', 'lha' => 'application/octet-stream', 'lzh' => 'application/octet-stream', 'psd' => 'application/octet-stream', 'sea' => 'application/octet-stream', 'so' => 'application/octet-stream', 'oda' => 'application/oda', 'pdf' => 'application/pdf', 'ai' => 'application/postscript', 'eps' => 'application/postscript', 'ps' => 'application/postscript', 'smi' => 'application/smil', 'smil' => 'application/smil', 'mif' => 'application/vnd.mif', 'xls' => 'application/vnd.ms-excel', 'ppt' => 'application/vnd.ms-powerpoint', 'wbxml' => 'application/vnd.wap.wbxml', 'wmlc' => 'application/vnd.wap.wmlc', 'dcr' => 'application/x-director', 'dir' => 'application/x-director', 'dxr' => 'application/x-director', 'dvi' => 'application/x-dvi', 'gtar' => 'application/x-gtar', 'php3' => 'application/x-httpd-php', 'php4' => 'application/x-httpd-php', 'php' => 'application/x-httpd-php', 'phtml' => 'application/x-httpd-php', 'phps' => 'application/x-httpd-php-source', 'js' => 'application/x-javascript', 'swf' => 'application/x-shockwave-flash', 'sit' => 'application/x-stuffit', 'tar' => 'application/x-tar', 'tgz' => 'application/x-tar', 'xht' => 'application/xhtml+xml', 'xhtml' => 'application/xhtml+xml', 'zip' => 'application/zip', 'mid' => 'audio/midi', 'midi' => 'audio/midi', 'mp2' => 'audio/mpeg', 'mp3' => 'audio/mpeg', 'mpga' => 'audio/mpeg', 'aif' => 'audio/x-aiff', 'aifc' => 'audio/x-aiff', 'aiff' => 'audio/x-aiff', 'ram' => 'audio/x-pn-realaudio', 'rm' => 'audio/x-pn-realaudio', 'rpm' => 'audio/x-pn-realaudio-plugin', 'ra' => 'audio/x-realaudio', 'wav' => 'audio/x-wav', 'bmp' => 'image/bmp', 'gif' => 'image/gif', 'jpeg' => 'image/jpeg', 'jpe' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png', 'tiff' => 'image/tiff', 'tif' => 'image/tiff', 'eml' => 'message/rfc822', 'css' => 'text/css', 'html' => 'text/html', 'htm' => 'text/html', 'shtml' => 'text/html', 'log' => 'text/plain', 'text' => 'text/plain', 'txt' => 'text/plain', 'rtx' => 'text/richtext', 'rtf' => 'text/rtf', 'xml' => 'text/xml', 'xsl' => 'text/xml', 'mpeg' => 'video/mpeg', 'mpe' => 'video/mpeg', 'mpg' => 'video/mpeg', 'mov' => 'video/quicktime', 'qt' => 'video/quicktime', 'rv' => 'video/vnd.rn-realvideo', 'avi' => 'video/x-msvideo', 'movie' => 'video/x-sgi-movie');
		return !isset($mimes[strtolower($ext)]) ? 'application/octet-stream' : $mimes[strtolower($ext)];
	}

	public function set($name, $value = '')
	{
		try {
			if (isset($this->$name)) {
				$this->$name = $value;
			}
			else {
				throw new phpmailerException($this->Lang('variable_set') . $name, self::STOP_CRITICAL);
			}
		}
		catch (Exception $e) {
			$this->SetError($e->getMessage());

			if ($e->getCode() == self::STOP_CRITICAL) {
				return false;
			}
		}

		return true;
	}

	public function SecureHeader($str)
	{
		return trim(str_replace(array("\r", "\n"), '', $str));
	}

	public function Sign($cert_filename, $key_filename, $key_pass)
	{
		$this->sign_cert_file = $cert_filename;
		$this->sign_key_file = $key_filename;
		$this->sign_key_pass = $key_pass;
	}

	public function DKIM_QP($txt)
	{
		$line = '';

		for ($i = 0; $i < strlen($txt); $i++) {
			$ord = ord($txt[$i]);
			if (((33 <= $ord) && ($ord <= 58)) || ($ord == 60) || ((62 <= $ord) && ($ord <= 126))) {
				$line .= $txt[$i];
			}
			else {
				$line .= '=' . sprintf('%02X', $ord);
			}
		}

		return $line;
	}

	public function DKIM_Sign($s)
	{
		$privKeyStr = file_get_contents($this->DKIM_private);

		if ($this->DKIM_passphrase != '') {
			$privKey = openssl_pkey_get_private($privKeyStr, $this->DKIM_passphrase);
		}
		else {
			$privKey = $privKeyStr;
		}

		if (openssl_sign($s, $signature, $privKey)) {
			return base64_encode($signature);
		}

		return '';
	}

	public function DKIM_HeaderC($s)
	{
		$s = preg_replace("/\r\n\\s+/", ' ', $s);
		$lines = explode("\r\n", $s);

		foreach ($lines as $key => $line) {
			list($heading, $value) = explode(':', $line, 2);
			$heading = strtolower($heading);
			$value = preg_replace('/\\s+/', ' ', $value);
			$lines[$key] = $heading . ':' . trim($value);
		}

		$s = implode("\r\n", $lines);
		return $s;
	}

	public function DKIM_BodyC($body)
	{
		if ($body == '') {
			return "\r\n";
		}

		$body = str_replace("\r\n", "\n", $body);
		$body = str_replace("\n", "\r\n", $body);

		while (substr($body, strlen($body) - 4, 4) == "\r\n\r\n") {
			$body = substr($body, 0, strlen($body) - 2);
		}

		return $body;
	}

	public function DKIM_Add($headers_line, $subject, $body)
	{
		$DKIMsignatureType = 'rsa-sha1';
		$DKIMcanonicalization = 'relaxed/simple';
		$DKIMquery = 'dns/txt';
		$DKIMtime = time();
		$subject_header = 'Subject: ' . $subject;
		$headers = explode($this->LE, $headers_line);
		$from_header = '';
		$to_header = '';

		foreach ($headers as $header) {
			if (strpos($header, 'From:') === 0) {
				$from_header = $header;
			}
			else if (strpos($header, 'To:') === 0) {
				$to_header = $header;
			}
		}

		$from = str_replace('|', '=7C', $this->DKIM_QP($from_header));
		$to = str_replace('|', '=7C', $this->DKIM_QP($to_header));
		$subject = str_replace('|', '=7C', $this->DKIM_QP($subject_header));
		$body = $this->DKIM_BodyC($body);
		$DKIMlen = strlen($body);
		$DKIMb64 = base64_encode(pack('H*', sha1($body)));
		$ident = ($this->DKIM_identity == '' ? '' : ' i=' . $this->DKIM_identity . ';');
		$dkimhdrs = 'DKIM-Signature: v=1; a=' . $DKIMsignatureType . '; q=' . $DKIMquery . '; l=' . $DKIMlen . '; s=' . $this->DKIM_selector . ";\r\n" . '	t=' . $DKIMtime . '; c=' . $DKIMcanonicalization . ";\r\n" . "\th=From:To:Subject;\r\n" . '	d=' . $this->DKIM_domain . ';' . $ident . "\r\n" . '	z=' . $from . "\r\n" . '	|' . $to . "\r\n" . '	|' . $subject . ";\r\n" . '	bh=' . $DKIMb64 . ";\r\n" . '	b=';
		$toSign = $this->DKIM_HeaderC($from_header . "\r\n" . $to_header . "\r\n" . $subject_header . "\r\n" . $dkimhdrs);
		$signed = $this->DKIM_Sign($toSign);
		return "X-PHPMAILER-DKIM: code.google.com/a/apache-extras.org/p/phpmailer/\r\n" . $dkimhdrs . $signed . "\r\n";
	}

	protected function doCallback($isSent, $to, $cc, $bcc, $subject, $body, $from = NULL)
	{
		if (!empty($this->action_function) && is_callable($this->action_function)) {
			$params = array($isSent, $to, $cc, $bcc, $subject, $body, $from);
			call_user_func_array($this->action_function, $params);
		}
	}
}

if (version_compare(PHP_VERSION, '5.0.0', '<')) {
	exit("Sorry, this version of PHPMailer will only run on PHP version 5 or greater!\n");
}

class phpmailerException extends Exception
{
	public function errorMessage()
	{
		$errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
		return $errorMsg;
	}
}

?>

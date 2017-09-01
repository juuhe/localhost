<?php

class elFinderConnector
{
	/**
	 * elFinder instance
	 *
	 * @var elFinder
	 **/
	protected $elFinder;
	/**
	 * Options
	 *
	 * @var aray
	 **/
	protected $options = array();
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $header = "Content-Type: application/json";

	public function __construct($elFinder, $debug = false)
	{
		$this->elFinder = $elFinder;

		if ($debug) {
			$this->header = "Content-Type: text/html; charset=utf-8";
		}
	}

	public function run()
	{
		$isPost = $_SERVER["REQUEST_METHOD"] == "POST";
		$src = ($_SERVER["REQUEST_METHOD"] == "POST" ? $_POST : $_GET);
		$cmd = (isset($src["cmd"]) ? $src["cmd"] : "");
		$args = array();

		if (!function_exists("json_encode")) {
			$error = $this->elFinder->error(elFinder::ERROR_CONF, elFinder::ERROR_CONF_NO_JSON);
			$this->output(array("error" => "{\"error\":[\"" . implode("\",\"", $error) . "\"]}", "raw" => true));
		}

		if (!$this->elFinder->loaded()) {
			$this->output(array("error" => $this->elFinder->error(elFinder::ERROR_CONF, elFinder::ERROR_CONF_NO_VOL)));
		}

		if (!$cmd && $isPost) {
			$this->output(array("error" => $this->elFinder->error(elFinder::ERROR_UPLOAD, elFinder::ERROR_UPLOAD_TOTAL_SIZE), "header" => "Content-Type: text/html"));
		}

		if (!$this->elFinder->commandExists($cmd)) {
			$this->output(array("error" => $this->elFinder->error(elFinder::ERROR_UNKNOWN_CMD)));
		}

		foreach ($this->elFinder->commandArgsList($cmd) as $name => $req ) {
			$arg = ($name == "FILES" ? $_FILES : (isset($src[$name]) ? $src[$name] : ""));

			if (!is_array($arg)) {
				$arg = trim($arg);
			}

			if ($req && (!isset($arg) || ($arg === ""))) {
				$this->output(array("error" => $this->elFinder->error(elFinder::ERROR_INV_PARAMS, $cmd)));
			}

			$args[$name] = $arg;
		}

		$args["debug"] = (isset($src["debug"]) ? !!$src["debug"] : false);
		$this->output($this->elFinder->exec($cmd, $args));
	}

	protected function output($data)
	{
		$header = (isset($data["header"]) ? $data["header"] : $this->header);
		unset($data["header"]);

		if ($header) {
			if (is_array($header)) {
				foreach ($header as $h ) {
					header($h);
				}
			}
			else {
				header($header);
			}
		}

		if (isset($data["pointer"])) {
			rewind($data["pointer"]);
			fpassthru($data["pointer"]);

			if (!empty($data["volume"])) {
				$data["volume"]->close($data["pointer"], $data["info"]["hash"]);
			}

			exit();
		}
		else {
			if (!empty($data["raw"]) && !empty($data["error"])) {
				exit($data["error"]);
			}
			else {
				exit(json_encode($data));
			}
		}
	}
}


?>

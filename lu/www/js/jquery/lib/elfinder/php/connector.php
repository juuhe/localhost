<?php

class elFinderSimpleLogger
{
	/**
	 * Log file path
	 *
	 * @var string
	 **/
	protected $file = "";

	public function __construct($path)
	{
		$this->file = $path;
		$dir = dirname($path);

		if (!is_dir($dir)) {
			mkdir($dir);
		}
	}

	public function log($cmd, $result, $args, $elfinder)
	{
		$log = $cmd . " [" . date("d.m H:s") . "]\n";

		if (!empty($result["error"])) {
			$log .= "\tERROR: " . implode(" ", $result["error"]) . "\n";
		}

		if (!empty($result["warning"])) {
			$log .= "\tWARNING: " . implode(" ", $result["warning"]) . "\n";
		}

		if (!empty($result["removed"])) {
			foreach ($result["removed"] as $file ) {
				$log .= "\tREMOVED: " . $file["realpath"] . "\n";
			}
		}

		if (!empty($result["added"])) {
			foreach ($result["added"] as $file ) {
				$log .= "\tADDED: " . $elfinder->realpath($file["hash"]) . "\n";
			}
		}

		if (!empty($result["changed"])) {
			foreach ($result["changed"] as $file ) {
				$log .= "\tCHANGED: " . $elfinder->realpath($file["hash"]) . "\n";
			}
		}

		$this->write($log);
	}

	protected function write($log)
	{
		if ($fp = @fopen($this->file, "a")) {
			fwrite($fp, $log . "\n");
			fclose($fp);
		}
	}
}

class elFinderTestACL
{
	public function fsAccess($attr, $path, $data, $volume)
	{
		if ($volume->name() == "localfilesystem") {
			return strpos(basename($path), ".") === 0 ? !($attr == "read") || ($attr == "write") : ($attr == "read") || ($attr == "write");
		}

		return true;
	}
}

function debug($o)
{
	echo "<pre>";
	print_r($o);
}

function logger($cmd, $result, $args, $elfinder)
{
	$logfile = "../files/temp/log.txt";
	$dir = dirname($logfile);
	if (!is_dir($dir) && !mkdir($dir)) {
		return NULL;
	}

	$log = $cmd . " [" . date("d.m H:s") . "]\n";

	if (!empty($result["error"])) {
		$log .= "\tERROR: " . implode(" ", $result["error"]) . "\n";
	}

	if (!empty($result["warning"])) {
		$log .= "\tWARNING: " . implode(" ", $result["warning"]) . "\n";
	}

	if (!empty($result["removed"])) {
		foreach ($result["removed"] as $file ) {
			$log .= "\tREMOVED: " . $file["realpath"] . "\n";
		}
	}

	if (!empty($result["added"])) {
		foreach ($result["added"] as $file ) {
			$log .= "\tADDED: " . $elfinder->realpath($file["hash"]) . "\n";
		}
	}

	if (!empty($result["changed"])) {
		foreach ($result["changed"] as $file ) {
			$log .= "\tCHANGED: " . $elfinder->realpath($file["hash"]) . "\n";
		}
	}

	if ($fp = fopen($logfile, "a")) {
		fwrite($fp, $log . "\n");
		fclose($fp);
	}
}

function access($attr, $path, $data, $volume)
{
	return strpos(basename($path), ".") === 0 ? !($attr == "read") || ($attr == "write") : ($attr == "read") || ($attr == "write");
}

function validName($name)
{
	return strpos($name, ".") !== 0;
}

error_reporting(32767);

if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Europe/Moscow");
}

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinderConnector.class.php";
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinder.class.php";
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinderVolumeDriver.class.php";
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinderVolumeLocalFileSystem.class.php";
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinderVolumeMySQL.class.php";
$acl = new elFinderTestACL();
$logger = new elFinderSimpleLogger("../files/temp/log.txt");
$opts = array(
	"locale" => "en_US.UTF-8",
	"bind"   => array(
		"mkdir mkfile  rename duplicate upload rm paste" => array($logger, "log")
		),
	"debug"  => true,
	"roots"  => array(
		array(
			"driver"     => "LocalFileSystem",
			"path"       => "../files/",
			"URL"        => dirname($_SERVER["PHP_SELF"]) . "/../files/",
			"alias"      => "File system",
			"mimeDetect" => "internal",
			"tmbPath"    => ".tmb",
			"utf8fix"    => true,
			"tmbCrop"    => false,
			"attributes" => array(
				array("pattern" => "~/\.~", "read" => false, "write" => false, "hidden" => true, "locked" => false),
				array("pattern" => "~/replace/.+png$~")
				)
			),
		array(
			"driver"     => "LocalFileSystem",
			"path"       => "../files2/",
			"URL"        => dirname($_SERVER["PHP_SELF"]) . "/../files2/",
			"alias"      => "Files",
			"mimeDetect" => "internal",
			"tmbPath"    => ".tmb",
			"utf8fix"    => true,
			"attributes" => array(
				array("pattern" => "~/\.~", "hidden" => true, "locked" => false)
				)
			),
		array("driver" => "MySQL", "path" => 1, "socket" => "/opt/local/var/run/mysql5/mysqld.sock", "user" => "root", "pass" => "hane", "db" => "elfinder", "user_id" => 1, "accessControl" => "access", "separator" => ":", "tmbCrop" => false, "tmbPath" => "../files/dbtmb", "tmbURL" => dirname($_SERVER["PHP_SELF"]) . "/../files/dbtmb/")
		)
	);
header("Access-Control-Allow-Origin: *");
$connector = new elFinderConnector(new elFinder($opts), true);
$connector->run();

?>

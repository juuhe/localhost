<?php

function access($attr, $path, $data, $volume)
{
	return strpos(basename($path), ".") === 0 ? !($attr == "read") || ($attr == "write") : ($attr == "read") || ($attr == "write");
}

error_reporting(0);
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinderConnector.class.php";
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinder.class.php";
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinderVolumeDriver.class.php";
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "elFinderVolumeLocalFileSystem.class.php";
$opts = array(
	"roots" => array(
		array("driver" => "LocalFileSystem", "path" => "../files/", "URL" => dirname($_SERVER["PHP_SELF"]) . "/../files/", "accessControl" => "access")
		)
	);
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

?>

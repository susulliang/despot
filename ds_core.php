<?php
/**
 * -----------------------------------------------------
 * @author Desmond Liang <me@desliang.com>
 * @copyright Copyright (c) 2016, Desmond Liang
 * @version 1.0.0 Beta
 * @link http://despot.desliang.com
 * @license GNU General Public License (see LICENSE.txt)
 * -----------------------------------------------------
 * This file load the configs, classes and functions
 * -----------------------------------------------------
 */

// Define DesPot Version
DEFINE(DESPOT_VERSION,"1.0.0 Beta");

// Show warning if a PHP version below 5.4.0 is used
if (version_compare(PHP_VERSION, '5.4.0') === -1) {
	echo 'This version of DeSpot requires at least PHP 5.4.0'.PHP_EOL;
	echo 'You are currently running ' . PHP_VERSION . '. Please update your PHP version.'.PHP_EOL;
	return;
}

try {
	// Load Functions
	require_once("ds_functions.php");

	// Reads in configs
	$configs = rwConfig::read("ds_config.php");

	// Define constants
	DEFINE(DESPOT_OPEN_REG,$configs["openregistration"]);
	DEFINE(DESPOT_OPEN_CMT,$configs["visitorcomment"]);
	DEFINE(DESPOT_SITENAME,$configs["sitename"]);
	DEFINE(DESPOT_SITESLOGAN,$configs["siteslogan"]);

	$timezoneoffset   = $configs["timezoneoffset"]; // Timezone offset
	$postsperpage     = $configs["posts_per_page"]; // How many posts per page
	$is_DST           = FALSE; // daylight saving
	$timezone_name    = timezone_name_from_abbr('', $timezoneoffset * 3600, $is_DST);
	date_default_timezone_set($timezone_name); // Set default timezone

	// Initialize database connection
	$DB = new DB();
	$DB->init($configs);

	// Start a session
	session_start();

} catch (Exception $ex) {
	echo "An unhandled exception has been thrown:" . PHP_EOL;
	echo $ex;
	exit(1);
}

?>

<?php
global $HTTP_COOKIE_VARS;
unset($HTTP_COOKIE_VARS);

define('IN_PHPBB', true);
define('MOPHPBB', true);

require 'cfg.php';
require 'log.php';

if ( $mo_enable_nuke )
{
	require '../mainfile.php';
	online();
	require '../includes/counter.php';
}

require $phpbb_root_path . 'extension.inc';
require $phpbb_root_path . 'common.' . $phpEx;
require $phpbb_includes_path . 'bbcode.' . $phpEx;
include_once $phpbb_includes_path . 'functions_post.' . $phpEx;
include_once $phpbb_includes_path . 'functions_search.' . $phpEx;

if ( $mo_enable_wurfl )
{
	require_once('./wurfl_cfg.php');
	require_once(WURFL_CLASS_FILE);
	
	$wurfl = new wurfl_class($wurfl, $wurfl_agents);
	$wurfl->GetDeviceCapabilitiesFromAgent($_SERVER["HTTP_USER_AGENT"]);
}
?>

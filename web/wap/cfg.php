<?php
$mo_enable_wurfl = 0;
$mo_enable_register = 0;
$mo_enable_nuke = 0;

$phpbb_root_path = '../';
$phpbb_includes_path = '../includes/';
if ( $mo_enable_nuke )
{
	$phpbb_root_path = '../modules/Forums/';
}

$mo_log_dir = 'logs';
?>

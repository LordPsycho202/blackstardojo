<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                             admin_medal_cfg.php
 *                            -------------------
 * Begin                : October 31, 2003
 * Email                : ycl6@users.sourceforge.net (http://macphpbbmod.sourceforge.net/)
 * Ver. 		: 2.1.0
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Medals']['Configuration'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

//
// Pull all config data
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in admin_medal_cfg", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['Config_updated'] . "<br /><br />" . sprintf($lang['Click_return_medalcfg'], "<a href=\"" . append_sid("admin_medal_cfg.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}
$medal_rand_yes = ( $new['medal_display_order'] ) ? "checked=\"checked\"" : "";
$medal_rand_no = ( !$new['medal_display_order'] ) ? "checked=\"checked\"" : "";

$medal_dispaly_yes = ( $new['allow_medal_dispaly'] ) ? "checked=\"checked\"" : "";
$medal_dispaly_no = ( !$new['allow_medal_dispaly'] ) ? "checked=\"checked\"" : "";

$template->set_filenames(array(
	"body" => "admin/medal_config_body.tpl")
);

//
// Escape any quotes in the site description for proper display in the text
// box on the admin page 
//

$template->assign_vars(array(
	"S_CONFIG_ACTION" => append_sid("admin_medal_cfg.$phpEx"),

	"L_YES" => $lang['Yes'],
	"L_NO" => $lang['No'],
	"L_CONFIGURATION_TITLE" => $lang['Medal_Config'],
	"L_CONFIGURATION_EXPLAIN" => $lang['Medal_Config_explain'],
	"L_MEDAL_SETTINGS" => $lang['Medal_setting'],
	"L_ALLOW_MEDAL" => $lang['Allow_medal'],
	"L_MEDAL_RAND" => $lang['Medal_rand'],
	"L_MEDAL_RAND_EXPLAIN" => $lang['Medal_rand_explain'],
	"L_MEDAL_DISPLAY" => $lang['Medal_dispaly'],
	"L_MEDAL_DISPLAY_EXPLAIN" => $lang['Medal_dispaly_explain'],
	"L_MEDAL_SIZE" => $lang['Medal_size'],
	"L_MEDAL_SIZE_EXPLAIN" => $lang['Medal_size_explain'],
	"L_SUBMIT" => $lang['Submit'], 
	"L_RESET" => $lang['Reset'], 

	"RAND_YES" => $medal_rand_yes,
	"RAND_NO" => $medal_rand_no,
	"MEDAL_YES" => $medal_dispaly_yes,
	"MEDAL_NO" => $medal_dispaly_no,
	"MEDAL_DISPALY_ROW" => $new['medal_display_row'],
	"MEDAL_DISPALY_COL" => $new['medal_display_col'],
	"MEDAL_DISPALY_W" => $new['medal_display_width'],
	"MEDAL_DISPALY_H" => $new['medal_display_height'])
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>

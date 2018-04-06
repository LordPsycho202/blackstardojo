<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                          admin_cash_indexpage.php
 *                            -------------------
 *   begin                : Tuesday, Sep 09, 2003
 *   copyright            : (C) 2003 naderman
 *   email                : nils_adermann@hotmail.com
 *
 *   $Id: admin_cash_indexpage.php,v 1.0.0.0 2003/09/09 14:12:37 naderman $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2003  naderman
 *
 *   This program is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU General Public License
 *   as published by the Free Software Foundation; either version 2
 *   of the License, or (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   http://www.gnu.org/copyleft/gpl.html
 *
 ***************************************************************************/

define('IN_PHPBB', 1);
define('IN_CASHMOD',true);

if(        !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Cash Mod']['Cash_Index Page'] = $file;
	return;
}

//
// Lets set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . $phpEx);

//
//check for userlevel
//
if( !$userdata['session_logged_in'] )
{
	header('Location: ' . append_sid("login.$phpEx?redirect=admin/admin_cash_indexpage.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
//end check

if ($HTTP_POST_VARS["progress"] != "alter") 
{
    
	$richdis = "";
	$richoff = "";
	$userdis = "";
	$useroff = "";
	$komma   = "";
	$br      = "";
	$cashmod = "";
	$points  = "";
	
	($board_config["cash_richdis"] == "yes") ?
		$richdis = "checked=\"checked\"" :
		$richoff = "checked=\"checked\"" ;
	($board_config["cash_userdis"] == "yes") ?
		$userdis = "checked=\"checked\"" :
		$useroff = "checked=\"checked\"" ;
        ($board_config["cash_komma"] == "komma") ?
		$kom = "checked=\"checked\""     :
		$br = "checked=\"checked\""      ;
        ($board_config["cash_pointsorcash"] == "cash") ?
		$cashmod = "checked=\"checked\""    :
		$points = "checked=\"checked\""  ;

	$select = '';
	if ($board_config['cash_pointsorcash'] == 'cash')
	{
		$template->assign_block_vars('switch_cashmod', array());

		$display_currency = $board_config['cash_displaycurrency'];
		if ($board_config['cash_indexfields'] == '')
		{
			$active_ids = array();
		}
		else
		{
			$active_ids = explode(',', str_replace(array("\n", "\r", " ", 0xFF, "\t"), '', $board_config['cash_indexfields']));
		}
		$checked = (empty($active_ids)) ? ' selected="selected"' : '';
		$dsp_checked = ($display_currency == '') ? ' selected="selected"' : '';
		$currency_select  = '<select name="display_currency">';
		$currency_select .= '<option value=""' . $dsp_checked . '>' . $lang['admin_cash_indexpage_highest_worth'] . '</option>';
		$currencies_select  = '<select name="currencies[]" size="6" multiple="multiple">';
		$currencies_select .= '<option value=""' . $checked . '>' . $lang['All'] . '</option>';
		$i = 0;
		while ( $c_cur = &$cash->currency_next($i, CURRENCY_ENABLED) ) 
		{
			if ((empty($active_ids)) || (in_array($c_cur->id(),$active_ids)))
			{
				$checked = ' selected="selected"';
			}
			else
			{
				$checked = '';
			}
			if ($c_cur->id() == $display_currency)
			{
				$dsp_checked = ' selected="selected"';
			}
			else
			{
				$dsp_checked = '';
			}
			$currencies_select .= '<option value="' . $c_cur->id() . '"' . $checked . '>' . $c_cur->name() . '</option>';
			$currency_select .= '<option value="' . $c_cur->id() . '"' . $dsp_checked . '>' . $c_cur->name() . '</option>';
		}
		$currencies_select .= '</select>';
		$currency_select .= '</select>';
	}
	
	$template->assign_vars(array(
	                             "TITEL" => $lang["cash_indexpage_title"],
	                             "DESCRIPTION" => $lang["admin_cash_indexpage_description"],
	                             "ACTION" => append_sid("admin_cash_indexpage.$phpEx"),
	                             "L_TITEL_TWO" => $lang["admin_cash_indexpage_config"],
	                             "L_YES" => $lang["Yes"],
	                             "L_NO" => $lang["No"],
	                             "L_DISPLAY_RICHEST" => $lang["admin_cash_indexpage_richdis"],
	                             "DISPLAY_RICHEST_YES" => $richdis,
	                             "DISPLAY_RICHEST_NO" => $richoff,
	                             "L_RICH_NUM" => $lang["admin_cash_indexpage_num"],
	                             "RICH_NUM" => $board_config["cash_richnum"],
	                             "L_DISPLAY_USERS" => $lang["admin_cash_indexpage_userdis"],
	                             "DISPLAY_USERS_YES" => $userdis,
	                             "DISPLAY_USERS_NO" => $useroff,
	                             "L_SEPERATOR" => $lang["admin_cash_indexpage_komma"],
				     "KOMMA" => $kom,
				     "BR" => $br,
				     "L_SYSTEM" => $lang["admin_cash_indexpage_pointsorcash"],
				     "CASH" => $cashmod,
				     "POINTS" => $points,
				     "L_SELECT_CURRENCIES" => $lang["admin_cash_indexpage_usecurrencies"],
				     "L_DISPLAY_CURRENCY" => $lang["admin_cash_indexpage_displaycurrency"],
				     "CURRENCIES_SELECT" => $currencies_select,
				     "CURRENCY_SELECT" => $currency_select,
				     "L_SUBMIT" => $lang["Submit"],
				     "L_RESET" => $lang["Reset"]
	                             ));
	
	$template->set_filenames(array(
	                               'body' => 'admin/cash_indexpage_config.tpl')
	                        );

}
else 
{
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . (($HTTP_POST_VARS["display_richest"] == "1") ? 'yes' : 'no') . "'
		WHERE config_name = 'cash_richdis'";
	if (!($db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Updating Cash on Index config<br>'.mysql_error());
	}

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . intval($HTTP_POST_VARS["richest_num"]) . "'
		WHERE config_name = 'cash_richnum'";
	if (!($db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Updating Cash on Index config<br>'.mysql_error());
	}

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . (($HTTP_POST_VARS["display_users"] == "1") ? 'yes' : 'no') . "'
		WHERE config_name = 'cash_userdis'";
	if (!($db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Updating Cash on Index config<br>'.mysql_error());
	}

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . (($HTTP_POST_VARS["seperator"] == "1") ? 'komma' : 'br') . "'
		WHERE config_name = 'cash_komma'";
	if (!($db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Updating Cash on Index config<br>'.mysql_error());
	}

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . (($HTTP_POST_VARS["system"] == "1") ? 'cash' : 'points') . "'
		WHERE config_name = 'cash_pointsorcash'";
	if (!($db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Updating Cash on Index config<br>'.mysql_error());
	}
	
	$select = '';
	if ($HTTP_POST_VARS["system"] == "1")
	{
		if ((!isset($HTTP_POST_VARS['currencies'])) || (intval($HTTP_POST_VARS['currencies'][0]) == 0))
		{
			$new_currencies = '';
		}
		else
		{
			$new_currencies_array = $HTTP_POST_VARS['currencies'];
			$num = count($new_currencies_array);
			for($i = 0; $i < $num; $i++)
			{
				if ($cash->currency_exists(intval($new_currencies_array[$i])))
				{
					$new_currencies[] = intval($new_currencies_array[$i]);
				}
			}
			$new_currencies = implode(',', $new_currencies);
		}
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $new_currencies . "'
			WHERE config_name = 'cash_indexfields'";
		if (!($db->sql_query($sql)))
		{
			message_die(GENERAL_MESSAGE, 'Fatal Error Updating Cash on Index config<br>'.mysql_error());
		}

		if ((!isset($HTTP_POST_VARS['display_currency'])) || (intval($HTTP_POST_VARS['display_currency']) == 0))
		{
			$new_currency = '';
		}
		else
		{
			$new_currency = intval($HTTP_POST_VARS['display_currency']);
			if (!$cash->currency_exists($new_currency))
			{
				$new_currency = '';
			}
			
		}
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $new_currency . "'
			WHERE config_name = 'cash_displaycurrency'";
		if (!($db->sql_query($sql)))
		{
			message_die(GENERAL_MESSAGE, 'Fatal Error Updating Cash on Index config<br>'.mysql_error());
		}
	}

	$message = $lang['admin_cash_indexpage_updated'] . "<br /><br />" . sprintf($lang['admin_click_return_cash_indexpage'], "<a href=\"" . append_sid("admin_cash_indexpage.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);

}




//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);


?>
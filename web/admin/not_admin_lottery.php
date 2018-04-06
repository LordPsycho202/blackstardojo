<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                             admin_lottery.php
 *                            -------------------
 *   Version              : 2.0.1
 *   email                : zarath@knightsofchaos.com
 *   forums               : http://www.ffsource.net/forums
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2004  Zarath
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

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Lottery_Settings'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
//
//check for userlevel
//
if( !$userdata['session_logged_in'] )
{
	header('Location: ' . append_sid("login.$phpEx?redirect=admin/admin_lottery.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
//end check
#
# Begin page functions
#

function duration($seconds)
{
	global $lang;

	switch($seconds)
	{
		case $seconds > 86399:
		{
			$days = preg_replace("/\..*/", '', ($seconds / 86400));
			$seconds = ($seconds - ($days * 86400));
			$string .= ( $days > 1 ) ? $days .' ' . $lang['lottery_days'] . ', ' : $days .' ' . $lang['lottery_day'] . ', ';
		}
		case $seconds > 3599:
		{
			$hours = preg_replace("/\..*/", '', ($seconds / 3600));
			$seconds = ( $days > 0 ) ? 0 : ($seconds - ($hours * 3600));
			if ( $seconds != 0 ) { $string .= ( $hours > 1 ) ? $hours .' ' . $lang['lottery_hours'] . ', ' : $hours .' ' . $lang['lottery_hour'] . ', '; }
			else { break; }
		}
		case $seconds > 59:
		{
			$minutes = preg_replace("/\..*/", '', ($seconds / 60));
			$seconds = ( $hours > 0 ) ? 0 : ($seconds - ($minutes * 60));
			if ( $seconds != 0 ) { $string .= ( $minutes > 1) ? $minutes .' ' . $lang['lottery_minutes'] . ', ' : $minutes .' ' . $lang['lottery_minute'] . ', '; }
			else { break; }
		}
		case $seconds > 0:
		{
			$string .= ( $seconds > 1 ) ? $seconds . ' ' . $lang['lottery_seconds'] . ', ' : $seconds . ' ' . $lang['lottery_second'] . ', ';
		}
	}
	$string = substr($string, 0, -2);
	return $string;
}

#
# End page functions
#
# Register action variable!
#

if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }

$thetime = time();

#
# Finish registering variable
#
if ( !(DEFINED('SHOP_TABLE')) )
{
	define('SHOP_ITEM_TABLE', 'phpbb_shopitems');
	define('SHOP_TABLE', 'phpbb_shops');
}

#
# Main lottery pages
#

if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'admin/lottery_config_body.tpl')
	);

	$sql = "SELECT *
		FROM " . LOTTERY_TABLE . "
		WHERE id > 0";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	$time_left = ( $board_config['lottery_start'] ) ? ($board_config['lottery_start'] + $board_config['lottery_length']) - $thetime : '-1';
	$duration_left = duration($time_left);

	$pool = (($sql_count * $board_config['lottery_cost'])/2) + $board_config['lottery_base'];
	$total_entries = $sql_count;

	#
	# Begin Items listing for addition to prize pool
	# ONLY do this if the shop items are enabled, incase there is no shop!
	#
	if ( $board_config['lottery_items'] )
	{
		$sql = "SELECT id, name
			FROM " . SHOP_ITEM_TABLE . "
			ORDER BY `name`";

		$result = $db->sql_query($sql);

		$sql_count = $db->sql_numrows($result);

		if ( $sql_count < 1 )
		{
			#
			# Code to toggle no history!
			#
			$template->assign_block_vars('switch_no_items', array(
			'MESSAGE' => $lang['lottery_no_items']));
		}
		else
		{
			#
			# Loop over the items in the DB and add them to a drop down after RANDOM item!
			#
			for ($i = 0; $i < $sql_count; $i++)
			{
				if (!( $row = $db->sql_fetchrow($result) ))
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
				}

				$template->assign_block_vars('item_listrow', array(
					'ITEM_ID' => $row['id'],
					'ITEM_NAME' => $row['name']
				));
			}

			$template->assign_block_vars('switch_are_items', array());
		}

	#
	# Begin items listing for items already in prize pool!
	# ONLY do this if the shop items are enabled, incase there is no shop!
	#
		$item_array = explode(';', $board_config['lottery_win_items']);
		$item_count = count($item_array);

		if ( ($item_count > 0) && (!empty($item_array[0])) )
		{
			for ($i = 0; $i < $item_count; $i++)
			{
				$item_array[$i] = ( $item_array[$i] == "random" ) ? $lang['lottery_rand'] : $item_array[$i];

				$template->assign_block_vars('pool_listrow', array(
					'ITEM_NAME' => $item_array[$i]
				));
			}

			$template->assign_block_vars('switch_pool_items', array());
		}

	#
	# Begin listing of all shops. This is for the RAND settings!
	# 
		$sql = "SELECT *
			FROM " . SHOP_TABLE . "
			ORDER BY `shopname`";

		$result = $db->sql_query($sql);

		$sql_count = $db->sql_numrows($result);

		if ( $sql_count > 0 )
		{
			#
			# Loop over the shops list results!
			#
			for ($i = 0; $i < $sql_count; $i++)
			{
				if (!( $row = $db->sql_fetchrow($result) ))
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop'), '', __LINE__, __FILE__, $sql);
				}

				$shop_selected = ( $board_config['lottery_random_shop'] == $row['shopname'] ) ? 'SELECTED' : '';

				$template->assign_block_vars('rand_listrow', array(
					'SHOP_NAME' => $row['shopname'],
					'SELECTED' => $shop_selected
				));
			}

		}

		$template->assign_block_vars('switch_enabled_items', array());
	}

	#
	# Begin Cash checks & loop
	#
	if ( DEFINED('CASH_TABLE') )
	{
		$sql = "SELECT *
			FROM " . CASH_TABLE;

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'cash'), '', __LINE__, __FILE__, $sql);
		}
		if ( ($sql_count = $db->sql_numrows($result)) && ($sql_count > 0) )
		{
			for ($i = 0; $i < $sql_count; $i++)
			{
				if (!( $cash_row = $db->sql_fetchrow($result) ))
				{
					message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'cash'), '', __LINE__, __FILE__, $sql);
				}

				$selected = ( $cash_row['cash_name'] = $board_config['lottery_currency'] ) ? 'SELECTED' : '';

				$template->assign_block_vars('cash_listrow', array(
					'CASH_NAME' => $cash_row['cash_name'],
					'SELECTED' => $shop_selected
				));
			}
			$template->assign_block_vars('switch_cash', array());
		}
	}

	#
	# Grab last winner from lottery -- ORDERED BY TIME
	#
	$sql = "SELECT t1.*, t2.username
		FROM " . LOTTERY_HISTORY_TABLE . " as t1, " . USERS_TABLE . " as t2
		WHERE t2.user_id = t1.user_id
		ORDER BY time DESC";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
	}

	if ( $db->sql_numrows($result) > 0 )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
		}
		$last_won = $row['username'];
	}
	else { $last_won = $lang['lottery_no_one']; }

	#
	# Begin lottery SELECTion setups!
	#
	$l_status_on = ( $board_config['lottery_status'] ) ? 'SELECTED' : '';
	$l_status_off = ( $board_config['lottery_status'] ) ? '' : 'SELECTED';

	$l_reset_on = ( $board_config['lottery_reset'] ) ? 'SELECTED' : '';
	$l_reset_off = ( $board_config['lottery_reset'] ) ? '' : 'SELECTED';

	$l_ticktype_sing = ( $board_config['lottery_ticktype'] == 'single' ) ? 'SELECTED' : '';
	$l_ticktype_mult = ( $board_config['lottery_ticktype'] == 'single' ) ? '' : 'SELECTED';

	$l_items_on = ( $board_config['lottery_items'] ) ? 'SELECTED' : '';
	$l_items_off = ( $board_config['lottery_items'] ) ? '' : 'SELECTED';

	$l_history_on = ( $board_config['lottery_history'] ) ? 'SELECTED' : '';
	$l_history_off = ( $board_config['lottery_history'] ) ? '' : 'SELECTED';

	$l_display_on = ( $board_config['lottery_show_entries'] ) ? 'SELECTED' : '';
	$l_display_off = ( $board_config['lottery_show_entries'] ) ? '' : 'SELECTED';

	$l_multi_tickets_on = ( $board_config['lottery_mb'] ) ? 'SELECTED' : '';
	$l_multi_tickets_off = ( $board_config['lottery_mb'] ) ? '' : 'SELECTED';
	
	$template->assign_vars(array(
		'V_L_NAME' => $board_config['lottery_name'],
		'V_L_BASE' => $board_config['lottery_base'],
		'V_L_COST' => $board_config['lottery_cost'],
		'V_L_LENGTH' => $board_config['lottery_length'],
		'L_MULTI_TICKETS_MAX' => $board_config['lottery_mb_amount'],
		'L_RAND_COST_MIN' => $board_config['lottery_item_mcost'],
		'L_RAND_COST_MAX' => $board_config['lottery_item_xcost'],
		'L_STATUS_ON' => $l_status_on,
		'L_STATUS_OFF' => $l_status_off,
		'L_RESET_ON' => $l_reset_on,
		'L_RESET_OFF' => $l_reset_off,
		'L_TICKTYPE_SING' => $l_ticktype_sing,
		'L_TICKTYPE_MULT' => $l_ticktype_mult,
		'L_ITEMS_ON' => $l_items_on,
		'L_ITEMS_OFF' => $l_items_off,
		'L_HISTORY_ON' => $l_history_on,
		'L_HISTORY_OFF' => $l_history_off,
		'L_DISPLAY_ON' => $l_display_on,
		'L_DISPLAY_OFF' => $l_display_off,
		'L_MULTI_TICKETS_ON' => $l_multi_tickets_on,
		'L_MULTI_TICKETS_OFF' => $l_multi_tickets_off,

		'L_LAST_WON' => $last_won,
		'L_TOTAL_ENTRIES' => $total_entries,
		'L_POOL' => $pool,
		'L_DURATION' => $duration_left,
		'L_TIME_LEFT' => $time_left,
		'L_TABLE_TITLE' => $lang['lottery_statistics'],
		'L_CONFIG_TITLE' => $lang['lottery_edit_settings'],
		'L_ENTRIES_TOTAL' => $lang['lottery_total_entries'],
		'L_DURATION_LEFT' => $lang['lottery_duration_left'],
		'L_LEFT_TIME' => $lang['lottery_time_left'],
		'L_SECONDS' => $lang['lottery_seconds'],
		'L_LOTTERY_POOL' => $lang['lottery_pool'],
		'L_WON_LAST' => $lang['lottery_last_won'],
		'L_LOTTERY_STATUS' => $lang['lottery_status'],
		'L_AUTO_RESTART' => $lang['lottery_auto_restart'],
		'L_NAME' => $lang['lottery_name'],
		'L_BASE_AMOUNT' => $lang['lottery_base_amount'],
		'L_ENTRY_COST' => $lang['lottery_entry_cost'],
		'L_DRAW_PERIODS' => $lang['lottery_draw_periods'],
		'L_TICKETS_ALLOWED' => $lang['lottery_tickets_allowed'],
		'L_SINGLE' => $lang['lottery_single'],
		'L_MULTIPLE' => $lang['lottery_multiple'],
		'L_MULT_TICKETS' => $lang['lottery_mult_tickets'],
		'L_ON' => $lang['lottery_on'],
		'L_OFF' => $lang['lottery_off'],
		'L_MAX' => $lang['lottery_max'],
		'L_FULL_DISPLAY' => $lang['lottery_full_display'],
		'L_ITEM_POOL' => $lang['lottery_item_pool'],
		'L_HISTORY' => $lang['lottery_history'],
		'L_CURRENCY' => $lang['lottery_currency'],
		'L_UPDATE' => $lang['lottery_update'],
		'L_FROM_SHOP' => $lang['lottery_from_shop'],
		'L_ALL_SHOPS' => $lang['lottery_all_shops'],
		'L_MIN_COST' => $lang['lottery_min_cost'],
		'L_MAX_COST' => $lang['lottery_max_cost'],
		'L_UPDATE_SETTINGS' => $lang['lottery_update_settings'],
		'L_CURRENT_ITEMS' => $lang['lottery_current_items'],
		'L_REMOVE_ITEM' => $lang['lottery_remove_item'],
		'L_ADD_ITEMS' => $lang['lottery_add_items'],
		'L_RANDOM' => $lang['lottery_rand'],
		'L_ADD_ITEM' => $lang['lottery_add_item'],
		'L_ITEMS_TITLE' => $lang['lottery_items_table'],
		'L_RAND_ITEMS_TITLE' => $lang['lottery_items_settings'],

		'S_CONFIG_ACTION' => append_sid('admin_lottery.' . $phpEx),
		'TITLE' => $lang['lottery_editor'],
		'EXPLAIN' => $lang['lottery_index_explain'])
	);
	
}

elseif ($action == "update")
{
	#
	# Register update variables!
	#
	if ( isset($HTTP_GET_VARS['name']) || isset($HTTP_POST_VARS['name']) ) { $name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name']; }
	else { $name = $board_config['lottery_name']; }
	if ( isset($HTTP_GET_VARS['baseamount']) || isset($HTTP_POST_VARS['baseamount']) ) { $baseamount = ( isset($HTTP_POST_VARS['baseamount']) ) ? intval($HTTP_POST_VARS['baseamount']) : intval($HTTP_GET_VARS['baseamount']); }
	else { $baseamount = $board_config['lottery_base']; }
	if ( isset($HTTP_GET_VARS['ticketcost']) || isset($HTTP_POST_VARS['ticketcost']) ) { $ticketcost = ( isset($HTTP_POST_VARS['ticketcost']) ) ? intval($HTTP_POST_VARS['ticketcost']) : intval($HTTP_GET_VARS['ticketcost']); }
	else { $ticketcost = $board_config['lottery_cost']; }
	if ( isset($HTTP_GET_VARS['drawperiod']) || isset($HTTP_POST_VARS['drawperiod']) ) { $drawperiod = ( isset($HTTP_POST_VARS['drawperiod']) ) ? intval($HTTP_POST_VARS['drawperiod']) : intval($HTTP_GET_VARS['drawperiod']); }
	else { $drawperiod = $board_config['lottery_length']; }
	if ( isset($HTTP_GET_VARS['tickets']) || isset($HTTP_POST_VARS['tickets']) ) { $tickets = ( isset($HTTP_POST_VARS['tickets']) ) ? $HTTP_POST_VARS['tickets'] : $HTTP_GET_VARS['tickets']; }
	else { $tickets = $board_config['lottery_ticktype']; }
	if ( isset($HTTP_GET_VARS['restart']) || isset($HTTP_POST_VARS['restart']) ) { $restart = ( isset($HTTP_POST_VARS['restart']) ) ? intval($HTTP_POST_VARS['restart']) : intval($HTTP_GET_VARS['restart']); }
	else { $restart = $board_config['lottery_reset']; }
	if ( isset($HTTP_GET_VARS['status']) || isset($HTTP_POST_VARS['status']) ) { $status = ( isset($HTTP_POST_VARS['status']) ) ? intval($HTTP_POST_VARS['status']) : intval($HTTP_GET_VARS['status']); }
	else { $status = $board_config['lottery_status']; }
	if ( isset($HTTP_GET_VARS['multi_tickets']) || isset($HTTP_POST_VARS['multi_tickets']) ) { $multi_tickets = ( isset($HTTP_POST_VARS['multi_tickets']) ) ? intval($HTTP_POST_VARS['multi_tickets']) : intval($HTTP_GET_VARS['multi_tickets']); }
	else { $multi_tickets = $board_config['lottery_mb']; }
	if ( isset($HTTP_GET_VARS['multi_tickets_max']) || isset($HTTP_POST_VARS['multi_tickets_max']) ) { $multi_tickets_max = ( isset($HTTP_POST_VARS['multi_tickets_max']) ) ? intval($HTTP_POST_VARS['multi_tickets_max']) : intval($HTTP_GET_VARS['multi_tickets_max']); }
	else { $multi_tickets_max = $board_config['lottery_mb_amount']; }
	if ( isset($HTTP_GET_VARS['full_display']) || isset($HTTP_POST_VARS['full_display']) ) { $full_display = ( isset($HTTP_POST_VARS['full_display']) ) ? intval($HTTP_POST_VARS['full_display']) : intval($HTTP_GET_VARS['full_display']); }
	else { $full_display = $board_config['lottery_show_entries']; }
	if ( isset($HTTP_GET_VARS['item_pool']) || isset($HTTP_POST_VARS['item_pool']) ) { $item_pool = ( isset($HTTP_POST_VARS['item_pool']) ) ? intval($HTTP_POST_VARS['item_pool']) : intval($HTTP_GET_VARS['item_pool']); }
	else { $item_pool = $board_config['lottery_items']; }
	if ( isset($HTTP_GET_VARS['history']) || isset($HTTP_POST_VARS['history']) ) { $history = ( isset($HTTP_POST_VARS['history']) ) ? intval($HTTP_POST_VARS['history']) : intval($HTTP_GET_VARS['history']); }
	else { $history = $board_config['lottery_items']; }
	if ( isset($HTTP_GET_VARS['cash_name']) || isset($HTTP_POST_VARS['cash_name']) ) { $cash_name = ( isset($HTTP_POST_VARS['cash_name']) ) ? $HTTP_POST_VARS['cash_name'] : $HTTP_GET_VARS['cash_name']; }
	else { $cash_name = $board_config['lottery_currency']; }

	#
	# Update all lottery variables. :)
	#
	$usql = array();
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$name'
		WHERE config_name='lottery_name'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$baseamount'
		WHERE config_name='lottery_base'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$ticketcost'
		WHERE config_name='lottery_cost'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$drawperiod'
		WHERE config_name='lottery_length'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$tickets'
		WHERE config_name='lottery_ticktype'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$restart'
		WHERE config_name='lottery_reset'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$multi_tickets'
		WHERE config_name='lottery_mb'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$multi_tickets_max'
		WHERE config_name='lottery_mb_amount'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$full_display'
		WHERE config_name='lottery_show_entries'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$item_pool'
		WHERE config_name='lottery_items'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$history'
		WHERE config_name='lottery_history'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$cash_name'
		WHERE config_name='lottery_currenecy'";

	if ( ($status) && !($board_config['lottery_status']) )
	{ 
		$usql[] = "UPDATE ". CONFIG_TABLE . "
			SET config_value='1'
			WHERE config_name='lottery_status'"; 
		$usql[] = "UPDATE ". CONFIG_TABLE . "
			SET config_value='$thetime'
			WHERE config_name='lottery_start'"; 
	}
	elseif ( $status )
	{
		$usql[] = "UPDATE ". CONFIG_TABLE . "
			SET config_value='1'
			WHERE config_name='lottery_status'";
	}
	else
	{ 
		$usql[] = "UPDATE ". CONFIG_TABLE . "
			SET config_value='0'
			WHERE config_name='lottery_status'"; 
		$usql[] = "UPDATE ". CONFIG_TABLE . "
			SET config_value='0'
			WHERE config_name='lottery_start'"; 
	}

	$sql_count = count($usql);
	for ($i = 0; $i < $sql_count; $i++)
	{
		if ( !($result = $db->sql_query($usql[$i])) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}
	}
	message_die(GENERAL_MESSAGE, $lang['lottery_updated'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
}

elseif ( $action == 'item_pool' )
{
	if ( isset($HTTP_GET_VARS['del_item']) || isset($HTTP_POST_VARS['del_item']) ) { $del_item = ( isset($HTTP_POST_VARS['del_item']) ) ? $HTTP_POST_VARS['del_item'] : $HTTP_GET_VARS['del_item']; }
	if ( isset($HTTP_GET_VARS['add_item']) || isset($HTTP_POST_VARS['add_item']) ) { $add_item = ( isset($HTTP_POST_VARS['add_item']) ) ? $HTTP_POST_VARS['add_item'] : $HTTP_GET_VARS['add_item']; }

	if ( !empty($del_item) )
	{
		if ( isset($HTTP_GET_VARS['item_name']) || isset($HTTP_POST_VARS['item_name']) ) { $item_id = ( isset($HTTP_POST_VARS['item_name']) ) ? $HTTP_POST_VARS['item_name'] : $HTTP_GET_VARS['item_name']; }
		else { message_die(GENERAL_MESSAGE, "Cannot read item_name variable!"); }

		if ( substr($board_config['lottery_win_items'], 0, strlen($item_id)) == $item_id )
		{
			$lottery_items = substr_replace($board_config['lottery_win_items'], "", 0, strlen($item_id . ";"));
		}
		else
		{
			$lottery_items = substr_replace($board_config['lottery_win_items'], "", strpos($board_config['lottery_win_items'], ';' . $item_id), strlen(';' . $item_id));
		}		

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . addslashes($lottery_items) . "'
			WHERE config_name = 'lottery_win_items'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['lottery_item_removed'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
	}
	elseif ( !empty($add_item) )
	{
		if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); }
		else { message_die(GENERAL_MESSAGE, "Cannot read item_id variable!"); }

		if ( $item_id != 'random' )
		{
			$sql = "SELECT *
				FROM " . SHOP_ITEM_TABLE . "
				WHERE id = '$item_id'";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
			}

			if ( !($db->sql_numrows($result)) )
			{
				message_die(GENERAL_MESSAGE, $lang['lottery_no_item']);
			}

			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
			}

			$lottery_items = ( empty($board_config['lottery_win_items']) ) ? $row['name'] : $board_config['lottery_win_items'] . ';' . $row['name'];
		}
		else
		{
			$lottery_items = ( empty($board_config['lottery_win_items']) ) ? 'random' : $board_config['lottery_win_items'] . ';random';
		}

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . addslashes($lottery_items) . "'
			WHERE config_name = 'lottery_win_items'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['lottery_item_added'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
	}

	else { message_die(GENERAL_MESSAGE, $lang['lottery_invalid_action']); }
}

elseif ( $action == 'rand_settings' )
{
	#
	# Register update variables!
	#
	if ( isset($HTTP_GET_VARS['rand_min_cost']) || isset($HTTP_POST_VARS['rand_min_cost']) ) { $rand_min_cost = ( isset($HTTP_POST_VARS['rand_min_cost']) ) ? intval($HTTP_POST_VARS['rand_min_cost']) : intval($HTTP_GET_VARS['rand_min_cost']); }
	else { $rand_min_cost = $board_config['lottery_item_mcost']; }

	if ( isset($HTTP_GET_VARS['rand_max_cost']) || isset($HTTP_POST_VARS['rand_max_cost']) ) { $rand_max_cost = ( isset($HTTP_POST_VARS['rand_max_cost']) ) ? intval($HTTP_POST_VARS['rand_max_cost']) : intval($HTTP_GET_VARS['rand_max_cost']); }
	else { $rand_max_cost = $board_config['lottery_item_xcost']; }

	if ( isset($HTTP_GET_VARS['rand_shop']) || isset($HTTP_POST_VARS['rand_shop']) ) { $rand_shop = ( isset($HTTP_POST_VARS['rand_shop']) ) ? $HTTP_POST_VARS['rand_shop'] : $HTTP_GET_VARS['rand_shop']; }
	else { $rand_shop = $board_config['lottery_random_shop']; }

	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$rand_min_cost'
		WHERE config_name='lottery_item_mcost'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$rand_max_cost'
		WHERE config_name='lottery_item_xcost'";
	$usql[] = "UPDATE ". CONFIG_TABLE . "
		SET config_value='$rand_shop'
		WHERE config_name='lottery_random_shop'";

	$sql_count = count($usql);
	for ($i = 0; $i < $sql_count; $i++)
	{
		if ( !($result = $db->sql_query($usql[$i])) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}
	}
	message_die(GENERAL_MESSAGE, $lang['lottery_random_items_updated'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
}

else { message_die(GENERAL_MESSAGE, $lang['lottery_invalid_action']); }


//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);


?>
